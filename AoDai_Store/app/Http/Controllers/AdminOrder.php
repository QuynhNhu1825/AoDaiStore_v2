<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class AdminOrder extends Controller
{
    /**
     * Danh sách đơn hàng Admin.
     * Tìm kiếm, lọc trạng thái và phân trang.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Tìm kiếm theo số điện thoại
        if ($request->filled('search_phone')) {
            $query->where(
                'SDTNguoiNhan',
                'LIKE',
                '%' . $request->search_phone . '%'
            );
        }

        // Lọc trạng thái
        $validStatuses = [
            'ChoXacNhan',
            'DaXacNhan',
            'DangGiao',
            'DaGiao',
            'DaHuy',
        ];

        if (
            $request->filled('status') &&
            in_array($request->status, $validStatuses, true)
        ) {
            $query->where('TrangThai', $request->status);
        }

        $orders = $query
            ->orderByDesc('MaDonHang')
            ->paginate(10)
            ->withQueryString();

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng.
     */
    public function show($id)
    {
        $order = Order::where('MaDonHang', $id)
            ->with([
                'orderDetails.productDetail.product',
                'orderDetails.productDetail.color',
                'orderDetails.productDetail.size',
            ])
            ->firstOrFail();

        $orderDetails = $order->orderDetails;

        return view('admin.order.show', compact(
            'order',
            'orderDetails'
        ));
    }

    /**
     * Cập nhật trạng thái đơn hàng.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'ChoXacNhan',
                    'DaXacNhan',
                    'DangGiao',
                    'DaGiao',
                    'DaHuy',
                ]),
            ],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $order = Order::where('MaDonHang', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $currentStatus = $order->TrangThai;
                $newStatus = $request->status;

                // Quy tắc chuyển trạng thái
                $rules = [
                    'ChoXacNhan' => ['DaXacNhan', 'DaHuy'],
                    'DaXacNhan' => ['DangGiao', 'DaHuy'],
                    'DangGiao' => ['DaGiao'],
                    'DaGiao' => [],
                    'DaHuy' => [],
                ];

                if ($newStatus === $currentStatus) {
                    return;
                }

                if (!in_array(
                    $newStatus,
                    $rules[$currentStatus] ?? [],
                    true
                )) {
                    throw new Exception(
                        'Không thể chuyển từ trạng thái hiện tại sang trạng thái đã chọn.'
                    );
                }

                // Admin hủy đơn hàng
                if ($newStatus === 'DaHuy') {

                    $orderDetails = OrderDetail::where(
                        'MaDonHang',
                        $order->MaDonHang
                    )
                        ->orderBy('MaChiTietSP')
                        ->get();

                    if ($orderDetails->isEmpty()) {
                        throw new Exception(
                            'Không tìm thấy chi tiết đơn hàng để hoàn kho.'
                        );
                    }

                    // Hoàn lại tồn kho
                    foreach ($orderDetails as $detail) {

                        $productDetail = ProductDetail::where(
                            'MaChiTietSP',
                            $detail->MaChiTietSP
                        )
                            ->lockForUpdate()
                            ->first();

                        if (!$productDetail) {
                            throw new Exception(
                                'Không tìm thấy biến thể sản phẩm.'
                            );
                        }

                        $productDetail->increment(
                            'SoLuong',
                            $detail->SoLuong
                        );
                    }
                }

                // Cập nhật trạng thái
                $order->TrangThai = $newStatus;

                // COD: chỉ đánh dấu thanh toán khi đã giao
                // và được xác nhận theo quy trình của hệ thống.
                if (
                    $newStatus === 'DaGiao' &&
                    $order->PhuongThucThanhToan === 'cod' &&
                    !$order->NgayThanhToan
                ) {
                    $order->NgayThanhToan = now();
                }

                $order->save();
            });

            return back()->with(
                'success',
                'Cập nhật trạng thái đơn hàng thành công.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * Route cũ dùng để xác nhận trạng thái.
     * Chuyển về cùng một logic để tránh bỏ qua quy tắc.
     */
    public function confirmstatus(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }
}

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
     * 1. Danh sách đơn hàng Admin
     * Tìm kiếm, lọc và phân trang.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Tìm kiếm theo số điện thoại
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
          $query->where(function ($q) use ($search) {
              $q->where('HoTenNguoiNhan', 'LIKE', "%{$search}%")
                ->orWhere('SDTNguoiNhan', 'LIKE', "%{$search}%");
          });
        }

        // Lọc theo trạng thái đơn hàng
        $validStatuses = [
            'ChoXacNhan',
            'DaXacNhan',
            'DangGiao',
            'DaGiao',
            'DaHuy',
        ];

        if (in_array($request->status, $validStatuses, true)) {
            $query->where('TrangThai', $request->status);
        }

        // Lọc theo phương thức thanh toán
        $paymentMethods = [
            'cod',
            'vietqr',
        ];

        if (in_array(
            $request->payment_method,
            $paymentMethods,
            true
        )) {
            $query->where(
                'PhuongThucThanhToan',
                $request->payment_method
            );
        }

        // Lọc theo trạng thái thanh toán
        $paymentStatuses = [
            'ChuaThanhToan',
            'ChoXacNhanThanhToan',
            'DaThanhToan',
        ];

        if (in_array(
            $request->payment_status,
            $paymentStatuses,
            true
        )) {
            $query->where(
                'TrangThaiThanhToan',
                $request->payment_status
            );
        }

        $orders = $query
            ->orderByDesc('MaDonHang')
            ->paginate(10)
            ->withQueryString();
        $totalOrders = \App\Models\Order::count();

$pendingOrders = \App\Models\Order::where(
    'TrangThai', 'ChoXacNhan'
)->count();

$shippingOrders = \App\Models\Order::where(
    'TrangThai', 'DangGiao'
)->count();

$deliveredOrders = \App\Models\Order::where(
    'TrangThai', 'DaGiao'
)->count();

        return view(
            'admin.order.index',
            compact('orders', 'totalOrders', 'pendingOrders', 'shippingOrders', 'deliveredOrders')
        );
    }

    /**
     * 2. Xem chi tiết đơn hàng.
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

        return view(
            'admin.order.show',
            compact('order', 'orderDetails')
        );
    }

    /**
     * 3. Cập nhật trạng thái đơn hàng.
     */
    public function updateStatus(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
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

            DB::transaction(function () use ($validated, $id) {

                // Khóa đơn hàng để tránh cập nhật đồng thời
                $order = Order::where('MaDonHang', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $currentStatus = $order->TrangThai;
                $newStatus = $validated['status'];

                // Quy tắc chuyển trạng thái
                $rules = [
                    'ChoXacNhan' => [
                        'DaXacNhan',
                        'DaHuy',
                    ],

                    'DaXacNhan' => [
                        'DangGiao',
                        'DaHuy',
                    ],

                    'DangGiao' => [
                        'DaGiao',
                    ],

                    'DaGiao' => [],

                    'DaHuy' => [],
                ];

                // Trạng thái không thay đổi
                if ($newStatus === $currentStatus) {
                    return;
                }

                // Kiểm tra chuyển trạng thái hợp lệ
                if (!in_array(
                    $newStatus,
                    $rules[$currentStatus] ?? [],
                    true
                )) {
                    throw new Exception(
                        'Không thể chuyển từ trạng thái hiện tại sang trạng thái đã chọn.'
                    );
                }

                /**
                 * VIETQR
                 *
                 * Không cho xác nhận đơn nếu
                 * thanh toán chưa được xác nhận.
                 *
                 * Việc xác nhận VietQR được thực hiện
                 * bằng hàm confirmPayment().
                 */
                if (
                    $newStatus === 'DaXacNhan' &&
                    $order->PhuongThucThanhToan === 'vietqr' &&
                    $order->TrangThaiThanhToan !== 'DaThanhToan'
                ) {
                    throw new Exception(
                        'Đơn VietQR chưa được xác nhận thanh toán.'
                    );
                }

                /**
                 * KIỂM TRA HỦY ĐƠN
                 *
                 * Không hủy trực tiếp đơn đã thanh toán
                 * vì chưa có quy trình hoàn tiền.
                 */
                if (
                    $newStatus === 'DaHuy' &&
                    $order->TrangThaiThanhToan === 'DaThanhToan'
                ) {
                    throw new Exception(
                        'Đơn hàng đã thanh toán. Vui lòng xử lý hoàn tiền trước khi hủy.'
                    );
                }

                /**
                 * HỦY ĐƠN HÀNG
                 * Hoàn lại tồn kho.
                 */
                if ($newStatus === 'DaHuy') {

                    $details = OrderDetail::where(
                        'MaDonHang',
                        $order->MaDonHang
                    )
                        ->orderBy('MaChiTietSP')
                        ->get();

                    if ($details->isEmpty()) {
                        throw new Exception(
                            'Không tìm thấy chi tiết đơn hàng để hoàn kho.'
                        );
                    }

                    foreach ($details as $detail) {

                        $variant = ProductDetail::where(
                            'MaChiTietSP',
                            $detail->MaChiTietSP
                        )
                            ->lockForUpdate()
                            ->first();

                        if (!$variant) {
                            throw new Exception(
                                'Không tìm thấy biến thể sản phẩm để hoàn kho.'
                            );
                        }

                        // Hoàn lại số lượng sản phẩm
                        $variant->increment(
                            'SoLuong',
                            $detail->SoLuong
                        );
                    }
                }

                /**
                 * CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
                 */
                $order->TrangThai = $newStatus;

                /**
                 * THANH TOÁN COD
                 *
                 * Khi Admin xác nhận đã giao hàng,
                 * hệ thống ghi nhận đã thanh toán.
                 */
                if (
                    $newStatus === 'DaGiao' &&
                    $order->PhuongThucThanhToan === 'cod' &&
                    $order->TrangThaiThanhToan !== 'DaThanhToan'
                ) {
                    $order->TrangThaiThanhToan = 'DaThanhToan';

                    $order->NgayThanhToan = now();
                }

                // Lưu thay đổi
                $order->save();
            });

            return back()->with(
                'success',
                'Cập nhật trạng thái đơn hàng thành công!'
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
     * 4. Xác nhận trạng thái đơn hàng.
     *
     * Giữ tương thích với route cũ.
     */
    public function confirmstatus(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }

    /**
     * 5. Xác nhận thanh toán VietQR.
     *
     * Khi Admin xác nhận thanh toán:
     *
     * - Thanh toán: DaThanhToan
     * - Đơn hàng: DaXacNhan
     * - Lưu thời gian thanh toán
     *
     * Thực hiện trong cùng transaction.
     */
    public function confirmPayment($id)
    {
        try {

            DB::transaction(function () use ($id) {

                // Khóa đơn hàng để tránh xử lý đồng thời
                $order = Order::where('MaDonHang', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /**
                 * Kiểm tra phương thức thanh toán.
                 */
                if (
                    $order->PhuongThucThanhToan !== 'vietqr'
                ) {
                    throw new Exception(
                        'Đơn hàng không sử dụng phương thức VietQR.'
                    );
                }

                /**
                 * Chỉ xử lý đơn đang chờ xác nhận.
                 *
                 * Không xác nhận lại đơn đã xử lý
                 * hoặc đơn đã hủy.
                 */
                if (
                    $order->TrangThai !== 'ChoXacNhan'
                ) {
                    throw new Exception(
                        'Đơn hàng không ở trạng thái chờ xác nhận.'
                    );
                }

                /**
                 * Khách phải nhấn "Đã chuyển khoản"
                 * trước khi Admin có thể xác nhận.
                 */
                if (
                    $order->TrangThaiThanhToan !==
                    'ChoXacNhanThanhToan'
                ) {
                    throw new Exception(
                        'Đơn hàng chưa ở trạng thái chờ xác nhận thanh toán.'
                    );
                }

                /**
                 * BƯỚC 1:
                 * Xác nhận thanh toán VietQR.
                 */
                $order->TrangThaiThanhToan = 'DaThanhToan';

                $order->NgayThanhToan = now();

                /**
                 * BƯỚC 2:
                 * Tự động xác nhận đơn hàng.
                 */
                $order->TrangThai = 'DaXacNhan';

                /**
                 * BƯỚC 3:
                 * Lưu đồng thời hai trạng thái.
                 */
                $order->save();
            });

            return back()->with(
                'success',
                'Xác nhận thanh toán VietQR và đơn hàng thành công!'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
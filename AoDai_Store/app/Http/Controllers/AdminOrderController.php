<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class AdminOrderController extends Controller
{
    /**
     * 1. DANH SÁCH ĐƠN HÀNG
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Tìm kiếm theo mã đơn hàng, họ tên hoặc số điện thoại
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('HoTenNguoiNhan', 'LIKE', "%{$search}%")
                  ->orWhere('SDTNguoiNhan', 'LIKE', "%{$search}%")
                   ->orWhere('MaDonHang', 'LIKE', "%{$search}%");
            });
        }

        // Lọc trạng thái đơn hàng
        $validStatuses = array_keys(Order::STATUS_LABELS);

        if (in_array($request->input('status'), $validStatuses, true)) {
            $query->where('TrangThai', $request->input('status'));
        }

        // Lọc phương thức thanh toán
        if (in_array(
            $request->input('payment_method'),
            ['cod', 'vietqr'],
            true
        )) {
            $query->where(
                'PhuongThucThanhToan',
                $request->input('payment_method')
            );
        }

        // Lọc trạng thái thanh toán
        $paymentStatuses = [
            'ChuaThanhToan',
            'ChoXacNhanThanhToan',
            'DaThanhToan',
        ];

        if (in_array(
            $request->input('payment_status'),
            $paymentStatuses,
            true
        )) {
            $query->where(
                'TrangThaiThanhToan',
                $request->input('payment_status')
            );
        }

        // Lọc yêu cầu hủy đơn
        $cancellationStatuses = [
            'KhongCo',
            'ChoDuyet',
            'DaDuyet',
            'TuChoi',
        ];

        if (in_array(
            $request->input('cancellation_status'),
            $cancellationStatuses,
            true
        )) {
            $query->where(
                'TrangThaiYeuCauHuy',
                $request->input('cancellation_status')
            );

            if ($request->input('cancellation_status') === 'ChoDuyet') {
                $query->where('TrangThai', 'DaXacNhan');
            }
        }

        // Phân trang
        $orders = $query
            ->orderByDesc('MaDonHang')
            ->paginate(10)
            ->withQueryString();

        // Thống kê
        $statusCounts = Order::statusCounts();
        $totalOrders = $statusCounts->sum();
        $pendingOrders = $statusCounts->get('ChoXacNhan', 0);
        $shippingOrders = $statusCounts->get('DangGiao', 0);
        $deliveredOrders = $statusCounts->get('DaGiao', 0);

        $pendingCancellationOrders = Order::where(
            'TrangThai', 'DaXacNhan'
        )->where('TrangThaiYeuCauHuy', 'ChoDuyet')->count();

        return view('admin.order.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'shippingOrders',
            'deliveredOrders',
            'pendingCancellationOrders'
        ));
    }

    /**
     * 2. XEM CHI TIẾT ĐƠN HÀNG
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
     * 3. CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
     */
    public function updateStatus(Request $request, $id)
    {
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

                $order = Order::where('MaDonHang', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $currentStatus = $order->TrangThai;
                $newStatus = $validated['status'];

                // Không cho cập nhật khi đang chờ duyệt hủy
                if ($order->TrangThaiYeuCauHuy === 'ChoDuyet') {
                    throw new Exception(
                        'Đơn hàng đang có yêu cầu hủy. Vui lòng xét duyệt yêu cầu trước.'
                    );
                }

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

                // Không thay đổi trạng thái
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

                // VietQR phải được xác nhận thanh toán trước
                if (
                    $newStatus === 'DaXacNhan' &&
                    $order->PhuongThucThanhToan === 'vietqr' &&
                    $order->TrangThaiThanhToan !== 'DaThanhToan'
                ) {
                    throw new Exception(
                        'Đơn VietQR chưa được xác nhận thanh toán.'
                    );
                }

                // Đơn đã thanh toán không được hủy trực tiếp
                if (
                    $newStatus === 'DaHuy' &&
                    $order->TrangThaiThanhToan === 'DaThanhToan'
                ) {
                    throw new Exception(
                        'Đơn hàng đã thanh toán. Cần xử lý hoàn tiền trước khi hủy.'
                    );
                }

                // Đơn đã có yêu cầu hủy bị từ chối:
                // không cho hủy trực tiếp để bỏ qua kết quả xét duyệt
                if (
                    $newStatus === 'DaHuy' &&
                    $currentStatus === 'DaXacNhan' &&
                    $order->TrangThaiYeuCauHuy === 'TuChoi'
                ) {
                    throw new Exception(
                        'Yêu cầu hủy đã bị từ chối. Không thể hủy trực tiếp đơn này.'
                    );
                }

                // Hủy đơn và hoàn tồn kho
                if ($newStatus === 'DaHuy') {
                    $this->restoreStock($order);
                }

                $order->TrangThai = $newStatus;

                // COD: đã giao thì ghi nhận thanh toán
                if (
                    $newStatus === 'DaGiao' &&
                    $order->PhuongThucThanhToan === 'cod' &&
                    $order->TrangThaiThanhToan !== 'DaThanhToan'
                ) {
                    $order->TrangThaiThanhToan = 'DaThanhToan';
                    $order->NgayThanhToan = now();
                }

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
     * 4. GIỮ TƯƠNG THÍCH ROUTE CŨ
     */
    public function confirmstatus(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }

    /**
     * 5. XÁC NHẬN THANH TOÁN VIETQR
     */
    public function confirmPayment($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $order = Order::where('MaDonHang', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($order->PhuongThucThanhToan !== 'vietqr') {
                    throw new Exception(
                        'Đơn hàng không sử dụng VietQR.'
                    );
                }

                if ($order->TrangThai !== 'ChoXacNhan') {
                    throw new Exception(
                        'Đơn hàng không ở trạng thái chờ xác nhận.'
                    );
                }

                if (
                    $order->TrangThaiThanhToan !==
                    'ChoXacNhanThanhToan'
                ) {
                    throw new Exception(
                        'Khách hàng chưa gửi yêu cầu xác nhận chuyển khoản.'
                    );
                }

                // Cập nhật thanh toán
                $order->TrangThaiThanhToan = 'DaThanhToan';
                $order->NgayThanhToan = now();

                // Tự động xác nhận đơn
                $order->TrangThai = 'DaXacNhan';

                $order->save();
            });

            return back()->with(
                'success',
                'Đã xác nhận thanh toán VietQR và đơn hàng thành công!'
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
     * 6. ADMIN XÉT DUYỆT YÊU CẦU HỦY
     *
     * approve: Đồng ý hủy
     * reject: Từ chối hủy
     */
    public function reviewCancellation(Request $request, $id)
    {
        $validated = $request->validate([
            'decision' => [
                'required',
                Rule::in(['approve', 'reject']),
            ],
        ], [
            'decision.required' => 'Vui lòng chọn thao tác.',
            'decision.in' => 'Thao tác không hợp lệ.',
        ]);

        try {
            $message = DB::transaction(function () use ($id, $validated) {

                $order = Order::where('MaDonHang', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Chỉ xử lý yêu cầu đang chờ duyệt
                if (
                    $order->TrangThai !== 'DaXacNhan' ||
                    $order->TrangThaiYeuCauHuy !== 'ChoDuyet'
                ) {
                    throw new Exception(
                        'Đơn hàng không có yêu cầu hủy hợp lệ đang chờ duyệt.'
                    );
                }

                if ($validated['decision'] === 'approve') {

                    // Duyệt hủy cả đơn VietQR đã thanh toán.
                    // Việc hoàn tiền được Admin thực hiện thủ công bên ngoài hệ thống.
                    // Giữ nguyên TrangThaiThanhToan để bảo toàn lịch sử thanh toán.

                    // Hoàn tồn kho
                    $this->restoreStock($order);

                    $order->TrangThai = 'DaHuy';
                    $order->TrangThaiYeuCauHuy = 'DaDuyet';

                    $message = $order->TrangThaiThanhToan === 'DaThanhToan'
                        ? 'Đã chấp nhận hủy đơn và hoàn tồn kho. Vui lòng hoàn tiền thủ công cho khách trong 1–2 ngày làm việc.'
                        : 'Đã chấp nhận yêu cầu hủy. Đơn hàng được hủy và hoàn lại tồn kho.';
                } else {

                    // Giữ nguyên trạng thái Đã xác nhận
                    $order->TrangThaiYeuCauHuy = 'TuChoi';

                    $message = 'Đã từ chối yêu cầu hủy. Đơn hàng tiếp tục được xử lý.';
                }

                // Ghi nhận Admin xử lý
                $order->NgayXuLyYeuCauHuy = now();
                $order->NguoiXuLyYeuCauHuy = Auth::id();

                $order->save();

                return $message;
            });

            return back()->with('success', $message);

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * 7. HOÀN TỒN KHO KHI HỦY ĐƠN
     *
     * Chỉ gọi bên trong DB::transaction()
     * sau khi đã khóa đơn hàng.
     */
    private function restoreStock(Order $order): void
    {
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

            $variant->increment(
                'SoLuong',
                $detail->SoLuong
            );
        }
    }
}

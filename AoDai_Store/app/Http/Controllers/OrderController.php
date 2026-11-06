<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng của khách hàng
     * Lọc theo trạng thái và phân trang 5 đơn/trang.
     */
    
public function index(Request $request)
{
    $statuses = [
        'ChoXacNhan',
        'DaXacNhan',
        'DangGiao',
        'DaGiao',
        'DaHuy',
    ];

    $status = $request->query('status', 'all');

    if ($status !== 'all' && !in_array($status, $statuses, true)) {
        $status = 'all';
    }

    $query = Order::where('MaTaiKhoan', Auth::id())
        ->with([
            'orderDetails.productDetail.product',
            'orderDetails.productDetail.color',
            'orderDetails.productDetail.size',
        ]);

    if ($status !== 'all') {
        $query->where('TrangThai', $status);
    }

    $orders = $query
        ->orderByDesc('NgayDat')
        ->paginate(5)
        ->withQueryString();

    return view('client.order.history', compact(
        'orders',
        'status'
    ));
}

    /**
     * Xem chi tiết đơn hàng.
     */
    public function show($id)
    {
        $order = Order::where('MaDonHang', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->firstOrFail();

        $orderDetails = OrderDetail::where(
            'MaDonHang',
            $order->MaDonHang
        )->get();

        return view('client.order.detail', compact(
            'order',
            'orderDetails'
        ));
    }

    /**
     * Đặt hàng từ những sản phẩm được chọn trong giỏ.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'HoTenNguoiNhan' => 'required|string|max:100',
            'SDTNguoiNhan' => [
                'required',
                'regex:/^(03|05|07|08|09)[0-9]{8}$/'
            ],
            'DiaChiNhan' => 'required|string|max:500',
            'PhuongThucThanhToan' => [
                'required',
                Rule::in(['cod', 'vnpay'])
            ],
            'GhiChu' => 'nullable|string|max:500',
        ], [
            'HoTenNguoiNhan.required' =>
                'Vui lòng nhập họ tên người nhận.',
            'SDTNguoiNhan.required' =>
                'Vui lòng nhập số điện thoại.',
            'SDTNguoiNhan.regex' =>
                'Số điện thoại không hợp lệ.',
            'DiaChiNhan.required' =>
                'Vui lòng nhập địa chỉ nhận hàng.',
            'PhuongThucThanhToan.required' =>
                'Vui lòng chọn phương thức thanh toán.',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {

                $selectedIds = array_values(array_unique(
                    session('selected_cart_ids', [])
                ));

                if (empty($selectedIds)) {
                    throw new Exception(
                        'Vui lòng chọn sản phẩm cần thanh toán.'
                    );
                }

                $cartItems = Cart::where(
                    'MaTaiKhoan',
                    Auth::id()
                )
                    ->whereIn('MaGioHang', $selectedIds)
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->count() !== count($selectedIds)) {
                    throw new Exception(
                        'Một số sản phẩm đã chọn không còn trong giỏ hàng.'
                    );
                }

                $orderItems = [];
                $tienHang = 0;

                // Khóa biến thể theo thứ tự cố định.
                $cartItems = $cartItems->sortBy('MaChiTietSP');

                foreach ($cartItems as $item) {

                    $productDetail = ProductDetail::where(
                        'MaChiTietSP',
                        $item->MaChiTietSP
                    )
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$productDetail ||
                        $productDetail->IsDeleted
                    ) {
                        throw new Exception(
                            'Sản phẩm không còn khả dụng.'
                        );
                    }

                    if (
                        $item->SoLuong <= 0 ||
                        $item->SoLuong > $productDetail->SoLuong
                    ) {
                        throw new Exception(
                            'Sản phẩm không đủ tồn kho. Vui lòng kiểm tra lại giỏ hàng.'
                        );
                    }

                    $donGia = $productDetail->Gia;
                    $thanhTien = $donGia * $item->SoLuong;

                    $tienHang += $thanhTien;

                    $orderItems[] = [
                        'productDetail' => $productDetail,
                        'SoLuong' => $item->SoLuong,
                        'DonGia' => $donGia,
                    ];
                }

                $order = Order::create([
                    'MaTaiKhoan' => Auth::id(),
                    'HoTenNguoiNhan' => $validated['HoTenNguoiNhan'],
                    'SDTNguoiNhan' => $validated['SDTNguoiNhan'],
                    'DiaChiNhan' => $validated['DiaChiNhan'],
                    'TienHang' => $tienHang,
                    'PhiVanChuyen' => 0,
                    'PhuongThucThanhToan' =>
                        $validated['PhuongThucThanhToan'],
                    'TrangThai' => 'ChoXacNhan',
                    'GhiChu' => $validated['GhiChu'] ?? null,
                ]);

                foreach ($orderItems as $item) {

                    OrderDetail::create([
                        'MaDonHang' => $order->MaDonHang,
                        'MaChiTietSP' =>
                            $item['productDetail']->MaChiTietSP,
                        'SoLuong' => $item['SoLuong'],
                        'DonGia' => $item['DonGia'],
                    ]);

                    $item['productDetail']->decrement(
                        'SoLuong',
                        $item['SoLuong']
                    );
                }

                Cart::where('MaTaiKhoan', Auth::id())
                    ->whereIn('MaGioHang', $selectedIds)
                    ->delete();

                return $order;
            });

            session()->forget('selected_cart_ids');

            return redirect()
                ->route('order.history')
                ->with(
                    'success',
                    'Đặt hàng thành công! Mã đơn hàng #' .
                    $order->MaDonHang
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Hủy đơn hàng đang chờ xác nhận.
     * Hoàn lại tồn kho.
     */
    public function cancel($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $order = Order::where('MaDonHang', $id)
                    ->where('MaTaiKhoan', Auth::id())
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    throw new Exception(
                        'Không tìm thấy đơn hàng.'
                    );
                }

                if ($order->TrangThai !== 'ChoXacNhan') {
                    throw new Exception(
                        'Chỉ có thể hủy đơn đang chờ xác nhận.'
                    );
                }

                $orderDetails = OrderDetail::where(
                    'MaDonHang',
                    $order->MaDonHang
                )
                    ->orderBy('MaChiTietSP')
                    ->get();

                if ($orderDetails->isEmpty()) {
                    throw new Exception(
                        'Không tìm thấy chi tiết đơn hàng.'
                    );
                }

                foreach ($orderDetails as $detail) {

                    $productDetail = ProductDetail::where(
                        'MaChiTietSP',
                        $detail->MaChiTietSP
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$productDetail) {
                        throw new Exception(
                            'Không tìm thấy biến thể để hoàn tồn kho.'
                        );
                    }

                    $productDetail->increment(
                        'SoLuong',
                        $detail->SoLuong
                    );
                }

                $order->update([
                    'TrangThai' => 'DaHuy'
                ]);
            });

            return back()->with(
                'success',
                'Hủy đơn hàng thành công. Sản phẩm đã được hoàn lại kho.'
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
     * Khách hàng xác nhận đã nhận hàng.
     */
    public function submit($id)
    {
        $updated = Order::where('MaDonHang', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->where('TrangThai', 'DangGiao')
            ->update([
                'TrangThai' => 'DaGiao'
            ]);

        if (!$updated) {
            return back()->with(
                'error',
                'Không thể xác nhận đơn hàng này.'
            );
        }

        return back()->with(
            'success',
            'Xác nhận đã nhận hàng thành công.'
        );
    }
    
/**
 * Mua lại đơn hàng đã giao hoặc đã hủy.
 */
public function reorder($id)
{
    $order = Order::where('MaDonHang', $id)
        ->where('MaTaiKhoan', Auth::id())
        ->with('orderDetails')
        ->firstOrFail();

    // Chỉ cho phép mua lại đơn đã giao hoặc đã hủy
    if (!in_array($order->TrangThai, ['DaGiao', 'DaHuy'], true)) {
        return back()->with(
            'error',
            'Đơn hàng này không hỗ trợ mua lại.'
        );
    }

    if ($order->orderDetails->isEmpty()) {
        return back()->with(
            'error',
            'Đơn hàng không có sản phẩm để mua lại.'
        );
    }

    try {
        $cartIds = DB::transaction(function () use ($order) {

            $selectedIds = [];

            // Gộp các dòng có cùng biến thể
            $items = $order->orderDetails
                ->groupBy('MaChiTietSP');

            foreach ($items as $variantId => $details) {

                $quantity = (int) $details->sum('SoLuong');

                // Kiểm tra biến thể và tồn kho hiện tại
                $variant = ProductDetail::where(
                    'MaChiTietSP',
                    $variantId
                )
                    ->lockForUpdate()
                    ->first();

                if (
                    !$variant ||
                    $variant->IsDeleted ||
                    (int) $variant->SoLuong < $quantity
                ) {
                    throw new \RuntimeException(
                        'Có sản phẩm đã hết hàng hoặc không còn kinh doanh.'
                    );
                }

                // Kiểm tra sản phẩm đã có trong giỏ chưa
                $cart = Cart::where('MaTaiKhoan', Auth::id())
                    ->where('MaChiTietSP', $variantId)
                    ->lockForUpdate()
                    ->first();

                if ($cart) {

                    $newQuantity = (int) $cart->SoLuong + $quantity;

                    if ($newQuantity > (int) $variant->SoLuong) {
                        throw new \RuntimeException(
                            'Số lượng mua lại vượt quá tồn kho hiện tại.'
                        );
                    }

                    $cart->SoLuong = $newQuantity;
                    $cart->save();

                } else {

                    $cart = Cart::create([
                        'MaTaiKhoan' => Auth::id(),
                        'MaChiTietSP' => $variantId,
                        'SoLuong' => $quantity,
                        'NgayThem' => now(),
                    ]);
                }

                $selectedIds[] = $cart->MaGioHang;
            }

            return $selectedIds;
        });

        // Lưu các sản phẩm được chọn để thanh toán
        session([
            'selected_cart_ids' => $cartIds,
        ]);

        // Chuyển sang trang xác nhận đơn hàng
        return redirect('/checkout')->with(
            'success',
            'Đã thêm sản phẩm mua lại. Vui lòng kiểm tra đơn hàng trước khi thanh toán.'
        );

    } catch (\RuntimeException $e) {

        return back()->with('error', $e->getMessage());

    } catch (\Throwable $e) {

        report($e);

        return back()->with(
            'error',
            'Có lỗi xảy ra khi mua lại đơn hàng. Vui lòng thử lại.'
        );
    }
}

}

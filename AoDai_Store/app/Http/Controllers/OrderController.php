<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    //
    public function store(Request $request)
{
    $request->validate([
        'HoTenNguoiNhan' => 'required|string|max:100',
        'SDTNguoiNhan' => 'required|string|max:15',
        'DiaChiNhan' => 'required|string|max:500',
        'PhuongThucThanhToan' => 'required|in:cod,vnpay',
        'GhiChu' => 'nullable|string|max:500',
    ], [
        'HoTenNguoiNhan.required' => 'Vui lòng nhập họ tên người nhận.',
        'SDTNguoiNhan.required' => 'Vui lòng nhập số điện thoại.',
        'DiaChiNhan.required' => 'Vui lòng nhập địa chỉ nhận hàng.',
        'PhuongThucThanhToan.required' => 'Vui lòng chọn phương thức thanh toán.',
    ]);

    try {
        DB::beginTransaction();

        // Lấy giỏ hàng của tài khoản đang đăng nhập
        $selectedIds = session('selected_cart_ids', []);

        if (empty($selectedIds)) {
            throw new \Exception('Không tìm thấy danh sách sản phẩm được chọn.');
        }

        $cartItems = Cart::where('MaTaiKhoan', Auth::id())
            ->whereIn('MaGioHang', $selectedIds)
            ->with('productDetail')
            ->get();

        if ($cartItems->isEmpty() || $cartItems->count() !== count($selectedIds)) {
            throw new Exception('Một số sản phẩm đã chọn không còn trong giỏ hàng.');
        }

        if ($cartItems->isEmpty()) {
            throw new Exception('Giỏ hàng của bạn đang trống.');
        }

        $orderItems = [];
        $tienHang = 0;

        // Kiểm tra tồn kho và lấy giá từ database
        foreach ($cartItems as $item) {
            $productDetail = ProductDetail::where(
                'MaChiTietSP',
                $item->MaChiTietSP
            )
                ->lockForUpdate()
                ->first();

            if (!$productDetail || $productDetail->IsDeleted) {
                throw new Exception('Một sản phẩm trong giỏ hàng không còn khả dụng.');
            }

            if ($item->SoLuong <= 0 || $item->SoLuong > $productDetail->SoLuong) {
                throw new Exception(
                    'Sản phẩm không đủ số lượng tồn kho. Vui lòng kiểm tra lại giỏ hàng.'
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

        // Phí vận chuyển
        $phiVanChuyen = 0;

        // Tạo đơn hàng
        $order = Order::create([
            'MaTaiKhoan' => Auth::id(),
            'HoTenNguoiNhan' => $request->HoTenNguoiNhan,
            'SDTNguoiNhan' => $request->SDTNguoiNhan,
            'DiaChiNhan' => $request->DiaChiNhan,
            'TienHang' => $tienHang,
            'PhiVanChuyen' => $phiVanChuyen,
            'PhuongThucThanhToan' => $request->PhuongThucThanhToan,
            'TrangThai' => 'ChoXacNhan',
            'GhiChu' => $request->GhiChu,
        ]);

        // Tạo chi tiết đơn hàng và trừ tồn kho
        foreach ($orderItems as $orderItem) {
            OrderDetail::create([
                'MaDonHang' => $order->MaDonHang,
                'MaChiTietSP' => $orderItem['productDetail']->MaChiTietSP,
                'SoLuong' => $orderItem['SoLuong'],
                'DonGia' => $orderItem['DonGia'],
            ]);

            $orderItem['productDetail']->decrement(
                'SoLuong',
                $orderItem['SoLuong']
            );
        }

        // Xóa giỏ hàng sau khi đặt thành công
        Cart::where('MaTaiKhoan', Auth::id())
        ->whereIn('MaGioHang', $selectedIds)
        ->delete();

    session()->forget('selected_cart_ids');

        DB::commit();

        return redirect()
            ->route('home')
            ->with('order_success', 'Đặt hàng thành công!');

    } catch (\Throwable $e) {
        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
        public function cancel($id)
    {
        try {
            DB::transaction(function () use ($id) {

                // Khóa đơn hàng để tránh hủy nhiều lần cùng lúc
                $order = Order::where('MaDonHang', $id)
                    ->where('MaTaiKhoan', Auth::id())
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    throw new Exception('Không tìm thấy đơn hàng.');
                }

                // Chỉ được hủy đơn đang chờ xác nhận
                if ($order->TrangThai !== 'ChoXacNhan') {
                    throw new Exception(
                        'Chỉ có thể hủy đơn hàng đang chờ xác nhận.'
                    );
                }

                // Lấy các sản phẩm trong đơn hàng
                $orderDetails = OrderDetail::where(
                    'MaDonHang',
                    $order->MaDonHang
                )->get();

                if ($orderDetails->isEmpty()) {
                    throw new Exception(
                        'Không tìm thấy chi tiết đơn hàng để hoàn tồn kho.'
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
                            'Không tìm thấy biến thể sản phẩm để hoàn tồn kho.'
                        );
                    }

                    $productDetail->increment(
                        'SoLuong',
                        $detail->SoLuong
                    );
                }

                // Cập nhật trạng thái đơn hàng
                $order->update([
                    'TrangThai' => 'DaHuy'
                ]);
            });

            return back()->with(
                'success',
                'Đã hủy đơn hàng thành công. Số lượng sản phẩm đã được hoàn lại kho.'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }    
       public function submit($id)
{
    $order = Order::where('MaDonHang', $id)
        ->where('MaTaiKhoan', Auth::id())
        ->where('TrangThai', 'DangGiao')
        ->first();

    if (!$order) {
        return back()->with(
            'error',
            'Không thể xác nhận đơn hàng này.'
        );
    }

    $order->update([
        'TrangThai' => 'DaGiao'
    ]);

    return back()->with(
        'success',
        'Đã xác nhận nhận hàng thành công.'
    );
}

public function purchasedProducts()
{
    $userId = Auth::id();

    $products = DB::table('chitietdonhang')
        ->join(
            'donhang',
            'donhang.MaDonHang',
            '=',
            'chitietdonhang.MaDonHang'
        )
        ->join(
            'chitietsanpham',
            'chitietsanpham.MaChiTietSP',
            '=',
            'chitietdonhang.MaChiTietSP'
        )
        ->join(
            'sanpham',
            'sanpham.MaSanPham',
            '=',
            'chitietsanpham.MaSanPham'
        )
        ->leftJoin('danhgia', function ($join) use ($userId) {
            $join->on(
                'danhgia.MaSanPham',
                '=',
                'sanpham.MaSanPham'
            )->where(
                'danhgia.MaTaiKhoan',
                '=',
                $userId
            );
        })
        ->where('donhang.MaTaiKhoan', $userId)
        ->where('donhang.TrangThai', 'DaGiao')
        ->select(
            'sanpham.MaSanPham',
            'sanpham.TenSanPham',
            'sanpham.HinhAnh',
            'chitietdonhang.DonGia as GiaMua',
            'danhgia.SoSao',
            'danhgia.NoiDung'
        )
        ->distinct()
        ->paginate(8);

    return view(
        'client.products.purchased',
        compact('products')
    );
}

}

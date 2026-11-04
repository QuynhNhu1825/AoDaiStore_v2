<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    //
    public function store(Request $request)
    {
        // Xử lý đặt hàng ở đây
        $address =$request->input('so_nha').', '.
                  $request->input('phuong_xa').', '.
                  $request->input('quan_huyen').', '.
                  $request->input('tinh_thanh');
        $request->validate([
            'TenNguoiNhan' => 'required|string|max:255',
            'SDTNguoiNhan' => 'required|string|max:15',
            'PhuongThucThanhToan' => 'required|string|max:100',
            'GhiChu' => 'nullable|string|max:1000',
            
            'TienHang' => 'required|numeric',
            'GiamGia' => 'nullable|numeric',
            'MaKhuyenMai' => 'nullable|integer',
        ]);
       
       try {
    DB::beginTransaction();

    // 1. Tạo đơn hàng
    $order = Order::create([
        'TenNguoiNhan' => $request->input('TenNguoiNhan'),
        'SDTNguoiNhan' => $request->input('SDTNguoiNhan'),
        'DiaChiGiaoHang' => $address,
        'PhuongThucThanhToan' => $request->input('PhuongThucThanhToan'),
        'GhiChu' => $request->input('GhiChu'),
        'MaTaiKhoan' => Auth::id(),
        'TienHang' => $request->input('TienHang') ,
        'TongTien' => $request->input('TongTien'),
        'PhiVanChuyen' => 10000,
        'GiamGia' => $request->input('GiamGia'),
        'MaKhuyenMai' => $request->input('MaKhuyenMai'),
        'NgayThanhToan' => null,
    ]);

    // Lấy ID vừa tạo trực tiếp từ object $order (An toàn hơn dùng latest()->first())
    $orderId = $order->MaHoaDon; 

    // 2. Lấy các mảng dữ liệu từ Form
    $maSanPhams = $request->input('MaSanPham', []); 
    $soLuongs   = $request->input('SoLuong', []);   
    $donGias    = $request->input('DonGia', []);    
    $thanhTiens = $request->input('ThanhTien', []); 
    $maSizes    = $request->input('MaSize', []);

    // 3. Duyệt vòng lặp lưu chi tiết hóa đơn
    if (is_array($maSanPhams) && count($maSanPhams) > 0) {
        foreach ($maSanPhams as $key => $maSP) {
            DB::table('chitiethoadon')->insert([
                'MaHoaDon'  => $orderId,
                'MaSanPham' => (int)$maSP,
                'SoLuong'   => (int)$soLuongs[$key],
                'DonGia'    => (float)$donGias[$key],
                'MaSize'    => $maSizes[$key],
                'ThanhTien' => (float)$thanhTiens[$key],
                
            ]);
        }
    } else {
        // Nếu không có sản phẩm nào, có thể chủ động ném lỗi để rollback
        throw new Exception("Đơn hàng phải có ít nhất một sản phẩm.");
    }

    // Nếu mọi thứ ổn, xác nhận lưu vào Database
    DB::commit();

    if ($request->input('PhuongThucThanhToan') == 'vnpay') {
        return app(VNPAYController::class)->createPayment($order);
    }
    $cartController = new CartController();
    $cartController->clearCart();
    return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
   

} catch (Exception $e) {
    // Nếu có bất kỳ lỗi nào xảy ra, hoàn tác lại toàn bộ dữ liệu đã ghi ở trên
    DB::rollBack();

 
}
    
    }
     public function cancel($id)
    {
        $order = DB::table('hoadon')
            ->where('MaHoaDon', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->where('TrangThai', 'ChoXacNhan')
            ->first();

        if (!$order) {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        DB::table('hoadon')
            ->where('MaHoaDon', $id)
            ->update([
                'TrangThai' => 'DaHuy'
            ]);

        return back()->with('success', 'Đã hủy đơn hàng thành công.');
    }
        public function submit($id)
        {
            $order =Order::where('MaHoaDon', $id)
                ->where('MaTaiKhoan', Auth::id())
                ->where('TrangThai', 'DangGiao')
                ->first();
    
            if (!$order) {
                return back()->with('error', 'Không thể xác nhận đơn hàng này.');
            }
    
            Order::where('MaHoaDon', $id)
                ->where('MaHoaDon', $id)
                ->update([
                    'TrangThai' => 'DaGiao'
                ]);
    
            return back()->with('success', 'Đã xác nhận đơn hàng thành công.');
        }

public function purchasedProducts()
{
    
  $userId = Auth::id();

    $products = DB::table('chitiethoadon')
        ->join('hoadon', 'hoadon.MaHoaDon', '=', 'chitiethoadon.MaHoaDon')
        ->join('sanpham', 'sanpham.MaSanPham', '=', 'chitiethoadon.MaSanPham')
        ->leftJoin('danhgia', function ($join) use ($userId) {
            $join->on('danhgia.MaSanPham', '=', 'sanpham.MaSanPham')
                 ->where('danhgia.MaTaiKhoan', '=', $userId);
        })
        ->where('hoadon.MaTaiKhoan', $userId)
        ->where('hoadon.TrangThai', 'HoanThanh')
        ->select(
            'sanpham.MaSanPham',
            'sanpham.TenSanPham',
            'sanpham.HinhAnh',
            'sanpham.GiaBan',
            'danhgia.SoSao',
            'danhgia.NoiDung'
        )
        ->paginate(8);

    return view('client.products.purchased', compact('products'));

}

}

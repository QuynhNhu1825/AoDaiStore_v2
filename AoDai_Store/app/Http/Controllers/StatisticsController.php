<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class StatisticsController extends Controller
{
        public function index(Request $request)
    {
        // Lấy ngày lọc từ request
        $tuNgay  = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        // Query gốc: chỉ lấy đơn đã giao
        $baseQuery = DB::table('hoadon')
            ->where('TrangThai', 'DaGiao');

        // Áp dụng lọc theo khoảng ngày nếu có
        if ($tuNgay && $denNgay) {
            $baseQuery->whereBetween('NgayTao', [
                $tuNgay . ' 00:00:00',
                $denNgay . ' 23:59:59'
            ]);
        }

        // Tổng doanh thu
        $tongDoanhThu = (clone $baseQuery)->sum('TongTien');

        // Số lượt mua (số đơn)
        $soLuotMua = (clone $baseQuery)->count();
        // lượt mua theo tháng
        // LƯỢT MUA THEO SẢN PHẨM THEO THÁNG
        $luotMuaTheoThang = DB::table('chitiethoadon')
            ->join('hoadon', 'hoadon.MaHoaDon', '=', 'chitiethoadon.MaHoaDon')
            ->join('sanpham', 'sanpham.MaSanPham', '=', 'chitiethoadon.MaSanPham')
            ->where('hoadon.TrangThai', 'DaGiao')
            ->whereYear('hoadon.NgayTao', date('Y'))
            ->selectRaw('
                MONTH(hoadon.NgayTao) as thang,
                sanpham.TenSanPham,
                SUM(chitiethoadon.SoLuong) as so_luot_mua
            ')
            ->groupBy('thang', 'sanpham.TenSanPham')
            ->orderBy('thang')
            ->orderByDesc('so_luot_mua')
            ->get();


        // Doanh thu theo tháng (năm hiện tại)
        $doanhThuTheoThang = DB::table('hoadon')
            ->selectRaw('MONTH(NgayTao) as thang, SUM(TongTien) as tong_tien')
            ->where('TrangThai', 'DaGiao')
            ->whereYear('NgayTao', date('Y'))
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();
        // LƯỢT MUA THEO SẢN PHẨM
        return view('admin.statistics.index', compact(
  'tongDoanhThu',
 'soLuotMua',
            'doanhThuTheoThang',
            'luotMuaTheoThang',
            'tuNgay',
            'denNgay'
            ));
    }
}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tu_ngay' => ['nullable', 'date'],
            'den_ngay' => [
                'nullable',
                'date',
                'after_or_equal:tu_ngay'
            ],
        ]);

        // 1. Khoảng thời gian thống kê
        $tuNgay = $request->input(
            'tu_ngay',
            now()->startOfMonth()->toDateString()
        );

        $denNgay = $request->input(
            'den_ngay',
            now()->toDateString()
        );

        $from = Carbon::parse($tuNgay)->startOfDay();
        $to = Carbon::parse($denNgay)->endOfDay();

        // 2. Truy vấn đơn hàng
        $orders = DB::table('donhang')
            ->whereBetween('NgayDat', [$from, $to]);

        // 3. Tổng số đơn hàng
        $tongDonHang = (clone $orders)->count();

        // 4. Đơn hàng đã giao
        $donDaGiao = (clone $orders)
            ->where('TrangThai', 'DaGiao')
            ->count();

        // 5. Chi tiết các đơn đã giao
        $soldDetails = DB::table('chitietdonhang as ct')
            ->join(
                'donhang as dh',
                'dh.MaDonHang',
                '=',
                'ct.MaDonHang'
            )
            ->whereBetween('dh.NgayDat', [$from, $to])
            ->where('dh.TrangThai', 'DaGiao');

        // 6. Tổng doanh thu
        $tongDoanhThu = (clone $soldDetails)
            ->sum('ct.ThanhTien');

        // 7. Tổng số lượng sản phẩm đã bán
        $soSanPhamDaBan = (clone $soldDetails)
            ->sum('ct.SoLuong');

        // 8. Đơn hàng theo trạng thái
        $statusLabels = [
            'ChoXacNhan' => 'Chờ xác nhận',
            'DaXacNhan' => 'Đã xác nhận',
            'DangGiao' => 'Đang giao',
            'DaGiao' => 'Đã giao',
            'DaHuy' => 'Đã hủy',
        ];

        $statusCounts = (clone $orders)
            ->select('TrangThai')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('TrangThai')
            ->pluck('total', 'TrangThai');

        $donTheoTrangThai = collect($statusLabels)
            ->map(function ($label, $code) use ($statusCounts) {
                return [
                    'label' => $label,
                    'count' => (int) ($statusCounts[$code] ?? 0),
                ];
            })
            ->values()
            ->all();

        // 9. Doanh thu theo ngày
        $doanhThuTheoNgay = (clone $soldDetails)
            ->selectRaw(
                'DATE(dh.NgayDat) as ngay,
                 SUM(ct.ThanhTien) as tong_tien'
            )
            ->groupByRaw('DATE(dh.NgayDat)')
            ->orderBy('ngay')
            ->get();

        // 10. Top 5 sản phẩm bán chạy
        // Lấy tên sản phẩm thông qua quan hệ biến thể
        $sanPhamBanChay = (clone $soldDetails)
            ->join(
                'chitietsanpham as ctsp',
                'ctsp.MaChiTietSP',
                '=',
                'ct.MaChiTietSP'
            )
            ->join(
                'sanpham as sp',
                'sp.MaSanPham',
                '=',
                'ctsp.MaSanPham'
            )
            ->select(
                'sp.MaSanPham',
                'sp.TenSanPham'
            )
            ->selectRaw(
                'SUM(ct.SoLuong) as so_luong_ban'
            )
            ->groupBy(
                'sp.MaSanPham',
                'sp.TenSanPham'
            )
            ->orderByDesc('so_luong_ban')
            ->limit(5)
            ->get();

        return view('admin.statistics.index', compact(
            'tuNgay',
            'denNgay',
            'tongDoanhThu',
            'tongDonHang',
            'donDaGiao',
            'soSanPhamDaBan',
            'donTheoTrangThai',
            'doanhThuTheoNgay',
            'sanPhamBanChay'
        ));
    }
}

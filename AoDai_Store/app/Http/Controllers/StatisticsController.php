<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $endDateRules = ['nullable', 'date_format:Y-m-d'];
        if ($request->filled('tu_ngay')) {
            $endDateRules[] = 'after_or_equal:tu_ngay';
        }

        $dates = $request->validate([
            'tu_ngay' => ['nullable', 'date_format:Y-m-d'],
            'den_ngay' => $endDateRules,
        ], [
            'tu_ngay.date_format' => 'Ngày bắt đầu không hợp lệ.',
            'den_ngay.date_format' => 'Ngày kết thúc không hợp lệ.',
            'den_ngay.after_or_equal' => 'Vui lòng chọn Đến ngày bằng hoặc sau Từ ngày.',
        ]);

        $tuNgay = $dates['tu_ngay'] ?? null;
        $denNgay = $dates['den_ngay'] ?? null;
        $orders = Order::query();

        if ($tuNgay !== null) {
            $orders->where('donhang.NgayDat', '>=', Carbon::parse($tuNgay)->startOfDay());
        }

        if ($denNgay !== null) {
            $orders->where('donhang.NgayDat', '<', Carbon::parse($denNgay)->addDay()->startOfDay());
        }

        $statusCounts = (clone $orders)->statusCounts();
        $tongDonHang = $statusCounts->sum();
        $donDaGiao = $statusCounts->get('DaGiao', 0);
        $donTheoTrangThai = collect(Order::STATUS_LABELS)
            ->map(fn ($label, $status) => [
                'label' => $label,
                'count' => $statusCounts->get($status, 0),
            ])
            ->values()
            ->all();

        $otherCount = $statusCounts->except(array_keys(Order::STATUS_LABELS))->sum();
        if ($otherCount > 0) {
            $donTheoTrangThai[] = ['label' => 'Trạng thái khác', 'count' => $otherCount];
        }

        $deliveredOrders = (clone $orders)->where('donhang.TrangThai', 'DaGiao');
        $doanhThuTheoNgay = (clone $deliveredOrders)->toBase()
            ->selectRaw('DATE(NgayDat) as ngay, SUM(COALESCE(TienHang, 0) + COALESCE(PhiVanChuyen, 0)) as tong_tien')
            ->groupByRaw('DATE(NgayDat)')
            ->orderBy('ngay')
            ->get();
        $tongDoanhThu = $doanhThuTheoNgay->sum('tong_tien');

        $soldDetails = $deliveredOrders->toBase()->join(
            'chitietdonhang as ct', 'ct.MaDonHang', '=', 'donhang.MaDonHang'
        );
        $soSanPhamDaBan = (clone $soldDetails)->sum('ct.SoLuong');

        $sanPhamBanChay = $soldDetails
            ->join('chitietsanpham as ctsp', 'ctsp.MaChiTietSP', '=', 'ct.MaChiTietSP')
            ->join('sanpham as sp', 'sp.MaSanPham', '=', 'ctsp.MaSanPham')
            ->select('sp.MaSanPham', 'sp.TenSanPham')
            ->selectRaw('SUM(ct.SoLuong) as so_luong_ban')
            ->groupBy('sp.MaSanPham', 'sp.TenSanPham')
            ->orderByDesc('so_luong_ban')
            ->orderBy('sp.MaSanPham')
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

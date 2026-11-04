<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminRatingController extends Controller
{
    public function index()
    {
        $comments = DB::table('danhgia')
            ->join('taikhoan', 'danhgia.MaTaiKhoan', '=', 'taikhoan.MaTaiKhoan')
            ->join('sanpham', 'danhgia.MaSanPham', '=', 'sanpham.MaSanPham')
            ->select(
                'danhgia.MaDanhGia',
                'danhgia.NoiDung',
                'danhgia.SoSao',
                'danhgia.NgayDanhGia',
                'danhgia.TrangThai',
                'taikhoan.TenDangNhap',
                'sanpham.TenSanPham'
            )
            ->orderByDesc('NgayDanhGia')
            ->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }

    public function hide($id)
    {
        DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->update(['TrangThai' => 0]);

        return back()->with('success', 'Đã ẩn bình luận.');
    }
      public function show($id)
    {
        DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->update(['TrangThai' => 1]);

        return back()->with('success', 'Đã hiển thị lại đánh giá');
    }
}

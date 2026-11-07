<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRatingController extends Controller
{
    // Danh sách đánh giá
    // Danh sách đánh giá + tìm kiếm + lọc
public function index(Request $request)
{
    $query = DB::table('danhgia')
        ->join('taikhoan', 'danhgia.MaTaiKhoan', '=', 'taikhoan.MaTaiKhoan')
        ->join('sanpham', 'danhgia.MaSanPham', '=', 'sanpham.MaSanPham')
        ->select(
            'danhgia.MaDanhGia',
            'danhgia.NoiDung',
            'danhgia.HinhAnh',
            'danhgia.SoSao',
            'danhgia.NgayDanhGia',
            'danhgia.TrangThai',
            'danhgia.PhanHoi',
            'taikhoan.TenDangNhap',
            'sanpham.TenSanPham'
        );

    // TÌM KIẾM
    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('taikhoan.TenDangNhap', 'like', "%{$search}%")
              ->orWhere('sanpham.TenSanPham', 'like', "%{$search}%")
              ->orWhere('danhgia.NoiDung', 'like', "%{$search}%");

        });
    }

    // LỌC SỐ SAO
    if ($request->filled('stars')) {
        $query->where('danhgia.SoSao', $request->stars);
    }

    // LỌC TRẠNG THÁI
    if ($request->filled('status')) {
        $query->where('danhgia.TrangThai', $request->status);
    }

    // SẮP XẾP VÀ PHÂN TRANG
    $comments = $query
        ->orderByDesc('danhgia.NgayDanhGia')
        ->paginate(10)
        ->withQueryString();

    return view('admin.comments.index', compact('comments'));
}

    // Ẩn đánh giá
    public function hide($id)
    {
        $rating = DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->first();

        if (!$rating) {
            return back()->with('error', 'Không tìm thấy đánh giá.');
        }

        DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->update([
                'TrangThai' => 0
            ]);

        return back()->with('success', 'Đã ẩn đánh giá.');
    }

    // Hiển thị lại đánh giá
    public function show($id)
    {
        $rating = DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->first();

        if (!$rating) {
            return back()->with('error', 'Không tìm thấy đánh giá.');
        }

        DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->update([
                'TrangThai' => 1
            ]);

        return back()->with('success', 'Đã hiển thị lại đánh giá.');
    }

    // Thêm hoặc chỉnh sửa phản hồi của Admin
    public function reply(Request $request, $id)
    {
        $request->validate([
            'PhanHoi' => 'required|string|max:1000'
        ], [
            'PhanHoi.required' => 'Vui lòng nhập nội dung phản hồi.',
            'PhanHoi.string' => 'Nội dung phản hồi không hợp lệ.',
            'PhanHoi.max' => 'Phản hồi không được vượt quá 1000 ký tự.'
        ]);

        $rating = DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->first();

        if (!$rating) {
            return back()->with('error', 'Không tìm thấy đánh giá cần phản hồi.');
        }

        DB::table('danhgia')
            ->where('MaDanhGia', $id)
            ->update([
                'PhanHoi' => trim($request->PhanHoi)
            ]);

        return back()->with('success', 'Đã lưu phản hồi của Admin thành công.');
    }
}

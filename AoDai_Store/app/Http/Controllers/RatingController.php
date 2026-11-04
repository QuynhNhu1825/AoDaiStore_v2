<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, $MaSanPham)
    {
        $request->validate([
            'SoSao' => 'required|integer|min:1|max:5',
            'NoiDung' => 'nullable|string',
            'HinhAnh' => 'nullable|image|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('HinhAnh')) {
            $fileName = time() . '_' . $request->HinhAnh->getClientOriginalName();
            $request->HinhAnh->move(public_path('img/ratings'), $fileName);
        }

        DB::table('danhgia')->insert([
            'SoSao'       => $request->SoSao,
            'NoiDung'     => $request->NoiDung,
            'HinhAnh'     => $fileName,
            'MaSanPham'   => $MaSanPham,
            'MaTaiKhoan'  => Auth::id(),
            'NgayDanhGia' => now(),
            'TrangThai'   => 1
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
     public function toggle($id) // ✅ nhận từ URL
    {
        $productId = $id;
        $userId = Auth::id();

        $exists = DB::table('yeuthich')
            ->where('MaSanPham', $productId)
            ->where('MaTaiKhoan', $userId)
            ->exists();

        if ($exists) {
            DB::table('yeuthich')
                ->where('MaSanPham', $productId)
                ->where('MaTaiKhoan', $userId)
                ->delete();

            $count = DB::table('yeuthich')
                ->where('MaSanPham', $productId)
                ->count();

            return response()->json([
                'liked' => false,
                'count' => $count
            ]);
        } else {
            DB::table('yeuthich')->insert([
                'MaSanPham' => $productId,
                'MaTaiKhoan' => $userId
            ]);

            $count = DB::table('yeuthich')
                ->where('MaSanPham', $productId)
                ->count();

            return response()->json([
                'liked' => true,
                'count' => $count
            ]);
        }
    }

    public function index()
{
    $userId = auth()->id();

    $favoriteProducts = \DB::table('yeuthich')
        ->join('sanpham', 'yeuthich.MaSanPham', '=', 'sanpham.MaSanPham')
        ->where('yeuthich.MaTaiKhoan', $userId)
        ->select(
            'sanpham.MaSanPham',
            'sanpham.TenSanPham',
            'sanpham.GiaBan',
            'sanpham.HinhAnh'
        )
         ->paginate(8);

    return view('client.favorite.index', compact('favoriteProducts'));
}

}

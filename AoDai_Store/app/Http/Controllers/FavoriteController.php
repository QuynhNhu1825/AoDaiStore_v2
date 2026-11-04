<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Thêm hoặc bỏ yêu thích
    public function toggle($id)
{
    $maTaiKhoan = Auth::id();

    $product = Product::findOrFail($id);

    $favorite = Favorite::where('MaTaiKhoan', $maTaiKhoan)
        ->where('MaSanPham', $product->MaSanPham)
        ->first();

    if ($favorite) {
        $favorite->delete();
        $isFavorite = false;
        $message = 'Đã bỏ yêu thích sản phẩm.';
    } else {
        Favorite::firstOrCreate([
            'MaTaiKhoan' => $maTaiKhoan,
            'MaSanPham' => $product->MaSanPham,
        ]);

        $isFavorite = true;
        $message = 'Đã thêm vào danh sách yêu thích.';
    }

    $favoriteCount = Favorite::where(
        'MaSanPham',
        $product->MaSanPham
    )->count();

    return response()->json([
        'success' => true,
        'isFavorite' => $isFavorite,
        'favoriteCount' => $favoriteCount,
        'message' => $message
    ]);
}

    // Xem danh sách yêu thích
    public function index()
    {
        $favoriteProducts = Favorite::where(
            'MaTaiKhoan',
            Auth::id()
        )
            ->with('product.chiTietSanPham')
            ->latest('NgayThem')
            ->paginate(12);

        return view(
            'client.favorite.index',
            compact('favoriteProducts')
        );
    }

    // Xóa sản phẩm khỏi danh sách yêu thích
    public function destroy($id)
    {
        $favorite = Favorite::where('MaYeuThich', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->firstOrFail();

        $favorite->delete();

        return back()->with(
            'favorite_success',
            'Đã xóa sản phẩm khỏi danh sách yêu thích.'
        );
    }
}
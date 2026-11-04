<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Review;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Trang chủ: tìm kiếm, lọc và sắp xếp sản phẩm.
     */
    public function index(Request $request)
    {
        $query = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0);

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $query->where(
                'TenSanPham',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Lọc theo danh mục qua bảng trung gian sanpham_danhmuc
        if ($request->filled('category')) {
            $maDanhMuc = $request->category;

            $query->whereHas('danhMucs', function ($q) use ($maDanhMuc) {
                $q->where('danhmucsp.MaDanhMuc', $maDanhMuc)
                    ->where('danhmucsp.IsDeleted', 0);
            });
        }

        // Lọc theo màu sắc qua bảng chitietsanpham
        if ($request->filled('color')) {
            $maMau = $request->color;

            $query->whereHas('chiTietSanPham', function ($q) use ($maMau) {
                $q->where('MaMau', $maMau)
                    ->where('IsDeleted', 0);
            });
        }

        // Chỉ lấy sản phẩm có ít nhất một chi tiết còn hoạt động
        $query->whereHas('chiTietSanPham', function ($q) {
            $q->where('IsDeleted', 0);
        });

        // Sắp xếp theo giá của chi tiết sản phẩm
        if ($request->sort === 'price_asc') {
            $query->withMin(
                'chiTietSanPham as gia_thap_nhat',
                'Gia'
            )->orderBy('gia_thap_nhat', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->withMax(
                'chiTietSanPham as gia_cao_nhat',
                'Gia'
            )->orderBy('gia_cao_nhat', 'desc');
        } else {
            $query->orderBy('NgayTao', 'desc');
        }

        $data['product'] = $query->paginate(8)->withQueryString();

        // Danh sách danh mục
        $data['categories'] = Category::where('IsDeleted', 0)->get();

        // Danh sách màu sắc
        $data['colors'] = Color::where('IsDeleted', 0)->get();

        // Sản phẩm mới nhất
        $data['newProducts'] = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0)
        ->whereHas('chiTietSanPham', function ($q) {
            $q->where('IsDeleted', 0);
        })
        ->orderBy('NgayTao', 'desc')
        ->take(8)
        ->get();

        // Tạm hiển thị sản phẩm mới nhất tại mục bán chạy.
        // Có thể thay bằng truy vấn thống kê số lượng đã bán.
        $data['bestSellers'] = $data['newProducts'];

        return view('client.home', compact('data'));
    }

    /**
     * Chi tiết sản phẩm.
     */
    public function detail($id)
    {
        $product = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('MaSanPham', $id)
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0)
        ->firstOrFail();

        // Chỉ hiển thị các chi tiết sản phẩm chưa bị xóa
        $product->setRelation(
            'chiTietSanPham',
            $product->chiTietSanPham
                ->where('IsDeleted', 0)
                ->values()
        );

        // Danh sách size chưa bị xóa
        $allSizes = Size::where('IsDeleted', 0)->get();

        // Điểm đánh giá trung bình
        $avgRating = Review::where(
            'MaSanPham',
            $product->MaSanPham
        )
        ->where('TrangThai', 1)
        ->avg('SoSao');
        // Tổng số lượng sản phẩm đã giao thành công
    $luotBan = DB::table('chitietdonhang')
    ->join(
        'donhang',
        'chitietdonhang.MaDonHang',
        '=',
        'donhang.MaDonHang'
    )
    ->join(
        'chitietsanpham',
        'chitietdonhang.MaChiTietSP',
        '=',
        'chitietsanpham.MaChiTietSP'
    )
    ->where('chitietsanpham.MaSanPham', $product->MaSanPham)
    ->where('donhang.TrangThai', 'DaGiao')
    ->sum('chitietdonhang.SoLuong');
        // Danh sách đánh giá kèm tên tài khoản
        $dsDanhGia = DB::table('danhgia')
            ->join(
                'taikhoan',
                'danhgia.MaTaiKhoan',
                '=',
                'taikhoan.MaTaiKhoan'
            )
            ->where('danhgia.MaSanPham', $product->MaSanPham)
            ->where('danhgia.TrangThai', 1)
            ->where('taikhoan.IsDeleted', 0)
            ->orderByDesc('danhgia.NgayDanhGia')
            ->select(
                'danhgia.*',
                'taikhoan.TenDangNhap'
            )
            ->get();

        // Kiểm tra tài khoản đã mua sản phẩm chưa
        $daMua = false;

        if (Auth::check()) {
            $maTaiKhoan = Auth::id();

            /*
             * LƯU Ý:
             * Đoạn này giả định chitietdonhang đã có cột MaChiTietSP
             * để liên kết với bảng chitietsanpham.
             */
            $daMua = DB::table('donhang')
                ->join(
                    'chitietdonhang',
                    'donhang.MaDonHang',
                    '=',
                    'chitietdonhang.MaDonHang'
                )
                ->join(
                    'chitietsanpham',
                    'chitietdonhang.MaChiTietSP',
                    '=',
                    'chitietsanpham.MaChiTietSP'
                )
                ->where('donhang.MaTaiKhoan', $maTaiKhoan)
                ->where(
                    'chitietsanpham.MaSanPham',
                    $product->MaSanPham
                )
                ->where('donhang.TrangThai', 'DaGiao')
                ->exists();
        }

        // Số lượt yêu thích
        $soLuotThich = Favorite::where(
            'MaSanPham',
            $product->MaSanPham
        )->count();

        // Kiểm tra sản phẩm đã được tài khoản yêu thích chưa
        $isFavorite = false;

        if (Auth::check()) {
            $isFavorite = Favorite::where(
                'MaTaiKhoan',
                Auth::id()
            )
            ->where('MaSanPham', $product->MaSanPham)
            ->exists();
        }

        // Sản phẩm liên quan:
        // cùng chất liệu hoặc cùng danh mục
        $relatedProducts = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('MaSanPham', '<>', $product->MaSanPham)
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0)
        ->whereHas('chiTietSanPham', function ($q) {
            $q->where('IsDeleted', 0);
        })
        ->where(function ($q) use ($product) {
            $q->where('MaChatLieu', $product->MaChatLieu);

            if ($product->danhMucs->isNotEmpty()) {
                $maDanhMuc = $product->danhMucs
                    ->pluck('MaDanhMuc')
                    ->all();

                $q->orWhereHas('danhMucs', function ($subQuery) use ($maDanhMuc) {
                    $subQuery->whereIn(
                        'danhmucsp.MaDanhMuc',
                        $maDanhMuc
                    );
                });
            }
        })
        ->inRandomOrder()
        ->take(8)
        ->get();

        return view('client.products.detail', compact(
            'product',
            'allSizes',
            'avgRating',
            'dsDanhGia',
            'daMua',
            'luotBan',
            'soLuotThich',
            'isFavorite',
            'relatedProducts'
        ));
    }

    /**
     * Hiển thị sản phẩm theo danh mục.
     */
    public function showByCategory(Request $request, $id)
    {
        $category = Category::where('MaDanhMuc', $id)
            ->where('IsDeleted', 0)
            ->firstOrFail();

        $products = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0)
        ->whereHas('danhMucs', function ($q) use ($id) {
            $q->where('danhmucsp.MaDanhMuc', $id);
        })
        ->whereHas('chiTietSanPham', function ($q) {
            $q->where('IsDeleted', 0);
        })
        ->paginate(8)
        ->withQueryString();

        return view(
            'client.products.showByCategory',
            compact('category', 'products')
        );
    }

    /**
     * Trang danh mục và bộ lọc sản phẩm.
     */
    public function category(Request $request)
    {
        $categories = Category::where('IsDeleted', 0)->get();

        $query = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0);

        if ($request->filled('category')) {
            $maDanhMuc = $request->category;

            $query->whereHas('danhMucs', function ($q) use ($maDanhMuc) {
                $q->where('danhmucsp.MaDanhMuc', $maDanhMuc);
            });
        }

        $products = $query->paginate(8)->withQueryString();

        return view(
            'client.products.category',
            compact('categories', 'products')
        );
    }

    /**
     * Danh sách tất cả sản phẩm.
     */
    public function productList()
    {
        $categories = Category::where('IsDeleted', 0)->get();

        $products = Product::with([
            'chatlieu',
            'danhMucs',
            'hinhanhsanpham',
            'chiTietSanPham.color',
            'chiTietSanPham.size',
        ])
        ->where('TrangThai', 1)
        ->where('IsDeleted', 0)
        ->whereHas('chiTietSanPham', function ($q) {
            $q->where('IsDeleted', 0);
        })
        ->orderBy('NgayTao', 'desc')
        ->paginate(8);

        return view(
            'client.products.index',
            compact('categories', 'products')
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng thành viên.
     * Khách vãng lai sử dụng Local Storage ở frontend.
     */
    public function addToCart(Request $request, $id) // $id ở đây là MaSanPham truyền qua URL
    {
        // 1. Phân luồng cho khách vãng lai
        if (!Auth::check()) {
            // Nếu gọi qua API/Fetch (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng sử dụng Local Storage cho giỏ hàng khách vãng lai.'
                ], 401);
            }
            
            // Nếu submit form HTML bình thường (có thể do lỗi JS, session hết hạn...)
            return back()->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng hoặc bật JavaScript để dùng giỏ hàng khách vãng lai!');
        }

        // 2. Validate bắt buộc phải có Màu sắc và Kích thước từ form gửi lên
        $request->validate([
            'SoLuong' => 'nullable|integer|min:1',
            'MaMau'   => 'required',
            'MaSize'  => 'required',
        ], [
            'MaMau.required'  => 'Vui lòng chọn màu sắc sản phẩm!',
            'MaSize.required' => 'Vui lòng chọn kích cỡ sản phẩm!',
        ]);

        $quantity = (int) $request->input('SoLuong', 1);

        // 3. Tìm chính xác biến thể (Chi tiết sản phẩm) dựa trên thông số khách chọn
        $productDetail = ProductDetail::where('MaSanPham', $id)
            ->where('MaMau', $request->MaMau)
            ->where('MaSize', $request->MaSize)
            ->where('IsDeleted', 0)
            ->first();

        if (!$productDetail) {
            return back()->with('error', 'Phân loại sản phẩm này không tồn tại hoặc đã hết hàng!');
        }

        $detailId = $productDetail->MaChiTietSP;

        // 4. Kiểm tra và cập nhật giỏ hàng trong Database
        $cartItem = Cart::where('MaTaiKhoan', Auth::id())
            ->where('MaChiTietSP', $detailId)
            ->first();

        if ($cartItem) {
            $cartItem->SoLuong += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'MaTaiKhoan'  => Auth::id(),
                'MaChiTietSP' => $detailId,
                'SoLuong'     => $quantity,
                'NgayThem'    => now(),
            ]);
        }

        return back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    /**
     * Đồng bộ giỏ hàng Local Storage vào database sau đăng nhập.
     */
    public function mergeCartAfterLogin(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa đăng nhập!'
            ], 401);
        }

        $request->validate([
            'items'           => 'required|array',
            'items.*.SoLuong' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $quantity = $item['SoLuong'];
                $productDetail = null;

                // Tương thích cả định dạng gửi MaChiTietSP hoặc gửi ID gốc kèm Màu & Size từ LocalStorage
                if (!empty($item['MaChiTietSP'])) {
                    $productDetail = ProductDetail::where('MaChiTietSP', $item['MaChiTietSP'])->first();
                } elseif (!empty($item['productId']) && !empty($item['colorId']) && !empty($item['sizeId'])) {
                    $productDetail = ProductDetail::where('MaSanPham', $item['productId'])
                        ->where('MaMau', $item['colorId'])
                        ->where('MaSize', $item['sizeId'])
                        ->where('IsDeleted', 0)
                        ->first();
                }

                if (!$productDetail) continue;

                $detailId = $productDetail->MaChiTietSP;

                $existing = Cart::where('MaTaiKhoan', Auth::id())
                    ->where('MaChiTietSP', $detailId)
                    ->first();

                if ($existing) {
                    $existing->SoLuong += $quantity;
                    $existing->save();
                } else {
                    Cart::create([
                        'MaTaiKhoan'  => Auth::id(),
                        'MaChiTietSP' => $detailId,
                        'SoLuong'     => $quantity,
                        'NgayThem'    => now(),
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Đồng bộ giỏ hàng thành công!'
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng thành viên.
     */
    public function updateQuantity(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập!'
            ], 401);
        }

        $request->validate([
            'id'   => 'required|integer',
            'type' => 'required|in:increase,decrease',
        ]);

        $item = Cart::where('MaGioHang', $request->id)
            ->where('MaTaiKhoan', Auth::id())
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'
            ], 404);
        }

        if ($request->type === 'increase') {
            $item->SoLuong += 1;
        } else {
            if ($item->SoLuong > 1) {
                $item->SoLuong -= 1;
            }
        }

        $item->save();

        return response()->json([
            'success' => true,
            'new_qty' => $item->SoLuong,
        ]);
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng thành viên.
     */
    public function removeFromCart($id)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập!');
        }

        Cart::where('MaGioHang', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    /**
     * Xóa toàn bộ giỏ hàng thành viên.
     */
    public function clearCart()
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập!');
        }

        Cart::where('MaTaiKhoan', Auth::id())->delete();

        return back()->with('success', 'Giỏ hàng đã được làm trống!');
    }

    /**
     * Hiển thị giỏ hàng.
     */
    public function viewCart()
    {
        $cartItems = collect();
        $totalPrice = 0;
        $totalQuantity = 0;

        if (Auth::check()) {
            $query = Cart::with([
                'productDetail.product',
                'productDetail.product.chatlieu',
                'productDetail.color',
                'productDetail.size',
            ])->where('MaTaiKhoan', Auth::id());

            $allItems = (clone $query)->get();

            $totalPrice = $allItems->sum(function ($item) {
                return (float) ($item->productDetail?->Gia ?? 0) * (int) $item->SoLuong;
            });

            $totalQuantity = $allItems->sum('SoLuong');
            $cartItems = $query->paginate(10);
        }

        return view('client.cart.index', compact('cartItems', 'totalPrice', 'totalQuantity'));
    }

    /**
     * Checkout dành cho thành viên.
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        $cartItems = Cart::with('productDetail')
            ->where('MaTaiKhoan', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $info = Auth::user();
        $addressData = [
            'soNha' => '',
            'phuongXa' => '',
            'quanHuyen' => '',
            'tinhThanh' => '',
        ];

        if ($info && $info->DiaChi) {
            $parts = explode(', ', $info->DiaChi);
            $addressData['soNha'] = $parts[0] ?? '';
            $addressData['phuongXa'] = $parts[1] ?? '';
            $addressData['quanHuyen'] = $parts[2] ?? '';
            $addressData['tinhThanh'] = $parts[3] ?? '';
        }

        return view('client.checkout.index', compact('cartItems', 'info', 'addressData'));
    }

    /**
     * Trang checkout thành công.
     */
    public function checkoutSuccess(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:20',
            'address'  => 'required|string|max:500',
        ]);

        return view('client.checkout.success');
    }
}
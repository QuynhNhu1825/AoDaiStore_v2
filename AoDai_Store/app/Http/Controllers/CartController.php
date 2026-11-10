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
    public function addToCart(Request $request, $id)
{
    // Khách vãng lai sử dụng Local Storage
    if (!Auth::check()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng sử dụng Local Storage cho giỏ hàng khách vãng lai.'
            ], 401);
        }

        return back()->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng!');
    }

    $request->validate([
        'SoLuong' => 'nullable|integer|min:1',
        'MaMau'   => 'required',
        'MaSize'  => 'required',
    ], [
        'MaMau.required'  => 'Vui lòng chọn màu sắc sản phẩm!',
        'MaSize.required' => 'Vui lòng chọn kích cỡ sản phẩm!',
    ]);

    $quantity = (int) $request->input('SoLuong', 1);

    $result = DB::transaction(function () use ($request, $id, $quantity) {

        // Khóa biến thể để tránh cập nhật tồn kho đồng thời
        $productDetail = ProductDetail::where('MaSanPham', $id)
            ->where('MaMau', $request->MaMau)
            ->where('MaSize', $request->MaSize)
            ->where('IsDeleted', 0)
            ->lockForUpdate()
            ->first();

        if (!$productDetail) {
            return [
                'success' => false,
                'message' => 'Phân loại sản phẩm không tồn tại hoặc đã bị xóa!'
            ];
        }

        $stock = (int) $productDetail->SoLuong;

        if ($stock <= 0) {
            return [
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng!'
            ];
        }

        $cartItem = Cart::where('MaTaiKhoan', Auth::id())
            ->where('MaChiTietSP', $productDetail->MaChiTietSP)
            ->lockForUpdate()
            ->first();

        $currentQuantity = $cartItem
            ? (int) $cartItem->SoLuong
            : 0;

        $newQuantity = $currentQuantity + $quantity;

        // Kiểm tra tổng số lượng sau khi thêm
        if ($newQuantity > $stock) {
            return [
                'success' => false,
                'message' => "Bạn đã có {$currentQuantity} sản phẩm trong giỏ. Kho hiện chỉ còn {$stock} sản phẩm."
            ];
        }

        if ($cartItem) {
            $cartItem->SoLuong = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'MaTaiKhoan'  => Auth::id(),
                'MaChiTietSP' => $productDetail->MaChiTietSP,
                'SoLuong'     => $quantity,
                'NgayThem'    => now(),
            ]);
        }

        return [
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!'
        ];
    });

    if (!$result['success']) {
        return back()->withInput()->with('error', $result['message']);
    }

    return back()->with('success', $result['message']);
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

    try {
        DB::transaction(function () use ($request) {

            foreach ($request->items as $item) {

                $quantity = (int) $item['SoLuong'];

                if (!empty($item['MaChiTietSP'])) {
                    $productDetail = ProductDetail::where(
                        'MaChiTietSP',
                        $item['MaChiTietSP']
                    )
                        ->where('IsDeleted', 0)
                        ->lockForUpdate()
                        ->first();

                } elseif (
                    !empty($item['productId']) &&
                    !empty($item['colorId']) &&
                    !empty($item['sizeId'])
                ) {
                    $productDetail = ProductDetail::where(
                        'MaSanPham',
                        $item['productId']
                    )
                        ->where('MaMau', $item['colorId'])
                        ->where('MaSize', $item['sizeId'])
                        ->where('IsDeleted', 0)
                        ->lockForUpdate()
                        ->first();

                } else {
                    continue;
                }

                if (!$productDetail) {
                    throw new \RuntimeException(
                        'Một sản phẩm trong giỏ hàng không còn tồn tại.'
                    );
                }

                $stock = (int) $productDetail->SoLuong;

                $existing = Cart::where('MaTaiKhoan', Auth::id())
                    ->where('MaChiTietSP', $productDetail->MaChiTietSP)
                    ->lockForUpdate()
                    ->first();

                $currentQuantity = $existing
                    ? (int) $existing->SoLuong
                    : 0;

                $newQuantity = $currentQuantity + $quantity;

                if ($newQuantity > $stock) {
                    throw new \RuntimeException(
                        "Sản phẩm {$productDetail->MaChiTietSP} không đủ tồn kho. Hiện có {$stock} sản phẩm, số lượng trong giỏ là {$currentQuantity}, số lượng cần đồng bộ là {$quantity}."
                    );
                }

                if ($existing) {
                    $existing->SoLuong = $newQuantity;
                    $existing->save();
                } else {
                    Cart::create([
                        'MaTaiKhoan'  => Auth::id(),
                        'MaChiTietSP' => $productDetail->MaChiTietSP,
                        'SoLuong'     => $quantity,
                        'NgayThem'    => now(),
                    ]);
                }
            }
        });

    } catch (\RuntimeException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }

    $request->session()->forget('merge_cart_pending');

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

    $result = DB::transaction(function () use ($request) {

        $item = Cart::where('MaGioHang', $request->id)
            ->where('MaTaiKhoan', Auth::id())
            ->lockForUpdate()
            ->first();

        if (!$item) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'
            ];
        }

        // Lấy tồn kho của đúng biến thể
        $productDetail = ProductDetail::where(
            'MaChiTietSP',
            $item->MaChiTietSP
        )
            ->where('IsDeleted', 0)
            ->lockForUpdate()
            ->first();

        if (!$productDetail) {
            return [
                'success' => false,
                'message' => 'Biến thể sản phẩm không còn tồn tại!'
            ];
        }

        $currentQuantity = (int) $item->SoLuong;
        $stock = (int) $productDetail->SoLuong;

        if ($request->type === 'increase') {

            $newQuantity = $currentQuantity + 1;

            // Không cho tăng vượt tồn kho
            if ($newQuantity > $stock) {
                return [
                    'success' => false,
                    'message' => "Sản phẩm chỉ còn {$stock} sản phẩm trong kho. Bạn đang có {$currentQuantity} sản phẩm trong giỏ."
                ];
            }

            $item->SoLuong = $newQuantity;

        } else {

            if ($currentQuantity > 1) {
                $item->SoLuong = $currentQuantity - 1;
            }
        }

        $item->save();

        return [
            'success' => true,
            'new_qty' => $item->SoLuong,
            'message' => 'Đã cập nhật số lượng!'
        ];
    });

    if (!$result['success']) {
        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 422);
    }

    return response()->json($result);
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

       $selectedIds = session('selected_cart_ids', []);

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Vui lòng chọn sản phẩm trước khi thanh toán.');
        }

        $cartItems = Cart::with([
            'productDetail.product',
            'productDetail.color',
            'productDetail.size',
        ])
            ->where('MaTaiKhoan', Auth::id())
            ->whereIn('MaGioHang', $selectedIds)
            ->get();

        if ($cartItems->isEmpty() || $cartItems->count() !== count($selectedIds)) {
            session()->forget('selected_cart_ids');

            return redirect()->route('cart.index')
                ->with('error', 'Sản phẩm đã chọn không còn trong giỏ hàng.');
        }

        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item->productDetail->Gia * $item->SoLuong;
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

        return view('client.checkout.index', compact('cartItems', 'info', 'addressData', 'totalPrice'));
    }
    public function checkoutSelected(Request $request)
{
    $request->validate([
        'selected_ids' => 'required|array|min:1',
        'selected_ids.*' => 'required|integer',
    ], [
        'selected_ids.required' => 'Vui lòng chọn sản phẩm muốn đặt hàng.',
        'selected_ids.min' => 'Vui lòng chọn ít nhất một sản phẩm.',
    ]);

    $selectedIds = array_unique(
        array_map('intval', $request->selected_ids)
    );

    $validCount = Cart::where('MaTaiKhoan', Auth::id())
        ->whereIn('MaGioHang', $selectedIds)
        ->count();

    if ($validCount !== count($selectedIds)) {
        return redirect()->route('cart.index')
            ->with('error', 'Danh sách sản phẩm không hợp lệ. Vui lòng chọn lại.');
    }

    session([
        'selected_cart_ids' => $selectedIds,
    ]);

    return redirect()->route('checkout.home');
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
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;


class AdminDashboardController extends Controller
{
    public function index()
    {
        // Chặn User / chưa đăng nhập
        if (!Auth::check() || Auth::user()->Role !== 'Admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị');
        }

        $totalAccounts   = Account::where('IsDeleted', 0)->count();
        $totalOrders     = Order::count();
        $totalProducts   = Product::where('IsDeleted', 0)->count();
        $totalCategories = Category::where('IsDeleted', 0)->count();

        return view('admin.dashboard', compact(
            'totalAccounts',
            'totalOrders',
            'totalProducts',
            'totalCategories'
        ));
    }
}

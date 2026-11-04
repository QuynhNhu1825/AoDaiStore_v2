<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Promotion;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Chặn User / chưa đăng nhập
        if (!Auth::check() || Auth::user()->Role !== 'Admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị');
        }

        $totalAccounts   = Account::count();
        $totalOrders     = Order::count();
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        

        return view('admin.dashboard', compact(
            'totalAccounts',
            'totalOrders',
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalPromotions'
        ));
    }
}

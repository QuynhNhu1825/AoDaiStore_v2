<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return redirect('/?login=1');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|string|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'MatKhau' => 'required|string|min:6|max:32',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'TenDangNhap.max' => 'Tên đăng nhập không được vượt quá :max ký tự.',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu.',
            'MatKhau.min' => 'Mật khẩu phải ít nhất :min ký tự.',
            'MatKhau.max' => 'Mật khẩu không được vượt quá :max ký tự.',
        ]);

        // Kiểm tra tài khoản tồn tại
        $user = Account::where('TenDangNhap', $request->TenDangNhap)->first();

        if (!$user) {
            return back()->withErrors([
                'TenDangNhap' => 'Tên đăng nhập không tồn tại.',
            ]);
        }

        // Kiểm tra trạng thái tài khoản
        if ($user->TrangThai != 1 || $user->IsDeleted != 0) {
            return back()->withErrors([
                'TenDangNhap' => 'Tài khoản đã bị khóa hoặc ngưng hoạt động.',
            ]);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->MatKhau, $user->MatKhau)) {
            return back()->withErrors([
                'MatKhau' => 'Mật khẩu không chính xác.',
            ]);
        }

        // Đăng nhập
        $credentials = [
            'TenDangNhap' => $request->TenDangNhap,
            'password' => $request->MatKhau,
        ];

        if (Auth::attempt($credentials)) {

            // Regenerate session sau đăng nhập
            $request->session()->regenerate();

            $user = Auth::user();

            // Admin
            if ($user->Role == 'Admin') {
                return redirect()->route('admin.dashboard');
            }

            // Thành viên: chuyển về trang chủ và đồng bộ giỏ hàng
            if ($user->Role == 'User') {
                return redirect('/')
                    ->with('merge_cart_pending', true);
            }
        }

        return back()->withErrors([
            'TenDangNhap' => 'Tên đăng nhập hoặc mật khẩu sai.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

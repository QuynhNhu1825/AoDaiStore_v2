<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;

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
        // Validate dữ liệu
        $request->validate([
            'TenDangNhap' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'MatKhau' => 'required|string|min:6|max:32',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'TenDangNhap.max' => 'Tên đăng nhập không được vượt quá :max ký tự.',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu.',
            'MatKhau.min' => 'Mật khẩu phải ít nhất :min ký tự.',
            'MatKhau.max' => 'Mật khẩu không được vượt quá :max ký tự.',
        ]);

        // 1. Tìm tài khoản
        $user = Account::where(
            'TenDangNhap',
            $request->TenDangNhap
        )->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'TenDangNhap' => 'Tên đăng nhập không tồn tại.'
                ])
                ->withInput($request->only('TenDangNhap'));
        }

        // 2. Kiểm tra trạng thái tài khoản
        if ($user->TrangThai != 1 || $user->IsDeleted != 0) {
            return back()
                ->withErrors([
                    'TenDangNhap' => 'Tài khoản đã bị khóa hoặc ngưng hoạt động.'
                ])
                ->withInput($request->only('TenDangNhap'));
        }

        // 3. Kiểm tra mật khẩu Bcrypt
        if (!Hash::check($request->MatKhau, $user->MatKhau)) {
            return back()
                ->withErrors([
                    'MatKhau' => 'Mật khẩu không chính xác.'
                ])
                ->withInput($request->only('TenDangNhap'));
        }

        // 4. Đăng nhập tài khoản
        Auth::login($user);

        // Tạo session mới để bảo mật
        $request->session()->regenerate();

        // 5. Phân quyền chuyển trang
        if ($user->Role == 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->Role == 'User') {
            return redirect('/')
                ->with('merge_cart_pending', true);
        }

        // Trường hợp role không hợp lệ
        Auth::logout();

        return back()->withErrors([
            'TenDangNhap' => 'Tài khoản không có quyền truy cập.'
        ]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

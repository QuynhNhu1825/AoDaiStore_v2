<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'TenDangNhap' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:TaiKhoan,TenDangNhap',
            ],

            'MatKhau' => [
                'required',
                'string',
                'min:6',
                'max:32',
                'confirmed',
            ],

            'HoTen' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s]+$/u',
            ],

            'Email' => [
                'required',
                'email',
                'max:100',
                'unique:TaiKhoan,Email',
            ],

            'SDT' => [
                'nullable',
                'regex:/^[0-9]{10,11}$/',
            ],

        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại.',
            'TenDangNhap.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'TenDangNhap.max' => 'Tên đăng nhập không được vượt quá :max ký tự.',

            'MatKhau.required' => 'Vui lòng nhập mật khẩu.',
            'MatKhau.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'MatKhau.min' => 'Mật khẩu phải ít nhất :min ký tự.',
            'MatKhau.max' => 'Mật khẩu không được vượt quá :max ký tự.',

            'HoTen.required' => 'Vui lòng nhập họ tên.',
            'HoTen.regex' => 'Họ tên chỉ được chứa chữ cái và khoảng trắng.',
            'HoTen.max' => 'Họ tên không được vượt quá :max ký tự.',

            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.unique' => 'Email này đã được sử dụng.',
            'Email.max' => 'Email không được vượt quá :max ký tự.',

            'SDT.regex' => 'Số điện thoại phải gồm 10-11 chữ số.',

        ]);

        Account::create([
            'TenDangNhap' => $request->TenDangNhap,
            'MatKhau'     => Hash::make($request->MatKhau),
            'HoTen'       => $request->HoTen,
            'Email'       => $request->Email,
            'SDT'         => $request->SDT,

            // Tài khoản khách hàng mặc định
            'Role'        => 'User',

            // 1 = đang hoạt động
            'TrangThai'   => 1,

            // 0 = chưa xóa
            'IsDeleted'   => 0,

            // Thông tin tạo
            'NguoiTao'    => null,
            'NgayTao'     => now(),

            // Chưa cập nhật
            'NguoiCapNhat' => null,
            'NgayCapNhat'  => null,

            // Chưa xóa
            'NguoiXoa'    => null,
            'NgayXoa'     => null,
        ]);

        return redirect('/')
            ->with('openLoginModal', true)
            ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}
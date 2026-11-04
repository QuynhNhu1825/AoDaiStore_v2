<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ProfileController extends Controller
{
    /**
     * Hiển thị thông tin tài khoản
     */
    public function index()
    {
        $user = Auth::user();

        return view('client.profile.index', compact('user'));
    }

    /**
     * Hiển thị form cập nhật thông tin
     */
    public function edit()
    {
        $user = Auth::user();

        return view('client.profile.edit', compact('user'));
    }

    /**
     * Lưu thay đổi thông tin tài khoản
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Account $user */
        $user = Auth::user();

                $request->validate([
            'HoTen' => 'required|string|min:2|max:255',
            'Email' => [
                'required',
                'email',
                'max:255',
                'unique:taikhoan,Email,' . $user->MaTaiKhoan . ',MaTaiKhoan',
            ],
            'SDT' => [
                'required',
                'string',
                'regex:/^(03|05|07|08|09)[0-9]{8}$/',
            ],
            'DiaChi' => 'required|string|min:5|max:500',

        ], [
            // Validate Họ tên
            'HoTen.required' => 'Vui lòng nhập họ tên.',
            'HoTen.min'      => 'Họ tên phải có ít nhất 2 ký tự.',
            'HoTen.max'      => 'Họ tên không được vượt quá 255 ký tự.',

            // Validate Email
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email'    => 'Email không đúng định dạng.',
            'Email.max'      => 'Email không được vượt quá 255 ký tự.',
            'Email.unique'   => 'Email này đã được sử dụng. Vui lòng chọn email khác.',

            // Validate Số điện thoại
            'SDT.required' => 'Vui lòng nhập số điện thoại.',
            'SDT.regex'    => 'Số điện thoại không hợp lệ (phải là số Việt Nam gồm 10 chữ số, bắt đầu bằng 03, 05, 07, 08 hoặc 09).',

            // Validate Địa chỉ
            'DiaChi.required' => 'Vui lòng nhập địa chỉ.',
            'DiaChi.min'      => 'Địa chỉ quá ngắn, vui lòng nhập ít nhất 5 ký tự.',
            'DiaChi.max'      => 'Địa chỉ không được vượt quá 500 ký tự.',
        ]);

        try {
            // Cập nhật thông tin
            $user->HoTen = $request->HoTen;
            $user->Email = $request->Email;
            $user->SDT = $request->SDT;
            $user->DiaChi = $request->DiaChi;

            $user->NguoiCapNhat = $user->MaTaiKhoan;
            $user->NgayCapNhat = now();

            $user->save();

            return redirect()
                ->route('profile.index')
                ->with('success', 'Cập nhật thông tin tài khoản thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->route('profile.edit')
                ->withInput()
                ->with('error', 'Cập nhật thông tin thất bại. Vui lòng thử lại!');
        }
    }
}
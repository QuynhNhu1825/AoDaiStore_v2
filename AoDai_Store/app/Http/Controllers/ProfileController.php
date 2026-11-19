<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
     * Tách địa chỉ thành 3 thành phần
     */
    private function parseAddress(?string $address): array
    {
        $result = [
            'soNha' => '',
            'phuongXa' => '',
            'tinhThanh' => '',
        ];

        if (empty($address)) {
            return $result;
        }

        $parts = array_map('trim', explode(',', $address));

        if (count($parts) >= 3) {
            $result['tinhThanh'] = array_pop($parts);
            $result['phuongXa'] = array_pop($parts);
            $result['soNha'] = implode(', ', $parts);
        } elseif (count($parts) === 2) {
            $result['soNha'] = $parts[0];
            $result['phuongXa'] = $parts[1];
        } else {
            $result['soNha'] = $parts[0] ?? '';
        }

        return $result;
    }

    /**
     * Hiển thị form cập nhật thông tin
     */
    public function edit()
    {
        $user = Auth::user();

        $addressData = $this->parseAddress($user->DiaChi);

        return view('client.profile.edit', compact(
            'user',
            'addressData'
        ));
    }

    /**
     * Lưu thay đổi thông tin tài khoản
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Account $user */
        $user = Auth::user();

        $validated = $request->validate([
            'HoTen' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'Email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('taikhoan', 'Email')
                    ->ignore($user->MaTaiKhoan, 'MaTaiKhoan'),
            ],

            'SDT' => [
                'required',
                'string',
                'regex:/^(03|05|07|08|09)[0-9]{8}$/',
                Rule::unique('taikhoan', 'SDT')
                    ->ignore($user->MaTaiKhoan, 'MaTaiKhoan'),
            ],

            'so_nha' => [
                'required',
                'string',
                'min:2',
                'max:200',
            ],

            'phuong_xa' => [
                'required',
                'string',
                'max:150',
            ],

            'tinh_thanh' => [
                'required',
                'string',
                'max:150',
            ],

        ], [
            'HoTen.required' => 'Vui lòng nhập họ và tên.',
            'HoTen.min' => 'Họ tên phải có ít nhất 2 ký tự.',
            'HoTen.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.unique' => 'Email này đã được sử dụng.',

            'SDT.required' => 'Vui lòng nhập số điện thoại.',
            'SDT.regex' => 'Số điện thoại không hợp lệ.',
            'SDT.unique' => 'Số điện thoại này đã được sử dụng.',

            'so_nha.required' => 'Vui lòng nhập số nhà, tên đường.',
            'so_nha.min' => 'Số nhà, tên đường quá ngắn.',

            'phuong_xa.required' => 'Vui lòng nhập phường hoặc xã.',
            'tinh_thanh.required' => 'Vui lòng nhập tỉnh hoặc thành phố.',
        ]);

        // Ghép địa chỉ theo định dạng thống nhất
        $diaChi = implode(', ', [
            trim($validated['so_nha']),
            trim($validated['phuong_xa']),
            trim($validated['tinh_thanh']),
        ]);

        if (mb_strlen($diaChi) > 500) {
            return back()->withInput()->withErrors([
                'tinh_thanh' => 'Địa chỉ không được vượt quá 500 ký tự.',
            ]);
        }

        try {
            $user->HoTen = trim($validated['HoTen']);
            $user->Email = trim($validated['Email']);
            $user->SDT = trim($validated['SDT']);
            $user->DiaChi = $diaChi;

            // Audit
            $user->NguoiCapNhat = $user->MaTaiKhoan;
            $user->NgayCapNhat = now();

            $user->save();

            return redirect()
                ->route('profile.index')
                ->with(
                    'success',
                    'Cập nhật thông tin tài khoản thành công!'
                );

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cập nhật thông tin thất bại. Vui lòng thử lại!'
                );
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));
        $baseQuery = Account::query()->where('IsDeleted', 0);

        $totalAccounts = (clone $baseQuery)->count();
        $activeAccounts = (clone $baseQuery)->where('TrangThai', 1)->count();
        $lockedAccounts = (clone $baseQuery)->where('TrangThai', 0)->count();

        $accounts = (clone $baseQuery)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('TenDangNhap', 'like', "%{$keyword}%")
                        ->orWhere('HoTen', 'like', "%{$keyword}%")
                        ->orWhere('Email', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('MaTaiKhoan', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', compact(
            'accounts', 'keyword', 'totalAccounts', 'activeAccounts', 'lockedAccounts'
        ));
    }

    public function edit($id)
    {
        $account = Account::where('IsDeleted', 0)->findOrFail($id);

        if (strcasecmp((string) $account->Role, 'Admin') === 0) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Không được phép chỉnh sửa tài khoản Admin.');
        }

        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = Account::where('IsDeleted', 0)->findOrFail($id);

        if (strcasecmp((string) $account->Role, 'Admin') === 0) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Không được phép chỉnh sửa tài khoản Admin.');
        }

        $validated = $request->validate([
            'HoTen' => ['required', 'string', 'min:2', 'max:255'],
            'Email' => [
                'required', 'email', 'max:255',
                Rule::unique('taikhoan', 'Email')->ignore($account->MaTaiKhoan, 'MaTaiKhoan'),
            ],
            'SDT' => [
                'nullable', 'regex:/^(03|05|07|08|09)[0-9]{8}$/',
                Rule::unique('taikhoan', 'SDT')->ignore($account->MaTaiKhoan, 'MaTaiKhoan'),
            ],
            'DiaChi' => ['nullable', 'string', 'max:500'],
            'MatKhau' => ['nullable', 'string', 'min:6', 'max:32'],
        ], [
            'HoTen.required' => 'Vui lòng nhập họ tên.',
            'HoTen.min' => 'Họ tên phải có ít nhất 2 ký tự.',
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.unique' => 'Email đã được sử dụng.',
            'SDT.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',
            'SDT.unique' => 'Số điện thoại đã được sử dụng.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'MatKhau.max' => 'Mật khẩu không được vượt quá 32 ký tự.',
        ]);

        $account->HoTen = $validated['HoTen'];
        $account->Email = $validated['Email'];
        $account->SDT = $validated['SDT'] ?? null;
        $account->DiaChi = $validated['DiaChi'] ?? null;

        // Role và TrangThai được giữ nguyên, không nhận thay đổi từ form.
        if (!empty($validated['MatKhau'])) {
            $account->MatKhau = Hash::make($validated['MatKhau']);
        }

        $account->NguoiCapNhat = auth()->id();
        $account->NgayCapNhat = now();
        $account->save();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function lock($id)
    {
        $account = Account::where('IsDeleted', 0)->findOrFail($id);

        if (strcasecmp((string) $account->Role, 'Admin') === 0) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Không được phép khóa/mở khóa tài khoản Admin.');
        }

        if ((string) $account->MaTaiKhoan === (string) auth()->id()) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản đang đăng nhập.');
        }

        $account->TrangThai = !$account->TrangThai;
        $account->NguoiCapNhat = auth()->id();
        $account->NgayCapNhat = now();
        $account->save();

        return back()->with('success', $account->TrangThai
            ? 'Mở khóa tài khoản thành công!'
            : 'Khóa tài khoản thành công!');
    }
}

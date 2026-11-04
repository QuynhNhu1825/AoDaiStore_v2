<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    // Trang danh sách + tìm kiếm
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $accounts = Account::when($keyword, function ($q) use ($keyword) {
            $q->where(function ($sub) use ($keyword) {
                $sub->where('TenDangNhap', 'like', "%{$keyword}%")
                    ->orWhere('HoTen', 'like', "%{$keyword}%")
                    ->orWhere('Email', 'like', "%{$keyword}%");
            });
        })
        ->orderBy('MaTaiKhoan', 'asc')
        ->paginate(10);

        return view('admin.accounts.index', compact('accounts', 'keyword'));
    }


    // Form cập nhật
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        return view('admin.accounts.edit', compact('account'));
    }

    // Lưu cập nhật
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $account->HoTen = $request->HoTen;
        $account->Email = $request->Email;
        $account->SoDienThoai = $request->SoDienThoai;
        $account->DiaChi = $request->DiaChi;
        $account->Role = $request->Role;

        // Nếu nhập mật khẩu mới
        if($request->MatKhau){
            $account->MatKhau = Hash::make($request->MatKhau);
        }

        $account->save();

        return redirect()->route('admin.accounts.index')->with('success','Cập nhật thành công');
    }

    // Khóa / mở khóa
    public function lock($id)
    {
        $account = Account::findOrFail($id);
        $account->TrangThai = !$account->TrangThai;
        $account->save();

        return back();
    }
}

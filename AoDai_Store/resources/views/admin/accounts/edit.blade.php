@extends('layouts.admin')

@section('title', 'Chỉnh sửa tài khoản: ' . $account->TenDangNhap)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Nút quay lại --}}
    <div class="mb-4">
        <a href="{{ route('admin.accounts.index') }}"
           class="text-indigo-600 hover:underline flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách tài khoản
        </a>
    </div>

    {{-- Thẻ nội dung --}}
    <div class="bg-white p-8 rounded-xl shadow-lg border-t-4 border-indigo-600">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">
                Cập nhật thông tin tài khoản
            </h2>
            <p class="text-xs text-gray-500 mt-1 italic">
                Mã tài khoản: #{{ $account->MaTaiKhoan }}
            </p>
        </div>

        <form action="{{ route('admin.accounts.update', $account->MaTaiKhoan) }}"
              method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">

                {{-- Username --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Tên đăng nhập
                    </label>
                    <input type="text" value="{{ $account->TenDangNhap }}" disabled
                           class="w-full border rounded-lg p-2.5 bg-gray-100 text-gray-500">
                </div>

                {{-- Họ tên --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Họ tên
                    </label>
                    <input type="text" name="HoTen"
                           value="{{ old('HoTen', $account->HoTen) }}"
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Email
                    </label>
                    <input type="email" name="Email"
                           value="{{ old('Email', $account->Email) }}"
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Số điện thoại --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Số điện thoại
                    </label>
                    <input type="text" name="SoDienThoai"
                           value="{{ old('SoDienThoai', $account->SoDienThoai) }}"
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Địa chỉ --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Địa chỉ
                    </label>
                    <input type="text" name="DiaChi"
                           value="{{ old('DiaChi', $account->DiaChi) }}"
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Quyền --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Quyền tài khoản
                    </label>
                    <select name="Role"
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">

                        <option value="Admin" {{ $account->Role == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="User"  {{ $account->Role == 'User'  ? 'selected' : '' }}>User</option>

                    </select>

                </div>

                {{-- Mật khẩu mới --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Mật khẩu mới (nếu đổi)
                    </label>
                    <input type="password" name="MatKhau"
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"
                           placeholder="Nhập nếu muốn đổi mật khẩu">
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Trạng thái
                    </label>
                    <select name="TrangThai"
                            class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="1" {{ $account->TrangThai == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ $account->TrangThai == 0 ? 'selected' : '' }}>Bị khóa</option>
                    </select>
                </div>

            </div>

            {{-- Nút --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-4 border-t pt-6">
                <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-3.5 rounded-lg font-bold
                               hover:bg-indigo-700 shadow-lg hover:shadow-indigo-200 transition-all
                               flex justify-center items-center uppercase tracking-widest">
                    <i class="fas fa-sync-alt mr-2"></i> Lưu thay đổi
                </button>

                <a href="{{ route('admin.accounts.index') }}"
                   class="px-10 py-3.5 border border-gray-300 text-gray-500 rounded-lg
                          hover:bg-gray-50 transition font-bold text-center">
                    Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-indigo-50 p-4 rounded-lg flex items-center text-indigo-700 border border-indigo-100">
        <i class="fas fa-info-circle mr-3"></i>
        <p class="text-xs italic">
            Lưu ý: Thay đổi thông tin tài khoản có thể ảnh hưởng đến quyền truy cập hệ thống.
        </p>
    </div>

</div>
@endsection

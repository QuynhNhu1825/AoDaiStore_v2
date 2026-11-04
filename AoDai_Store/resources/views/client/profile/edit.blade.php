@extends('layouts.client')

@section('title', 'Cập nhật thông tin tài khoản')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div id="success-alert"
            class="mb-5 flex items-center gap-3 p-4
                bg-green-50 border border-green-200
                text-green-700 rounded-xl transition-opacity duration-500">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert"
            class="mb-5 flex items-center gap-3 p-4
                bg-red-50 border border-red-200
                text-red-700 rounded-xl transition-opacity duration-500">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ['success-alert', 'error-alert'].forEach(id => {
                const alert = document.getElementById(id);

                if (alert) {
                    setTimeout(() => {
                        alert.classList.add('opacity-0');

                        setTimeout(() => alert.remove(), 500);
                    }, 3000);
                }
            });
        });
    </script>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Cập nhật thông tin tài khoản
        </h2>
        <p class="text-gray-500 mt-1">
            Chỉnh sửa thông tin cá nhân của bạn.
        </p>
    </div>
    @if(empty($user->SDT) || empty($user->DiaChi))
        <div class="mb-5 flex items-start gap-3 p-4
                    bg-amber-50 border border-amber-200
                    text-amber-800 rounded-xl">
            <i class="fa-solid fa-circle-info mt-1"></i>
            <div>
                <p class="font-semibold">Thông tin tài khoản chưa đầy đủ</p>
                <p class="text-sm mt-1">
                    Vui lòng bổ sung số điện thoại và địa chỉ để thuận tiện
                    cho việc đặt hàng và giao nhận.
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">

                <div>
                    <label class="block font-medium mb-2">Họ và tên</label>
                    <input
                        type="text"
                        name="HoTen"
                        value="{{ old('HoTen', $user->HoTen) }}"
                        class="w-full border rounded-lg px-4 py-2"
                    >
                    @error('HoTen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Email</label>
                    <input
                        type="email"
                        name="Email"
                        value="{{ old('Email', $user->Email) }}"
                        class="w-full border rounded-lg px-4 py-2"
                    >
                    @error('Email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Số điện thoại</label>
                    <input
                        type="text"
                        name="SDT"
                        value="{{ old('SDT', $user->SDT) }}"
                        class="w-full border rounded-lg px-4 py-2"
                    >
                    @error('SDT')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Địa chỉ</label>
                    <textarea
                        name="DiaChi"
                        rows="3"
                        class="w-full border rounded-lg px-4 py-2"
                    >{{ old('DiaChi', $user->DiaChi) }}</textarea>
                    @error('DiaChi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('profile.index') }}"
                   class="px-5 py-2 border rounded-lg">
                    Hủy
                </a>

                <button type="submit"
                    class="px-5 py-2 bg-amber-700 text-white rounded-lg">
                    Lưu thay đổi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
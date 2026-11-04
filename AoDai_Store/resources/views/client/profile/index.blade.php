@extends('layouts.client')

@section('title', 'Thông tin tài khoản')

@section('content')

<div class="max-w-4xl mx-auto py-10 px-4">

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.getElementById('success-alert');

            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0');

                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>

    {{-- KHUNG THÔNG TIN --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-red-900 to-red-800
                        px-6 py-5 flex flex-col sm:flex-row
                        items-start sm:items-center justify-between gap-4">

            <div>
                <h3 class="text-lg font-bold text-white">
                    Thông tin khách hàng
                </h3>
                <p class="text-sm text-slate-300 mt-1">
                    Thông tin được sử dụng trong quá trình đặt hàng.
                </p>
            </div>

            <a href="{{ route('profile.edit') }}"
            class="inline-flex items-center gap-2
                    bg-amber-100 hover:bg-amber-200
                    text-red-900 font-bold
                    px-5 py-2.5 rounded-lg
                    transition duration-200 shadow-md
                    border border-amber-200">
                <i class="fa-solid fa-pen-to-square"></i>
                Cập nhật thông tin
            </a>
        </div>

        {{-- BẢNG THÔNG TIN --}}
        <div class="p-5 sm:p-7">

            <div class="overflow-hidden border border-gray-200 rounded-xl">

                <table class="w-full text-sm sm:text-base">

                    <tbody class="divide-y divide-gray-200">

                        {{-- TÊN ĐĂNG NHẬP --}}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-slate-700">
                                <i class="fa-solid fa-user-tag text-amber-700 mr-2"></i>
                                Tên đăng nhập
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-800">
                                {{ $user->TenDangNhap }}
                            </td>
                        </tr>

                        {{-- HỌ TÊN --}}
                        <tr class="bg-gray-50 hover:bg-amber-50/50 transition">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-slate-700">
                                <i class="fa-solid fa-user text-amber-700 mr-2"></i>
                                Họ và tên
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-800">
                                {{ $user->HoTen ?: 'Chưa cập nhật' }}
                            </td>
                        </tr>

                        {{-- EMAIL --}}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-slate-700">
                                <i class="fa-solid fa-envelope text-amber-700 mr-2"></i>
                                Email
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-800 break-all">
                                {{ $user->Email ?: 'Chưa cập nhật' }}
                            </td>
                        </tr>

                        {{-- SỐ ĐIỆN THOẠI --}}
                        <tr class="bg-gray-50 hover:bg-amber-50/50 transition">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-slate-700">
                                <i class="fa-solid fa-phone text-amber-700 mr-2"></i>
                                Số điện thoại
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-800">
                                {{ $user->SDT ?: 'Chưa cập nhật' }}
                            </td>
                        </tr>

                        {{-- ĐỊA CHỈ --}}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-slate-700">
                                <i class="fa-solid fa-location-dot text-amber-700 mr-2"></i>
                                Địa chỉ
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-800">
                                {{ $user->DiaChi ?: 'Chưa cập nhật' }}
                            </td>
                        </tr>

                        {{-- NGÀY ĐĂNG KÝ --}}
                        <tr class="bg-gray-50 hover:bg-amber-50/50 transition">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-slate-700">
                                <i class="fa-solid fa-calendar-days text-amber-700 mr-2"></i>
                                Ngày đăng ký
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-800">
                                @if($user->NgayTao)
                                    {{ \Carbon\Carbon::parse($user->NgayTao)->format('d/m/Y') }}
                                @else
                                    Chưa cập nhật
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>

            {{-- GHI CHÚ --}}
            <div class="mt-5 flex items-start gap-2 text-sm text-gray-500">
                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                <p>
                    Vui lòng kiểm tra và cập nhật thông tin chính xác
                    để thuận tiện cho quá trình đặt hàng và giao nhận áo dài.
                </p>
            </div>

        </div>
    </div>

</div>

@endsection

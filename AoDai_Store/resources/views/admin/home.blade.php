@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển Quản Trị')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Chào mừng trở lại, Admin!</h1>
        <p class="text-gray-500">Đây là tóm tắt tình hình kinh doanh tiệm Áo Dài hôm nay.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-100 rounded-full text-emerald-600 mr-4">
                    <i class="fas fa-money-bill-wave fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Doanh thu tháng</p>
                    <p class="text-2xl font-bold text-gray-800">45.200.000đ</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full text-orange-600 mr-4">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Đơn hàng mới</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full text-blue-600 mr-4">
                    <i class="fas fa-tshirt fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Mẫu Áo Dài</p>
                    <p class="text-2xl font-bold text-gray-800">86</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full text-purple-600 mr-4">
                    <i class="fas fa-users fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Khách hàng</p>
                    <p class="text-2xl font-bold text-gray-800">1.240</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Đơn hàng chờ xác nhận</h3>
                <a href="#" class="text-blue-600 text-sm hover:underline">Xem tất cả</a>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3">Mã đơn</th>
                            <th class="px-6 py-3">Khách hàng</th>
                            <th class="px-6 py-3">Giá trị</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <tr>
                            <td class="px-6 py-4 font-medium">#AD-9921</td>
                            <td class="px-6 py-4">Lê Thu Thảo</td>
                            <td class="px-6 py-4">2.500.000đ</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase">Xử lý</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium">#AD-9922</td>
                            <td class="px-6 py-4">Trần Minh Anh</td>
                            <td class="px-6 py-4">1.800.000đ</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase">Xử lý</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-700 mb-4">Mẫu Áo Dài Bán Chạy</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="https://placehold.co/40x60" class="rounded mr-3 shadow-sm">
                        <div>
                            <p class="text-sm font-bold">Áo dài lụa tơ tằm thêu hoa</p>
                            <p class="text-xs text-gray-400">Đã bán: 45 bộ</p>
                        </div>
                    </div>
                    <span class="text-emerald-500 font-bold text-sm">+15%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="https://placehold.co/40x60" class="rounded mr-3 shadow-sm">
                        <div>
                            <p class="text-sm font-bold">Áo dài cách tân hoa sen</p>
                            <p class="text-xs text-gray-400">Đã bán: 32 bộ</p>
                        </div>
                    </div>
                    <span class="text-emerald-500 font-bold text-sm">+8%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
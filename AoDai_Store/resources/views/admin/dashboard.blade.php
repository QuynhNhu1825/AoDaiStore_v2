
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-10">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">
            Tổng quan hệ thống
        </h1>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- SẢN PHẨM --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Sản phẩm</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $totalProducts }}
                </p>
            </div>

            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>

        {{-- DANH MỤC --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Danh mục</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $totalCategories }}
                </p>
            </div>

            <div class="w-14 h-14 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                <i class="fas fa-list text-2xl"></i>
            </div>
        </div>

        {{-- TÀI KHOẢN --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Tài khoản</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $totalAccounts }}
                </p>
            </div>

            <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>

        {{-- ĐƠN HÀNG --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Đơn hàng</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $totalOrders }}
                </p>
            </div>

            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
        </div>

    </div>

@endsection

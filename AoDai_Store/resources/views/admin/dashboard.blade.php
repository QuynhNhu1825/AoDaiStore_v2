@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="w-full min-w-0 space-y-6 font-sans">

    {{-- THẺ THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <a href="{{ route('admin.products.index') }}" class="group flex items-center justify-between gap-3 rounded-2xl border border-red-100 bg-white p-5 shadow-sm transition hover:shadow-md min-w-0">
            <div class="min-w-0">
                <p class="text-[11px] font-semibold text-stone-500">Tổng sản phẩm</p>
                <p class="mt-2 text-3xl font-bold text-red-900">{{ number_format($totalProducts, 0, ',', '.') }}</p>
            </div>
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-800"><i class="fas fa-shirt text-xl"></i></div>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="group flex items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm transition hover:shadow-md min-w-0">
            <div class="min-w-0">
                <p class="text-[11px] font-semibold text-stone-500">Danh mục sản phẩm</p>
                <p class="mt-2 text-3xl font-bold text-stone-800">{{ number_format($totalCategories, 0, ',', '.') }}</p>
            </div>
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700"><i class="fas fa-layer-group text-xl"></i></div>
        </a>
        <a href="{{ route('admin.accounts.index') }}" class="group flex items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm transition hover:shadow-md min-w-0">
            <div class="min-w-0">
                <p class="text-[11px] font-semibold text-stone-500">Tổng tài khoản</p>
                <p class="mt-2 text-3xl font-bold text-stone-800">{{ number_format($totalAccounts, 0, ',', '.') }}</p>
            </div>
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i class="fas fa-users text-xl"></i></div>
        </a>
        <a href="{{ route('orders.index') }}" class="group flex items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm transition hover:shadow-md min-w-0">
            <div class="min-w-0">
                <p class="text-[11px] font-semibold text-stone-500">Tổng đơn hàng</p>
                <p class="mt-2 text-3xl font-bold text-stone-800">{{ number_format($totalOrders, 0, ',', '.') }}</p>
            </div>
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-receipt text-xl"></i></div>
        </a>
    </div>

    {{-- TRUY CẬP NHANH VÀ TỔNG QUAN --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <section class="xl:col-span-2 rounded-2xl border border-red-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-red-100 bg-red-50 px-5 py-4">
                <h2 class="text-sm font-bold text-red-900"><i class="fas fa-bolt mr-2"></i>Truy cập nhanh</h2>
                <span class="text-[10px] text-stone-500">Các nghiệp vụ thường dùng</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 sm:p-5">
                <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-red-200 hover:bg-red-50 transition"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-800"><i class="fas fa-plus"></i></span><span class="text-xs font-semibold text-stone-700">Thêm sản phẩm</span></a>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-red-200 hover:bg-red-50 transition"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700"><i class="fas fa-truck-fast"></i></span><span class="text-xs font-semibold text-stone-700">Xử lý đơn hàng</span></a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-red-200 hover:bg-red-50 transition"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><i class="fas fa-comments"></i></span><span class="text-xs font-semibold text-stone-700">Phản hồi đánh giá</span></a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-red-200 hover:bg-red-50 transition"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-50 text-purple-700"><i class="fas fa-layer-group"></i></span><span class="text-xs font-semibold text-stone-700">Danh mục</span></a>
                <a href="{{ route('admin.accounts.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-red-200 hover:bg-red-50 transition"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700"><i class="fas fa-user-gear"></i></span><span class="text-xs font-semibold text-stone-700">Tài khoản</span></a>
                <a href="{{ route('statistics.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-red-200 hover:bg-red-50 transition"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-700"><i class="fas fa-chart-column"></i></span><span class="text-xs font-semibold text-stone-700">Thống kê </span></a>
            </div>
        </section>

        <section class="rounded-2xl border border-red-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-red-100 bg-red-50 px-5 py-4"><h2 class="text-sm font-bold text-red-900"><i class="fas fa-circle-info mr-2"></i>Thông tin hệ thống</h2></div>
            <div class="space-y-4 p-5 text-xs">
                <div class="flex justify-between gap-3"><span class="text-stone-500">Cửa hàng</span><span class="font-semibold text-stone-800 text-right">Áo Dài Heritage</span></div>
                <div class="border-t border-stone-100"></div>
                <div class="flex justify-between gap-3"><span class="text-stone-500">Khu vực</span><span class="font-semibold text-stone-800">Quản trị</span></div>
                <div class="border-t border-stone-100"></div>
                <div class="flex justify-between gap-3"><span class="text-stone-500">Người đăng nhập</span><span class="font-semibold text-stone-800 text-right break-words">{{ Auth::user()->HoTen ?? 'Admin' }}</span></div>
                <div class="border-t border-stone-100"></div>
                <div class="flex justify-between gap-3"><span class="text-stone-500">Ngày hiện tại</span><span class="font-semibold text-stone-800">{{ now()->format('d/m/Y') }}</span></div>
            </div>
        </section>
    </div>

    {{-- QUY TRÌNH NGHIỆP VỤ --}}
    <section class="rounded-2xl border border-red-100 bg-white shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-red-100 bg-red-50 px-5 py-4">
            <h2 class="text-sm font-bold text-red-900"><i class="fas fa-route mr-2"></i>Quy trình xử lý đơn hàng</h2>
            <a href="{{ route('orders.index') }}" class="text-[11px] font-semibold text-red-800 hover:underline">Quản lý đơn hàng <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 p-4 sm:p-5">
            @foreach([
                ['icon' => 'fa-clock', 'title' => 'Chờ xác nhận', 'desc' => 'Kiểm tra thông tin đơn hàng'],
                ['icon' => 'fa-circle-check', 'title' => 'Đã xác nhận', 'desc' => 'Chuẩn bị sản phẩm giao khách'],
                ['icon' => 'fa-truck', 'title' => 'Đang giao', 'desc' => 'Theo dõi quá trình giao hàng'],
                ['icon' => 'fa-box-open', 'title' => 'Đã giao', 'desc' => 'Hoàn tất giao hàng cho khách'],
            ] as $step)
                <div class="rounded-xl border border-stone-200 bg-stone-50 p-4">
                    <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-white text-red-800 shadow-sm"><i class="fas {{ $step['icon'] }}"></i></div>
                    <h3 class="text-xs font-bold text-stone-800">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-[11px] leading-relaxed text-stone-500">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-10">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Tổng quan hệ thống</h1>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- SẢN PHẨM --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Sản phẩm</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>

        {{-- DANH MỤC --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Danh mục</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCategories }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                <i class="fas fa-list text-2xl"></i>
            </div>
        </div>

        {{-- TÀI KHOẢN --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Tài khoản</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAccounts }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>

        {{-- ĐƠN HÀNG --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Đơn hàng</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
        </div>

        {{-- NHÀ CUNG CẤP --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Nhà cung cấp</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSuppliers }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                <i class="fas fa-truck text-2xl"></i>
            </div>
        </div>

        {{-- KHUYẾN MÃI --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Khuyến mãi</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPromotions }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                <i class="fas fa-tags text-2xl"></i>
            </div>
        </div>

    </div>

    {{-- THÔNG TIN WEBSITE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b font-bold text-gray-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Thông tin Website
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.dashboard.update') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    {{-- ĐỊA CHỈ --}}
                    <div class="flex flex-col">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">
                            Địa chỉ shop
                        </label>
                        <input type="text"
                            name="DiaChiShop"
                            value="{{ old('DiaChiShop', $infoWeb->DiaChiShop) }}"
                            placeholder="Nhập địa chỉ..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                    </div>

                    {{-- GIỜ MỞ CỬA --}}
                    <div class="flex flex-col">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">
                            Thời gian mở cửa
                        </label>
                        <input type="text"
                            name="GioMoCua"
                            value="{{ old('GioMoCua', $infoWeb->GioMoCua) }}"
                            placeholder="Ví dụ: 08:00 - 22:00"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                    </div>

                    {{-- EMAIL --}}
                    <div class="flex flex-col">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">
                            Email liên hệ
                        </label>
                        <input type="email"
                            name="Email"
                            value="{{ old('Email', $infoWeb->Email) }}"
                            placeholder="example@gmail.com"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                    </div>

                    {{-- HOTLINE --}}
                    <div class="flex flex-col">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">
                            Hotline (Số điện thoại)
                        </label>
                        <input type="text"
                            name="SoDienThoai"
                            value="{{ old('SoDienThoai', $infoWeb->SoDienThoai) }}"
                            placeholder="09xx xxx xxx"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                    </div>
                </div>

                {{-- NÚT LƯU --}}
                <div class="mt-8 flex justify-end border-t pt-6">
                    <button type="button"
                            onclick="confirmSave()"
                            class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-10 py-2.5 rounded-lg shadow-lg transform active:scale-95 transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Lưu thay đổi
                    </button>
                </div>

            </form>
        </div>
    </div>


</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmSave() {
    Swal.fire({
        title: 'Xác nhận lưu',
        text: 'Bạn có chắc muốn lưu thay đổi không?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Lưu',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector('form').submit();
        }
    });
}

</script>
@endsection



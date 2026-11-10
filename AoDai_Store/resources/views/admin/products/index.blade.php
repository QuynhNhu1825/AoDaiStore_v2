@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm Áo Dài')

@section('content')
<div class="w-full max-w-full min-w-0 space-y-4 admin-products-page" id="full-container">
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
    <div class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-red-100 flex items-center gap-2 sm:gap-4 min-w-0">
        <div class="p-2 sm:p-3 bg-red-50 rounded-xl text-red-800 shrink-0">
            <i class="fas fa-tshirt fa-lg"></i>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Tổng sản phẩm</p>
            <p class="text-xl font-bold text-red-900">{{ $totalProducts }}</p>
        </div>
    </div>

    <div class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-stone-100 flex items-center gap-2 sm:gap-4 min-w-0">
        <div class="p-2 sm:p-3 bg-green-50 rounded-xl text-green-600 shrink-0">
            <i class="fas fa-check-circle fa-lg"></i>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Đang hoạt động</p>
            <p class="text-xl font-bold text-stone-800">{{ $activeProducts }}</p>
        </div>
    </div>

    {{-- NGỪNG KINH DOANH --}}
    <div class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-stone-100 flex items-center gap-2 sm:gap-4 min-w-0">
        <div class="p-2 sm:p-3 bg-slate-100 rounded-xl text-slate-600 shrink-0">
            <i class="fas fa-ban text-sm sm:text-lg"></i>
        </div>
        <div class="min-w-0">
            <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide font-bold">Ngừng kinh doanh</p>
            <p class="text-lg sm:text-xl font-bold text-stone-800">{{ max(0, (int) $totalProducts - (int) $activeProducts) }}</p>
        </div>
    </div>
</div>
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">
    <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-[minmax(160px,1fr)_minmax(300px,2fr)_auto] items-stretch gap-3">
        
        <div class="relative w-full min-w-0 sm:col-span-2 xl:col-span-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                <i class="fas fa-search text-[10px]"></i>
            </span>
            <input type="text" name="search" 
                placeholder="Tìm kiếm..." 
                value="{{ request('search') }}"
                class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 w-full min-w-0 sm:col-span-2 xl:col-span-1">
            <select name="category" class="bg-stone-50 border border-stone-200 rounded-xl py-2 px-3 text-[11px] focus:ring-0 focus:border-red-700 transition-all cursor-pointer w-full min-w-0">
                <option value="">-- Danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->MaDanhMuc }}" {{ request('category') == $cat->MaDanhMuc ? 'selected' : '' }}>
                        {{ $cat->TenDanhMuc }}
                    </option>
                @endforeach
            </select>

            <select name="material" class="bg-stone-50 border border-stone-200 rounded-xl py-2 px-3 text-[11px] focus:ring-0 focus:border-red-700 cursor-pointer w-full min-w-0">
                <option value="">-- Chất liệu --</option>
                @foreach($materials as $m)
                    <option value="{{ $m->MaChatLieu }}" {{ request('material') == $m->MaChatLieu ? 'selected' : '' }}>
                        {{ $m->TenChatLieu }}
                    </option>
                @endforeach
            </select>

            <select name="color" class="bg-stone-50 border border-stone-200 rounded-xl py-2 px-3 text-[11px] focus:ring-0 focus:border-red-700 cursor-pointer w-full min-w-0">
                <option value="">-- Màu sắc --</option>
                @foreach($colors as $c)
                    <option value="{{ $c->MaMau }}" {{ request('color') == $c->MaMau ? 'selected' : '' }}>
                        {{ $c->TenMau }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 w-full xl:w-auto sm:col-span-2 xl:col-span-1">
            <button type="submit"
                class="w-full bg-red-800 text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">
                Lọc
            </button>
            <a href="{{ route('admin.products.index') }}"
                class="w-full text-center bg-stone-100 text-stone-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">
                <i class="fas fa-redo mr-1"></i>
                Đặt lại
            </a>
            <a href="{{ route('admin.products.create') }}"
                class="col-span-2 sm:col-span-1 inline-flex items-center justify-center bg-red-50 text-red-900 border border-red-200 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 hover:text-white hover:border-red-800 transition-all shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i> Thêm Áo Dài
            </a>
        </div>
    </form>
</div>

    

<div id="product-table-container"
     class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">

    <div class="product-table-wrap">
    <table class="product-table w-full table-fixed text-left border-collapse text-xs">
        <thead>
            <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">
                <th class="w-[17%] px-2 py-3">Tên sản phẩm</th>
                <th class="w-[18%] px-2 py-3">Mô tả</th>
                <th class="w-[11%] px-1 py-3 text-center">Màu sắc</th>
                <th class="w-[8%] px-1 py-3 text-center">Size</th>
                <th class="w-[8%] px-1 py-3 text-center">Số lượng</th>
                <th class="w-[14%] px-1 py-3 text-center">Trạng thái</th>
                <th class="w-[11%] px-1 py-3 text-center">Ngày tạo</th>
                <th class="w-[13%] px-1 py-3 text-center">Thao tác</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-red-50">
            @forelse($products as $product)

                @php
                    $colorsText = $product->chiTietSanPham
                        ->map(fn($item) => $item->color?->TenMau)
                        ->filter()
                        ->unique()
                        ->implode(', ');

                    $sizesText = $product->chiTietSanPham
                        ->map(fn($item) => $item->size?->TenSize)
                        ->filter()
                        ->unique()
                        ->implode(', ');

                    $totalQuantity = $product->chiTietSanPham->sum('SoLuong');
                @endphp

                <tr class="product-card hover:bg-red-50/40 transition">

                    {{-- Tên sản phẩm --}}
                    <td data-label="Tên sản phẩm" class="px-2 py-3 align-middle">
                        <p class="font-bold text-red-900 uppercase text-[11px] break-words">
                            {{ $product->TenSanPham }}
                        </p>
                        <p class="text-[10px] text-stone-400 mt-1">
                            #{{ $product->MaSanPham }}
                        </p>
                    </td>

                    {{-- Mô tả --}}
                    <td data-label="Mô tả" class="px-2 py-3 align-middle">
                        <p class="text-[11px] text-stone-600 line-clamp-2 break-words"
                           title="{{ $product->MoTa }}">
                            {{ $product->MoTa ?: 'Chưa có mô tả' }}
                        </p>
                    </td>

                    {{-- Màu sắc --}}
                    <td data-label="Màu sắc" class="px-1 py-3 text-center align-middle">
                        <span class="text-[11px] text-stone-700 break-words">
                            {{ $colorsText ?: 'Chưa xác định' }}
                        </span>
                    </td>

                    {{-- Size --}}
                    <td data-label="Size" class="px-1 py-3 text-center align-middle">
                        <span class="text-[11px] text-stone-700 break-words">
                            {{ $sizesText ?: 'Chưa có size' }}
                        </span>
                    </td>

                    {{-- Số lượng --}}
                    <td data-label="Số lượng" class="px-1 py-3 text-center align-middle">
                        <span class="font-semibold text-stone-700 text-xs">
                            {{ $totalQuantity }}
                        </span>
                    </td>

                    {{-- Trạng thái --}}
                    <td data-label="Trạng thái" class="px-1 py-3 text-center align-middle">
                        @if($product->TrangThai == 1)
                            <span class="inline-flex items-center justify-center gap-1 px-1.5 py-1.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-green-600"></span>
                                <span>Đang kinh doanh</span>
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center gap-1 px-1.5 py-1.5 rounded-full text-[9px] font-bold bg-stone-100 text-stone-500 border border-stone-200">
                                <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-stone-400"></span>
                                <span>Ngừng kinh doanh</span>
                            </span>
                        @endif
                    </td>

                    {{-- Ngày tạo --}}
                    <td data-label="Ngày tạo" class="px-1 py-3 text-center align-middle">
                        <span class="text-[10px] text-stone-500 whitespace-nowrap">
                            {{ $product->NgayTao ? date('d/m/Y', strtotime($product->NgayTao)) : '---' }}
                        </span>
                    </td>

                    {{-- Thao tác --}}
                    <td data-label="Thao tác" class="px-1 py-3 align-middle">
                        <div class="flex justify-center items-center gap-1">

                            {{-- Sửa sản phẩm --}}
                            <a href="{{ route('admin.products.edit', $product->MaSanPham) }}"
                                title="Sửa sản phẩm"
                                class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>

                            {{-- Xóa sản phẩm --}}
                            <button type="button" onclick="confirmDeleteProduct(this.dataset.id, this.dataset.name)" data-id="{{ $product->MaSanPham }}" data-name="{{ $product->TenSanPham }}"
                                title="Xóa sản phẩm"
                                class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-600 hover:bg-red-700 hover:text-white transition">
                                <i class="fas fa-trash-alt text-[10px]"></i>
                            </button>

                        </div>
                    </td>
                </tr>

            @empty
                <tr class="product-empty-row">
                    <td colspan="8" class="px-4 py-8 text-center text-stone-400 italic">
                        Không tìm thấy sản phẩm nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>


    <div class="p-4 border-t border-stone-100">
        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '<span class="serif text-2xl">Thành công!</span>',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false,
            customClass: {
                popup: 'glass-popup'
            }
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '<span class="serif text-2xl text-red-800">Thông báo lỗi</span>',
            text: @json(session('error')),
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#991b1b',
            buttonsStyling: false,
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-delete-confirm'
            }
        });
    @endif
    
    function refreshTable() {
        const container = document.getElementById('full-container'); 
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const updated = doc.getElementById('full-container');
                if (updated && container) container.innerHTML = updated.innerHTML;
            })
            .catch(error => console.warn('Lỗi khi làm mới bảng:', error));
    }
    function confirmDeleteProduct(id, name) {
        Swal.fire({
            title: '<span class="serif">Xác nhận xóa bỏ</span>',
            html: `<div class="mt-3 text-stone-500 text-sm">Mẫu thiết kế <b class="text-stone-900">"${name}"</b> sẽ bị xóa khỏi hệ thống. <br><span class="text-red-600 font-bold">Lưu ý: Không thể hoàn tác hành động này!</span></div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ',
            buttonsStyling: false, 
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-delete-confirm',
                cancelButton: 'btn-delete-cancel'
            },
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/admin/products/delete/${id}`, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message) });
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Lỗi: ${error.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value?.success) {
                Swal.fire({
                    icon: 'success',
                    title: '<span class="serif text-2xl">Thành công!</span>',
                    text: result.value.message,
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'glass-popup' }
                });

                refreshTable();
            }
        });
    }   
</script>

<style>
    .serif { font-family: 'Playfair Display', serif; }
    .admin-products-page { width: 100%; max-width: 100%; min-width: 0; }
    .admin-products-page .product-table-wrap { width: 100%; overflow-x: hidden; }
    .admin-products-page .product-table { width: 100%; min-width: 0; table-layout: fixed; }
    .admin-products-page .product-table th,
    .admin-products-page .product-table td { box-sizing: border-box; overflow-wrap: anywhere; word-break: normal; }
    .admin-products-page .product-table th { white-space: normal; line-height: 1.4; }
    .admin-products-page .product-table td { font-size: 10px; }
    .admin-products-page .product-table td[data-label="Trạng thái"] span { max-width: 100%; white-space: normal; }
    @media (min-width: 768px) and (max-width: 1100px) {
        .admin-products-page .product-table th { font-size: 9px; padding-left: 3px; padding-right: 3px; }
        .admin-products-page .product-table td { padding-left: 3px; padding-right: 3px; }
        .admin-products-page .product-table td[data-label="Thao tác"] > div { gap: 3px; }
    }
    @media (max-width: 767px) {
        .admin-products-page .product-table-wrap { overflow: visible; padding: 10px; background: #fafaf9; }
        .admin-products-page .product-table { display: block; width: 100%; min-width: 0 !important; table-layout: auto; }
        .admin-products-page .product-table thead { display: none; }
        .admin-products-page .product-table tbody { display: grid; gap: 12px; }
        .admin-products-page .product-table .product-card {
            display: block; width: 100%; padding: 7px 13px;
            background: white; border: 1px solid #fee2e2; border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,.035);
        }
        .admin-products-page .product-table .product-card td {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 10px; width: 100%; padding: 11px 0 !important;
            border-bottom: 1px solid #f5f5f4; text-align: right; white-space: normal;
        }
        .admin-products-page .product-table .product-card td:last-child { border-bottom: 0; }
        .admin-products-page .product-table .product-card td::before {
            content: attr(data-label); flex: 0 0 35%; text-align: left;
            font-size: 11px; font-weight: 700; color: #78716c;
        }
        .admin-products-page .product-table .product-card td > * { max-width: 63%; min-width: 0; overflow-wrap: anywhere; }
        .admin-products-page .product-table .product-card td[data-label="Thao tác"] > div { justify-content: flex-end; }
        .admin-products-page .product-table .product-card td[data-label="Mô tả"] p { display: block; -webkit-line-clamp: unset; overflow: visible; }
        .admin-products-page .product-table .product-empty-row { display: block; }
        .admin-products-page .product-table .product-empty-row td { display: block; width: 100%; }
    }
    @media (max-width: 380px) {
        .admin-products-page .product-table .product-card td::before { flex-basis: 40%; }
        .admin-products-page .product-table .product-card td > * { max-width: 58%; }
    }
    .glass-popup {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        border-radius: 24px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .btn-delete-confirm {
    background-color: #991b1b !important;
    color: white !important;
    padding: 10px 25px !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-size: 11px !important;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-left: 10px;
    }
    .btn-delete-cancel {
        background-color: #f3f4f6 !important; color: #4b5563 !important;
        padding: 10px 25px !important; border-radius: 12px !important;
        font-weight: 600 !important; font-size: 11px !important;
    }
</style>
@endsection
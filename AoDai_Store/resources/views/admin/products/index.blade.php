@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm Áo Dài')

@section('content')
<div class="w-full max-w-full min-w-0 space-y-4 admin-products-page" id="full-container">
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
        <x-admin.stat-card label="Tổng sản phẩm" :value="$totalProducts" icon="fas fa-shirt" />
        <x-admin.stat-card label="Đang hoạt động" :value="$activeProducts" icon="fas fa-circle-check" color="green" />
        <x-admin.stat-card label="Ngừng kinh doanh" :value="max(0, $totalProducts - $activeProducts)" icon="fas fa-ban" color="slate" />
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
     class="w-full max-w-full bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">

    <div class="product-table-wrap">
    <table class="product-table w-full table-fixed text-left border-collapse text-sm">
        <thead>
            <tr class="bg-red-50 text-red-900 text-xs uppercase font-bold">
                <th scope="col" class="w-[21%] font-semibold">Sản phẩm</th>
                <th scope="col" class="w-[10%] font-semibold">Chất liệu</th>
                <th scope="col" class="w-[22%] font-semibold">Màu sắc &amp; Size</th>
                <th scope="col" class="w-[9%] font-semibold text-center">Tồn kho</th>
                <th scope="col" class="w-[17%] font-semibold">Trạng thái</th>
                <th scope="col" class="w-[11%] font-semibold">Ngày tạo</th>
                <th scope="col" class="w-[10%] font-semibold text-center">Thao tác</th>
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
                    $categoryNames = $product->danhMucs
                        ->where('IsDeleted', false)
                        ->pluck('TenDanhMuc')
                        ->filter()
                        ->unique()
                        ->implode(', ');
                @endphp

                <tr class="product-card hover:bg-red-50/40 transition-colors">
                    <td>
                        <p class="font-semibold text-red-900">
                            {{ $product->TenSanPham }}
                        </p>
                        <p class="mt-0.5 text-xs text-stone-600">
                            #{{ $product->MaSanPham }}@if($categoryNames) · {{ $categoryNames }}@endif
                        </p>
                    </td>

                    <td data-label="Chất liệu">
                        <span class="text-stone-800">
                            {{ $product->chatlieu?->TenChatLieu ?: 'Chưa cập nhật' }}
                        </span>
                    </td>

                    <td data-label="Màu sắc &amp; Size">
                        <div>
                            <p class="text-stone-800">{{ $colorsText ?: 'Chưa xác định màu' }}</p>
                            <p class="mt-0.5 text-xs text-stone-600">Size: {{ $sizesText ?: 'Chưa cập nhật' }}</p>
                        </div>
                    </td>

                    <td data-label="Tồn kho" class="text-center">
                        <span class="font-bold text-stone-900 text-base tabular-nums">
                            {{ $totalQuantity }}
                        </span>
                    </td>

                    <td data-label="Trạng thái">
                        <span @class([
                            'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border',
                            'bg-green-50 text-green-700 border-green-100' => $product->TrangThai,
                            'bg-stone-100 text-stone-500 border-stone-200' => !$product->TrangThai,
                        ])>
                            <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-current" aria-hidden="true"></span>
                            {{ $product->TrangThai ? 'Đang kinh doanh' : 'Ngừng kinh doanh' }}
                        </span>
                    </td>

                    <td data-label="Ngày tạo">
                        <span class="text-xs text-stone-700 whitespace-nowrap tabular-nums">
                            {{ $product->NgayTao?->format('d/m/Y') ?? '—' }}
                        </span>
                    </td>

                    <td data-label="Thao tác">
                        <div class="flex justify-center items-center gap-1">
                            <a href="{{ route('admin.products.edit', $product->MaSanPham) }}"
                                title="Sửa sản phẩm" aria-label="Sửa {{ $product->TenSanPham }}"
                                class="w-7 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-800 border border-red-200 hover:bg-red-800 hover:text-white transition-colors">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>

                            <button type="button" onclick="confirmDeleteProduct(this.dataset.id, this.dataset.name)" data-id="{{ $product->MaSanPham }}" data-name="{{ $product->TenSanPham }}"
                                title="Xóa sản phẩm" aria-label="Xóa {{ $product->TenSanPham }}"
                                class="w-7 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-800 border border-red-200 hover:bg-red-800 hover:text-white transition-colors">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                            </button>
                        </div>
                    </td>
                </tr>

            @empty
                <tr class="product-empty-row">
                    <td colspan="7" class="px-4 py-8 text-center text-stone-600">
                        Không tìm thấy sản phẩm nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>


    @if($products->hasPages())
    <div class="admin-pagination px-4 py-3 border-t border-red-100 bg-red-50">
        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
    </div>
    @endif
</div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
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
        confirmNotification({
            title: 'Xác nhận xóa bỏ',
            text: `Mẫu thiết kế "${name}" sẽ bị xóa khỏi hệ thống. Hành động này không thể hoàn tác.`,
            icon: 'warning',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ',
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
                showNotification({
                    icon: 'success',
                    title: 'Thành công!',
                    text: result.value.message
                });

                refreshTable();
            }
        });
    }   
</script>

<style>
    .admin-products-page .product-table-wrap { width: 100%; overflow-x: auto; }
    .admin-products-page .product-table { min-width: 800px; }
    .admin-products-page .product-table th,
    .admin-products-page .product-table .product-card td {
        padding: 10px;
        vertical-align: middle;
        overflow-wrap: anywhere;
    }
    .admin-products-page .product-table th:first-child,
    .admin-products-page .product-table .product-card td:first-child { padding-left: 14px; }
    @media (max-width: 767px) {
        .admin-products-page .product-table-wrap { overflow: visible; padding: 10px; background: #fafaf9; }
        .admin-products-page .product-table { display: block; min-width: 0; }
        .admin-products-page .product-table thead { display: none; }
        .admin-products-page .product-table tbody { display: grid; gap: 12px; }
        .admin-products-page .product-table .product-card {
            display: block; width: 100%; padding: 7px 13px;
            background: white; border: 1px solid #fee2e2; border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,.035);
        }
        .admin-products-page .product-table .product-card td {
            display: grid; grid-template-columns: 40% minmax(0, 1fr); align-items: center;
            gap: 8px; padding: 8px 0;
            border-bottom: 1px solid #f5f5f4; text-align: right;
        }
        .admin-products-page .product-table .product-card td:first-child {
            display: block; padding: 8px 0; text-align: left;
        }
        .admin-products-page .product-table .product-card td:last-child { border-bottom: 0; }
        .admin-products-page .product-table .product-card td[data-label]::before {
            content: attr(data-label); text-align: left;
            font-size: 11px; font-weight: 700; color: #78716c;
        }
        .admin-products-page .product-table .product-card td > * { min-width: 0; justify-self: end; }
        .admin-products-page .product-table .product-empty-row { display: block; }
        .admin-products-page .product-table .product-empty-row td { display: block; width: 100%; }
    }
</style>
@endsection

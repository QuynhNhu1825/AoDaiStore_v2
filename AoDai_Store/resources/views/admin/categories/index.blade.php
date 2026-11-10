@extends('layouts.admin')

@section('title', 'Danh sách Danh mục Áo Dài')

@section('content')
<div id="category-page-container" class="categories-page w-full max-w-full min-w-0 space-y-4">
    <div class="space-y-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="relative w-full lg:flex-1 lg:max-w-md min-w-0">
                    <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" placeholder="Tìm tên danh mục, mã danh mục..." value="{{ request('search') }}"
                        class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all">
                </div>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full lg:w-auto lg:justify-end">
                <button type="submit" class="w-full sm:w-auto bg-red-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-filter mr-1"></i> Lọc
                </button>

                <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto text-center bg-stone-100 text-stone-600 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">Đặt lại</a>
                <a href="{{ route('admin.categories.create') }}" class="col-span-2 sm:col-span-1 w-full sm:w-auto inline-flex items-center justify-center bg-red-50 text-red-900 border border-red-200 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 hover:text-white hover:border-red-800 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i> Thêm Danh Mục
                </a>
                </div>
            </form>
        </div>

        <div id="category-table-container" class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
            <div class="overflow-x-auto categories-table-wrap">
                <table class="category-responsive-table w-full min-w-[850px] table-fixed text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">
                            <th class="px-3 py-3">Tên danh mục</th>
                            <th class="px-3 py-3">Mô tả</th>
                            <th class="px-3 py-3 text-center">Sản phẩm</th>
                            <th class="px-3 py-3 text-center">Ngày tạo</th>
                            <th class="px-3 py-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($categories as $category)
                            <tr class="category-row hover:bg-red-50/40 transition group">
                                <td data-label="Danh mục" class="px-3 py-3">
                                    <div class="flex flex-col">
                                        <p class="font-bold text-red-900 uppercase text-[11px] break-words">
                                            {{ $category->TenDanhMuc }}
                                        </p>
                                        <p class="text-[10px] text-stone-400 font-mono mt-0.5">#{{ $category->MaDanhMuc }}</p>
                                    </div>
                                </td>
                                <td data-label="Mô tả" class="px-3 py-3 text-xs text-stone-600">
                                    <span class="break-words">{{ $category->MoTa ?: 'Chưa có mô tả' }}</span>
                                </td>
                                <td data-label="Sản phẩm" class="px-3 py-3 text-center">
                                    <span class="px-3 py-1 bg-stone-50 text-stone-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-stone-100">
                                        {{ $category->products_count ?? 0 }} sản phẩm
                                    </span>
                                </td>
                                <td data-label="Ngày tạo" class="px-3 py-3 text-center text-[10px] text-stone-500 font-medium">
                                    {{ $category->NgayTao ? date('d/m/Y', strtotime($category->NgayTao)) : '---' }}
                                </td>
                                <td data-label="Thao tác" class="px-3 py-3">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category->MaDanhMuc) }}"
   title="Sửa danh mục"
   class="w-7 h-7 flex items-center justify-center
          rounded-md border border-red-200 text-red-800
          hover:bg-red-800 hover:text-white transition-all">
    <i class="fas fa-edit text-[10px]"></i>
</a>

                                        {{-- NÚT XÓA DANH MỤC --}}
<button
    type="button"
    onclick="confirmDelete(@js($category->MaDanhMuc), @js($category->TenDanhMuc))"
    title="Xóa danh mục"
    aria-label="Xóa danh mục"
    class="w-7 h-7 flex items-center justify-center
           rounded-md border border-red-200 text-red-600
           hover:bg-red-700 hover:text-white
           transition-all duration-200"
>
    <i class="fas fa-trash-alt text-[10px]"></i>
</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-stone-400 italic">
                                    <i class="fas fa-folder-open fa-2x mb-3 opacity-20 block text-center"></i>
                                    Không tìm thấy danh mục nào phù hợp.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($categories, 'links'))
            <div class="p-4 border-t border-stone-100">
                {{ $categories->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '<span class="serif text-2xl">Thành công!</span>',
        text: @json(session('success')),
        timer: 2000,
        showConfirmButton: false,
        customClass: { popup: 'glass-popup' }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: '<span class="serif text-2xl text-red-800">Thông báo lỗi</span>',
        text: @json(session('error')),
        confirmButtonText: 'Đóng',
        buttonsStyling: false,
        customClass: {
            popup: 'glass-popup',
            confirmButton: 'btn-delete-confirm'
        }
    });
    @endif

    function refreshTable() {
        const container = document.getElementById('category-page-container');

        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const updated = doc.getElementById('category-page-container');
                if (updated) container.innerHTML = updated.innerHTML;
            });
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, char => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        })[char]);
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: '<span class="serif">Xác nhận xóa bỏ</span>',
            html: `
                <div class="mt-3 text-stone-500 text-sm">
                    Danh mục <b class="text-stone-900 italic">"${escapeHtml(name)}"</b> sẽ bị xóa khỏi hệ thống.
                    <br>
                    <span class="text-red-600 font-bold">Danh mục chỉ được xóa khi không còn sản phẩm liên kết; dữ liệu được lưu dưới dạng xóa mềm.</span>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-delete-confirm',
                cancelButton: 'btn-delete-cancel'
            }
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch(`/admin/categories/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Có lỗi xảy ra.');
                return data;
            })
            .then(data => {
                if (!data.success) throw new Error(data.message);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Đã xóa!',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'glass-popup' }
                });

                refreshTable();
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể xóa',
                    text: err.message,
                    customClass: { popup: 'glass-popup' }
                });
            });
        });
    }
    </script>


    <style>
        .serif { font-family: 'Playfair Display', serif; }
        .categories-page { min-width: 0; }
        @media (max-width: 767px) {
            .categories-page .categories-table-wrap {
                overflow: visible;
                background: #fafaf9;
                padding: 10px;
            }
            .categories-page .category-responsive-table {
                display: block;
                width: 100%;
                min-width: 0 !important;
                table-layout: auto;
            }
            .categories-page .category-responsive-table thead { display: none; }
            .categories-page .category-responsive-table tbody {
                display: grid;
                gap: 12px;
            }
            .categories-page .category-responsive-table .category-row {
                display: block;
                width: 100%;
                background: white;
                border: 1px solid #fee2e2;
                border-radius: 14px;
                padding: 6px 13px;
                box-shadow: 0 2px 8px rgba(0,0,0,.035);
            }
            .categories-page .category-responsive-table .category-row td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                width: 100%;
                padding: 11px 0 !important;
                border-bottom: 1px solid #f5f5f4;
                text-align: right;
                overflow-wrap: anywhere;
            }
            .categories-page .category-responsive-table .category-row td:last-child { border-bottom: 0; }
            .categories-page .category-responsive-table .category-row td::before {
                content: attr(data-label);
                flex: 0 0 35%;
                font-size: 11px;
                font-weight: 700;
                color: #78716c;
                text-align: left;
            }
            .categories-page .category-responsive-table .category-row td > * {
                min-width: 0;
                max-width: 63%;
            }
            .categories-page .category-responsive-table .category-row td[data-label="Mô tả"] span {
                white-space: normal;
                overflow-wrap: anywhere;
            }
            .categories-page .category-responsive-table .category-row td[data-label="Thao tác"] > div {
                justify-content: flex-end;
                flex-wrap: wrap;
                gap: 8px;
            }
            .categories-page .category-responsive-table .category-row td[data-label="Thao tác"] a,
            .categories-page .category-responsive-table .category-row td[data-label="Thao tác"] button {
                min-height: 36px;
            }
            .categories-page .category-responsive-table tbody tr:not(.category-row) { display: block; }
            .categories-page .category-responsive-table tbody tr:not(.category-row) td { display: block; width: 100%; }
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
            margin-left: 10px !important;
            font-size: 12px !important;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s;
        }
        .btn-delete-confirm:hover { 
            background-color: #991b1b !important; 
            transform: translateY(-2px); 
        }
        .btn-delete-cancel {
            background-color: #f3f4f6 !important;
            color: #4b5563 !important;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            text-transform: uppercase;
            transition: all 0.3s;
        }
    </style>
@endsection
@extends('layouts.admin')

@section('title', 'Quản lý Chất liệu')

@section('content')

<div class="materials-page w-full max-w-full min-w-0 space-y-4" id="full-container">

    {{-- ================= TÌM KIẾM + THÊM ================= --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">

        <form action="{{ route('admin.materials.index') }}"
              method="GET"
              class="flex flex-col lg:flex-row lg:items-center gap-3">

            <div class="relative w-full lg:flex-1 lg:max-w-md min-w-0">

                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>

                <input
                    type="text"
                    name="search"
                    placeholder="Tìm tên hoặc xuất xứ chất liệu..."
                    value="{{ request('search') }}"
                    class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all"
                >

            </div>

            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full lg:w-auto lg:justify-end">

                <button type="submit"
                    class="w-full sm:w-auto bg-red-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">

                    <i class="fas fa-filter mr-1"></i>
                    Lọc

                </button>

                 <a href="{{ route('admin.materials.index') }}"
                    class="w-full sm:w-auto text-center bg-stone-100 text-stone-600 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">
                    <i class="fas fa-redo mr-1"></i>
                    Đặt lại
                </a>

               

                <a href="{{ route('admin.materials.create') }}"
                   class="col-span-2 sm:col-span-1 w-full sm:w-auto inline-flex items-center justify-center bg-red-50 text-red-900 border border-red-200 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 hover:text-white hover:border-red-800 transition-all shadow-sm whitespace-nowrap">

                    <i class="fas fa-plus mr-1.5"></i>
                    Thêm chất liệu

                </a>

            </div>

        </form>

    </div>

    {{-- ================= THÔNG BÁO ================= --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl text-xs">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl text-xs">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ================= BẢNG CHẤT LIỆU ================= --}}
    <div id="material-table-container"
         class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">

        <div class="overflow-x-auto materials-table-wrap">

            <table class="materials-table w-full min-w-[850px] table-fixed text-left border-collapse text-xs">

                <thead>
                    <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">

                        <th class="w-[20%] px-3 py-3">
                            Chất liệu
                        </th>

                        <th class="w-[17%] px-2 py-3">
                            Xuất xứ
                        </th>

                        <th class="w-[33%] px-3 py-3">
                            Hướng dẫn bảo quản
                        </th>

                        <th class="w-[15%] px-2 py-3 text-center">
                            Trạng thái
                        </th>

                        <th class="w-[15%] px-2 py-3 text-center">
                            Thao tác
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-red-50">

                    @forelse($materials as $item)

                        <tr class="material-card hover:bg-red-50/40 transition">

                            {{-- CHẤT LIỆU --}}
                            <td data-label="Chất liệu" class="px-3 py-3 align-middle">

                                <p class="font-bold text-red-900 uppercase text-[11px] break-words">
                                    {{ $item->TenChatLieu }}
                                </p>

                                <p class="text-[10px] text-stone-400 mt-1">
                                    #{{ $item->MaChatLieu }}
                                </p>

                            </td>

                            {{-- XUẤT XỨ --}}
                            <td data-label="Xuất xứ" class="px-2 py-3 align-middle">

                                <span class="text-[11px] text-stone-600 break-words">
                                    {{ $item->XuatXu ?: '---' }}
                                </span>

                            </td>

                            {{-- HƯỚNG DẪN BẢO QUẢN --}}
                            <td data-label="Hướng dẫn bảo quản" class="px-3 py-3 align-middle">

                                <p class="text-[11px] text-stone-600 line-clamp-2 break-words"
                                   title="{{ $item->HuongDanBaoQuan }}">

                                    {{ $item->HuongDanBaoQuan ?: 'Chưa có hướng dẫn bảo quản' }}

                                </p>

                            </td>

                            {{-- TRẠNG THÁI --}}
                            <td data-label="Trạng thái" class="px-2 py-3 text-center align-middle">

                                @if(!$item->IsDeleted)

                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-1.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-100 whitespace-nowrap">

                                        <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-green-600"></span>

                                        Đang sử dụng

                                    </span>

                                @else

                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-1.5 rounded-full text-[9px] font-bold bg-stone-100 text-stone-500 border border-stone-200 whitespace-nowrap">

                                        <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-stone-400"></span>

                                        Đã xóa

                                    </span>

                                @endif

                            </td>

                            {{-- THAO TÁC --}}
                            <td data-label="Thao tác" class="px-2 py-3 align-middle">

                                <div class="flex justify-center items-center gap-1">

                                    {{-- SỬA --}}
                                    <a href="{{ route('admin.materials.edit', $item->MaChatLieu) }}"
                                       title="Sửa chất liệu"
                                       class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition">

                                        <i class="fas fa-edit text-[10px]"></i>

                                    </a>

                                    {{-- NÚT XÓA --}}
<button
    type="button"
    onclick="confirmDelete(this)"
    data-id="{{ $item->MaChatLieu }}"
    data-name="{{ $item->TenChatLieu }}"
    title="Xóa chất liệu"
    class="w-7 h-7 flex items-center justify-center
           rounded-md border border-red-200 text-red-600
           hover:bg-red-700 hover:text-white transition"
>
    <i class="fas fa-trash-alt text-[10px]"></i>
</button>

{{-- FORM XÓA --}}
<form
    id="delete-form-{{ $item->MaChatLieu }}"
    action="{{ route('admin.materials.destroy', $item->MaChatLieu) }}"
    method="POST"
    class="hidden"
>
    @csrf
    @method('DELETE')
</form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr class="material-empty-row">
                            <td colspan="5" class="px-4 py-10 text-center text-stone-400 italic">

                                <i class="fas fa-layer-group text-2xl mb-2"></i>

                                <p class="text-xs">
                                    Không tìm thấy chất liệu nào.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- ================= PHÂN TRANG ================= --}}
    @if(method_exists($materials, 'links'))

        <div class="p-4 border-t border-stone-100">
            {{ $materials->appends(request()->query())->links('pagination::tailwind') }}
        </div>

    @endif

</div>

{{-- ================= SWEETALERT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

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
            customClass: {
                popup: 'glass-popup'
            }
        });

    @endif

    // XÁC NHẬN XÓA
    function confirmDelete(button) {
    const id = button.dataset.id;
    const name = button.dataset.name;
    const form = document.getElementById(`delete-form-${id}`);

    if (!form) {
        console.error('Không tìm thấy form xóa:', id);
        return;
    }

    Swal.fire({
        title: 'Xác nhận xóa chất liệu',
        text: `Bạn có chắc muốn xóa chất liệu "${name}" không?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy bỏ',
        confirmButtonColor: '#991b1b',
        cancelButtonColor: '#78716c',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

    // TRÁNH CHÈN HTML KHÔNG AN TOÀN
    function escapeHtml(value) {

        return String(value).replace(/[&<>"']/g, function (char) {

            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];

        });

    }

</script>

{{-- ================= STYLE ================= --}}
<style>
    /* Mobile: bảng chuyển thành các thẻ, không cuộn ngang */
    .materials-page { min-width: 0; }
    @media (max-width: 767px) {
        .materials-page .materials-table-wrap {
            overflow: visible;
            background: #fafaf9;
            padding: 10px;
        }
        .materials-page .materials-table {
            display: block;
            width: 100%;
            min-width: 0 !important;
            table-layout: auto;
        }
        .materials-page .materials-table thead { display: none; }
        .materials-page .materials-table tbody {
            display: grid;
            gap: 12px;
        }
        .materials-page .materials-table .material-card {
            display: block;
            width: 100%;
            background: white;
            border: 1px solid #fee2e2;
            border-radius: 14px;
            padding: 6px 13px;
            box-shadow: 0 2px 8px rgba(0,0,0,.035);
        }
        .materials-page .materials-table .material-card td {
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
        .materials-page .materials-table .material-card td:last-child { border-bottom: 0; }
        .materials-page .materials-table .material-card td::before {
            content: attr(data-label);
            flex: 0 0 35%;
            font-size: 11px;
            font-weight: 700;
            color: #78716c;
            text-align: left;
        }
        .materials-page .materials-table .material-card td > * {
            min-width: 0;
            max-width: 63%;
        }
        .materials-page .materials-table .material-card td[data-label="Hướng dẫn bảo quản"] p {
            display: block;
            overflow: visible;
            -webkit-line-clamp: unset;
        }
        .materials-page .materials-table .material-card td[data-label="Thao tác"] > div {
            justify-content: flex-end;
        }
        .materials-page .materials-table .material-card td[data-label="Thao tác"] a,
        .materials-page .materials-table .material-card td[data-label="Thao tác"] button {
            width: 36px;
            height: 36px;
        }
        .materials-page .materials-table .material-empty-row { display: block; }
        .materials-page .materials-table .material-empty-row td {
            display: block;
            width: 100%;
        }
    }


    .serif {
        font-family: 'Playfair Display', serif;
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
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        padding: 10px 25px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }

</style>

@endsection

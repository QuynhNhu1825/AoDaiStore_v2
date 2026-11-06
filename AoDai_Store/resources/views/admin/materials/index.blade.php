
@extends('layouts.admin')

@section('title', 'Quản lý Chất liệu')

@section('content')

<div class="w-full max-w-full space-y-4" id="full-container">

    {{-- ================= THỐNG KÊ ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex items-center">
            <div class="p-3 bg-red-50 rounded-xl text-red-800 mr-4">
                <i class="fas fa-layer-group fa-lg"></i>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                    Tổng chất liệu
                </p>

                <p class="text-xl font-bold text-red-900">
                    {{ $totalMaterials ?? $materials->count() }}
                </p>
            </div>
        </div>

    </div>

    {{-- ================= TÌM KIẾM + THÊM ================= --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">

        <form action="{{ route('admin.materials.index') }}"
              method="GET"
              class="flex flex-col md:flex-row items-center gap-3">

            <div class="relative flex-1 max-w-[250px] min-w-[150px]">

                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>

                <input
                    type="text"
                    name="search"
                    placeholder="Tìm chất liệu..."
                    value="{{ request('search') }}"
                    class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all"
                >

            </div>

            <div class="flex items-center gap-2 flex-grow md:justify-end">

                <button type="submit"
                    class="bg-red-800 text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">

                    <i class="fas fa-filter mr-1"></i>
                    Lọc

                </button>

                 <a href="{{ route('admin.materials.index') }}"
                    class="bg-stone-100 text-stone-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">
                    <i class="fas fa-redo mr-1"></i>
                    Đặt lại
                </a>

               

                <a href="{{ route('admin.materials.create') }}"
                   class="inline-flex items-center justify-center bg-red-50 text-red-900 border border-red-200 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 hover:text-white hover:border-red-800 transition-all shadow-sm whitespace-nowrap">

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

        <div class="overflow-x-auto">

            <table class="w-full table-fixed text-left border-collapse text-xs">

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

                        <tr class="hover:bg-red-50/40 transition">

                            {{-- CHẤT LIỆU --}}
                            <td class="px-3 py-3 align-middle">

                                <p class="font-bold text-red-900 uppercase text-[11px] break-words">
                                    {{ $item->TenChatLieu }}
                                </p>

                                <p class="text-[10px] text-stone-400 mt-1">
                                    #{{ $item->MaChatLieu }}
                                </p>

                            </td>

                            {{-- XUẤT XỨ --}}
                            <td class="px-2 py-3 align-middle">

                                <span class="text-[11px] text-stone-600 break-words">
                                    {{ $item->XuatXu ?: '---' }}
                                </span>

                            </td>

                            {{-- HƯỚNG DẪN BẢO QUẢN --}}
                            <td class="px-3 py-3 align-middle">

                                <p class="text-[11px] text-stone-600 line-clamp-2 break-words"
                                   title="{{ $item->HuongDanBaoQuan }}">

                                    {{ $item->HuongDanBaoQuan ?: 'Chưa có hướng dẫn bảo quản' }}

                                </p>

                            </td>

                            {{-- TRẠNG THÁI --}}
                            <td class="px-2 py-3 text-center align-middle">

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
                            <td class="px-2 py-3 align-middle">

                                <div class="flex justify-center items-center gap-1">

                                    {{-- SỬA --}}
                                    <a href="{{ route('admin.materials.edit', $item->MaChatLieu) }}"
                                       title="Sửa chất liệu"
                                       class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition">

                                        <i class="fas fa-edit text-[10px]"></i>

                                    </a>

                                    {{-- XÓA --}}
                                    <button
                                        type="button"
                                        onclick="confirmDelete({{ $item->MaChatLieu }}, @json($item->TenChatLieu))"
                                        title="Xóa chất liệu"
                                        class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-600 hover:bg-red-700 hover:text-white transition">

                                        <i class="fas fa-trash-alt text-[10px]"></i>

                                    </button>

                                    {{-- FORM XÓA --}}
                                    <form
                                        id="delete-form-{{ $item->MaChatLieu }}"
                                        action="{{ route('admin.materials.destroy', $item->MaChatLieu) }}"
                                        method="POST"
                                        class="hidden">

                                        @csrf
                                        @method('DELETE')

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
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
    function confirmDelete(id, name) {

        Swal.fire({

            title: '<span class="serif">Xác nhận xóa bỏ</span>',

            html: `
                <div class="mt-3 text-stone-500 text-sm">
                    Bạn có chắc muốn xóa chất liệu
                    <b class="text-stone-900">"${escapeHtml(name)}"</b>?
                    <br>
                    <span class="text-red-600 font-bold">
                        Vui lòng kiểm tra trước khi xác nhận!
                    </span>
                </div>
            `,

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

            reverseButtons: true

        }).then((result) => {

            if (result.isConfirmed) {

                document.getElementById(`delete-form-${id}`).submit();

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

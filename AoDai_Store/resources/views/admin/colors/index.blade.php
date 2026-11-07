
@extends('layouts.admin')

@section('title', 'Quản lý Màu sắc')

@section('content')
<div class="w-full max-w-full space-y-4" id="full-container">

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex items-center">
            <div class="p-3 bg-red-50 rounded-xl text-red-800 mr-4">
                <i class="fas fa-palette fa-lg"></i>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                    Tổng số màu sắc
                </p>
                <p class="text-xl font-bold text-red-900">
                    {{ $totalColors }}
                </p>
            </div>
        </div>

    </div>

    {{-- TÌM KIẾM + THÊM --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">

        <form action="{{ route('admin.colors.index') }}"
              method="GET"
              class="flex flex-col md:flex-row items-center gap-3">

            <div class="relative flex-1 max-w-[200px] min-w-[150px]">

                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm kiếm..."
                    class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all"
                >

            </div>

            <div class="flex-grow">
                <span class="text-[11px] text-stone-400">
                    Tìm theo tên màu, mã màu hoặc mã HEX
                </span>
            </div>

            <div class="flex items-center gap-2 shrink-0">

                <button type="submit"
                    class="bg-red-800 text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-filter mr-1"></i>
                    Lọc
                </button>

                <a href="{{ route('admin.colors.index') }}"
                    class="bg-stone-100 text-stone-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">
                    <i class="fas fa-redo mr-1"></i>
                    Đặt lại
                </a>

                <a href="{{ route('admin.colors.create') }}"
                    class="inline-flex items-center justify-center bg-red-50 text-red-900 border border-red-200 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 hover:text-white hover:border-red-800 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-1.5"></i>
                    Thêm Màu
                </a>

            </div>

        </form>
    </div>

    {{-- BẢNG MÀU SẮC --}}
    <div class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">

        <table class="w-full table-fixed text-left border-collapse text-xs">

            <thead>
                <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">

                    <th class="w-[15%] px-3 py-3">
                        Mã màu
                    </th>

                    <th class="w-[30%] px-3 py-3">
                        Tên màu
                    </th>

                    <th class="w-[20%] px-2 py-3 text-center">
                        Hiển thị màu
                    </th>

                    <th class="w-[20%] px-2 py-3 text-center">
                        Ngày tạo
                    </th>

                    <th class="w-[15%] px-2 py-3 text-center">
                        Thao tác
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-red-50">

                @forelse($colors as $item)

                    <tr class="hover:bg-red-50/40 transition">

                        {{-- MÃ MÀU --}}
                        <td class="px-3 py-3 align-middle">
                            <span class="font-bold text-red-900 text-[11px]">
                                #{{ $item->MaMau }}
                            </span>
                        </td>

                        {{-- TÊN MÀU --}}
                        <td class="px-3 py-3 align-middle">
                            <p class="font-bold text-red-900 uppercase text-[11px] break-words">
                                {{ $item->TenMau }}
                            </p>
                        </td>

                        {{-- MÃ HEX --}}
                        <td class="px-2 py-3 text-center align-middle">

                            <div class="flex flex-col items-center gap-1">

                                <span
                                    class="w-7 h-7 rounded-md border border-stone-200 shadow-sm"
                                    style="background-color: {{ preg_match('/^#[0-9A-Fa-f]{6}$/', $item->MaHex ?? '') ? $item->MaHex : '#FFFFFF' }}">
                                </span>

                                <span class="text-[10px] text-stone-500 font-mono">
                                    {{ $item->MaHex ?: 'Chưa có mã HEX' }}
                                </span>

                            </div>

                        </td>

                        {{-- NGÀY TẠO --}}
                        <td class="px-2 py-3 text-center align-middle">
                            <span class="text-[10px] text-stone-500 whitespace-nowrap">
                                {{ $item->NgayTao ? date('d/m/Y', strtotime($item->NgayTao)) : '---' }}
                            </span>
                        </td>

                        {{-- THAO TÁC --}}
                        <td class="px-2 py-3 align-middle">

                            <div class="flex justify-center items-center gap-1">

                                <a href="{{ route('admin.colors.edit', $item->MaMau) }}"
                                   title="Sửa màu sắc"
                                   class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </a>

                                <button
                                    type="button"
                                    onclick="confirmDeleteColor('{{ $item->MaMau }}', @js($item->TenMau))"
                                    title="Xóa màu sắc"
                                    class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-600 hover:bg-red-700 hover:text-white transition">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stone-400 italic">
                            <i class="fas fa-palette text-2xl mb-2"></i>
                            <p>Không tìm thấy màu sắc nào.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- SWEETALERT --}}
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
            buttonsStyling: false,
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-delete-confirm'
            }
        });
    @endif

    // XÁC NHẬN XÓA MỀM
    function confirmDeleteColor(id, name) {

        Swal.fire({
            title: '<span class="serif">Xác nhận xóa bỏ</span>',

            html: `
                <div class="mt-3 text-stone-500 text-sm">
                    Màu sắc
                    <b class="text-stone-900">"${escapeHtml(name)}"</b>
                    sẽ được chuyển vào trạng thái đã xóa.
                    <br>
                    <span class="text-red-600 font-bold">
                        Dữ liệu vẫn được lưu trong hệ thống.
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

        }).then(result => {

            if (result.isConfirmed) {

                const form = document.createElement('form');

                form.method = 'POST';
                form.action = `/admin/colors/delete/${id}`;

                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;

                document.body.appendChild(form);
                form.submit();
            }

        });
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function(char) {
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

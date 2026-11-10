@extends('layouts.admin')

@section('title', 'Quản lý Kích cỡ')

@section('content')
<div class="size-admin-page w-full max-w-full min-w-0 space-y-4" id="full-container">

    {{-- TÌM KIẾM VÀ THÊM --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">

        <form action="{{ route('admin.sizes.index') }}"
              method="GET"
              class="flex flex-col lg:flex-row lg:items-center gap-3">

            <div class="relative w-full min-w-0 lg:flex-1 lg:max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>

                <input type="text"
                       name="search"
                       placeholder="Tìm kiếm theo tên kích cỡ"
                       value="{{ request('search') }}"
                       class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all">
            </div>

            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full lg:w-auto lg:shrink-0 size-actions">

                <button type="submit"
                        class="inline-flex items-center justify-center bg-red-800 text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-filter mr-1"></i>
                    Lọc
                </button>

                <a href="{{ route('admin.sizes.index') }}"
                   class="inline-flex items-center justify-center bg-stone-100 text-stone-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">
                    <i class="fas fa-redo mr-1"></i>
                    Đặt lại
                </a>

                <a href="{{ route('admin.sizes.create') }}"
                   class="col-span-2 sm:col-span-1 inline-flex items-center justify-center bg-red-50 text-red-900 border border-red-200 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 hover:text-white hover:border-red-800 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-1.5"></i>
                    Thêm Size
                </a>

            </div>
        </form>
    </div>

    {{-- BẢNG KÍCH CỠ --}}
    <div id="size-table-container"
         class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">

        <table class="size-table w-full table-fixed text-left border-collapse text-xs">

            <thead>
                <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">

                    <th class="w-[15%] px-3 py-3">
                        Mã Size
                    </th>

                    <th class="w-[30%] px-3 py-3">
                        Tên kích cỡ
                    </th>

                    <th class="w-[20%] px-2 py-3 text-center">
                        Ngày tạo
                    </th>

                    <th class="w-[20%] px-2 py-3 text-center">
                        Ngày cập nhật
                    </th>

                    <th class="w-[15%] px-2 py-3 text-center">
                        Thao tác
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-red-50">

                @forelse($sizes as $size)

                    <tr class="size-card hover:bg-red-50/40 transition">

                        {{-- MÃ SIZE --}}
                        <td data-label="Mã Size" class="px-3 py-3 align-middle">
                            <span class="font-bold text-red-900 text-[11px]">
                                #{{ $size->MaSize }}
                            </span>
                        </td>

                        {{-- TÊN SIZE --}}
                        <td data-label="Tên kích cỡ" class="px-3 py-3 align-middle">
                            <p class="font-bold text-red-900 uppercase text-[11px] break-words">
                                {{ $size->TenSize }}
                            </p>
                        </td>

                        {{-- NGÀY TẠO --}}
                        <td data-label="Ngày tạo" class="px-2 py-3 text-center align-middle">
                            <span class="text-[10px] text-stone-500 whitespace-nowrap">
                                {{ $size->NgayTao ? date('d/m/Y', strtotime($size->NgayTao)) : '---' }}
                            </span>
                        </td>

                        {{-- NGÀY CẬP NHẬT --}}
                        <td data-label="Ngày cập nhật" class="px-2 py-3 text-center align-middle">
                            <span class="text-[10px] text-stone-500 whitespace-nowrap">
                                {{ $size->NgayCapNhat ? date('d/m/Y', strtotime($size->NgayCapNhat)) : '---' }}
                            </span>
                        </td>

                        {{-- THAO TÁC --}}
                        <td data-label="Thao tác" class="px-2 py-3 align-middle">
                            <div class="flex justify-center items-center gap-1">

                                <a href="{{ route('admin.sizes.edit', $size->MaSize) }}"
                                   title="Sửa kích cỡ"
                                   class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </a>

                                <button type="button"
                                        onclick="confirmDeleteSize('{{ $size->MaSize }}', @js($size->TenSize))"
                                        title="Xóa kích cỡ"
                                        class="w-7 h-7 flex items-center justify-center rounded-md border border-red-200 text-red-600 hover:bg-red-700 hover:text-white transition">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5"
                            class="px-4 py-8 text-center text-stone-400 italic">
                            Không tìm thấy kích cỡ nào.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>

</div>


<style>
/* Responsive cho trang Quản lý kích cỡ */
.size-admin-page { min-width: 0; }
.size-admin-page .size-table td { overflow-wrap: anywhere; }
@media (max-width: 767px) {
    .size-admin-page #size-table-container { background: #fafaf9; padding: 10px; }
    .size-admin-page .size-table,
    .size-admin-page .size-table tbody { display: block; width: 100%; min-width: 0; }
    .size-admin-page .size-table thead { display: none; }
    .size-admin-page .size-table tbody { display: grid; gap: 12px; }
    .size-admin-page .size-table tr.size-card {
        display: block; background: white; border: 1px solid #fee2e2;
        border-radius: 14px; padding: 8px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,.035);
    }
    .size-admin-page .size-table tr.size-card td {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; width: 100%; padding: 11px 0 !important;
        text-align: right; border-bottom: 1px solid #f5f5f4;
        white-space: normal;
    }
    .size-admin-page .size-table tr.size-card td:last-child { border-bottom: none; }
    .size-admin-page .size-table tr.size-card td::before {
        content: attr(data-label); flex: 0 0 40%; text-align: left;
        font-size: 11px; font-weight: 700; color: #78716c;
    }
    .size-admin-page .size-table tr.size-card td > * { max-width: 58%; min-width: 0; }
    .size-admin-page .size-table tr.size-card td[data-label="Thao tác"] > div {
        justify-content: flex-end;
    }
    .size-admin-page .size-table .size-empty-row { display: block; }
    .size-admin-page .size-table .size-empty-row td { display: block; width: 100%; }
}
</style>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Thông báo thành công
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

    // Thông báo lỗi
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

    // XÁC NHẬN XÓA MỀM
    function confirmDeleteSize(id, name) {

        Swal.fire({
            title: '<span class="serif">Xác nhận xóa bỏ</span>',

            html: `
                <div class="mt-3 text-stone-500 text-sm">
                    Kích cỡ
                    <b class="text-stone-900">"${escapeHtml(name)}"</b>
                    sẽ được chuyển vào trạng thái đã xóa.
                    <br>
                    <span class="text-red-600 font-bold">
                        Kích cỡ vẫn được lưu trong cơ sở dữ liệu.
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

            reverseButtons: true,
            showLoaderOnConfirm: true,

            preConfirm: () => {

                return fetch(`/admin/sizes/delete/${id}`, {
                    method: 'DELETE',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })

                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Không thể xóa kích cỡ.');
                    }

                    return data;
                })

                .catch(error => {
                    Swal.showValidationMessage(`Lỗi: ${error.message}`);
                });
            },

            allowOutsideClick: () => !Swal.isLoading()

        }).then(result => {

            if (result.isConfirmed && result.value?.success) {

                Swal.fire({
                    icon: 'success',
                    title: '<span class="serif text-2xl">Thành công!</span>',
                    text: result.value.message,
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'glass-popup'
                    }
                }).then(() => {
                    window.location.reload();
                });

            }

        });
    }

    // Tránh lỗi HTML khi tên kích cỡ chứa ký tự đặc biệt
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

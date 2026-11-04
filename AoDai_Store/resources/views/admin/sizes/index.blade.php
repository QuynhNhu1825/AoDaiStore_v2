@extends('layouts.admin')

@section('title', 'Quản lý Kích cỡ Áo Dài')

@section('content')
    <div class="space-y-6" id ="size-table-container">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-stone-100 flex items-center">
                <div class="p-3 bg-stone-50 rounded-xl text-stone-600 mr-4">
                    <i class="fas fa-ruler-combined fa-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Tổng số size</p>
                    <p class="text-xl font-bold text-gray-800">{{ $totalSizeCount }}</p>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-stone-100 flex items-center">
                <div class="p-3 bg-green-50 rounded-xl text-green-600 mr-4">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Đang áp dụng</p>
                    <p class="text-xl font-bold text-stone-800">{{ $activeSizeCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-50">
            <form action="{{ route('admin.sizes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="relative md:col-span-3">
                    <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" placeholder="Tìm tên size (S, M, L, XL...)" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs focus:ring-0 focus:border-stone-800 transition-all">
                </div>

                <button type="submit" class="bg-stone-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-800 transition-all py-2 shadow-md">
                    Lọc dữ liệu
                </button>

                <a href="{{ route('admin.sizes.create') }}" class="inline-flex items-center justify-center bg-stone-100 text-stone-800 border border-stone-200 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-green-600 hover:text-white hover:border-green-600 transition-all py-2 shadow-sm">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm Kích Cỡ
                </a>
            </form>
        </div>

        <div id="size-table-container">
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden text-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50/50 text-stone-500 text-[10px] uppercase tracking-[0.2em] font-bold border-b border-stone-100">
                            <th class="px-6 py-5">Kích cỡ</th>
                            <th class="px-6 py-5">Trạng thái</th>
                            <th class="px-6 py-5 text-center">Mô tả thông số</th>
                            <th class="px-6 py-5 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($sizes as $size)
                            <tr class="hover:bg-stone-50/30 transition duration-150 group">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <p class="font-bold text-stone-800 group-hover:text-red-800 transition-colors uppercase tracking-widest text-base">
                                            {{ $size->TenSize }}
                                        </p>
                                        @error('TenSize') 
                                            <div class="flex items-center mt-2 text-red-800">
                                                <i class="fas fa-exclamation-circle text-[10px] mr-1"></i>
                                                <span class="text-[10px] font-bold uppercase tracking-tight">{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <p class="text-[10px] text-stone-400 font-mono mt-0.5">ID: #{{ $size->MaSize }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($size->TrangThai == 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-50 text-green-700 border border-green-100">
                                            <span class="w-1 h-1 mr-1.5 rounded-full bg-green-600"></span> Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-stone-100 text-stone-400 border border-stone-200">
                                            <span class="w-1 h-1 mr-1.5 rounded-full bg-stone-400"></span> Tạm khóa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center text-stone-500 italic text-xs">
                                    {{ $size->MoTa ?? 'Chưa có thông số chi tiết' }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="toggleSizeStatus('{{ $size->MaSize }}', {{ $size->TrangThai }})"
                                            class="flex items-center px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest
                                            {{ $size->TrangThai == 1 ? 'text-amber-600' : 'text-green-600' }}">
                                            
                                            <i class="fas {{ $size->TrangThai == 1 ? 'fa-eye-slash' : 'fa-eye' }} mr-1.5"></i>
                                            {{ $size->TrangThai == 1 ? 'Ẩn' : 'Hiện' }}
                                        </button>

                                        <a href="{{ route('admin.sizes.edit', $size->MaSize) }}"
                                            class="flex items-center px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-blue-600 hover:bg-blue-600 hover:text-white transition-all rounded-lg border border-blue-100">
                                            <i class="fas fa-edit mr-1.5"></i> Sửa
                                        </a>

                                        <button type="button" onclick="confirmDeleteSize('{{ $size->MaSize }}', '{{ $size->TenSize }}')"
                                            class="flex items-center px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-red-600 hover:bg-red-700 hover:text-white transition-all rounded-lg border border-red-100">
                                            <i class="fas fa-trash-alt mr-1.5"></i> Xóa
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-stone-400 italic font-light">
                                    <i class="fas fa-ruler-combined fa-2x mb-3 opacity-20 block text-center"></i>
                                    Không tìm thấy kích cỡ nào phù hợp.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                customClass: { popup: 'glass-popup' }
            });
        @endif

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function toggleSizeStatus(id, currentStatus) {
            const newStatus = currentStatus === 1 ? 0 : 1;
            const actionText = newStatus === 1 ? 'kích hoạt' : 'tạm khóa';

            Swal.fire({
                title: '<span class="serif">Cập nhật kích cỡ</span>',
                html: `<div class="text-stone-500 text-sm">Bạn muốn ${actionText} size này?</div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                buttonsStyling: false,
                customClass: { popup: 'glass-popup', confirmButton: 'btn-delete-confirm', cancelButton: 'btn-delete-cancel' },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/sizes/toggle-status/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Đã cập nhật', 
                                text: data.message,
                                timer: 1000, 
                                showConfirmButton: false, 
                                customClass: { popup: 'glass-popup' } 
                            });
                            refreshSizeTable();
                        }
                    });
                }
            });
        }

        function confirmDeleteSize(id, name) {
            Swal.fire({
                title: '<span class="serif">Xác nhận xóa bỏ</span>',
                html: `<div class="mt-3 text-stone-500 text-sm">Kích cỡ <b class="text-stone-900 italic">"${name}"</b> sẽ bị xóa khỏi hệ thống. <br><span class="text-red-600 font-bold">Lưu ý: Hành động này không thể hoàn tác!</span></div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý xóa',
                cancelButtonText: 'Hủy bỏ',
                buttonsStyling: false,
                customClass: { popup: 'glass-popup', confirmButton: 'btn-delete-confirm', cancelButton: 'btn-delete-cancel' },
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/admin/sizes/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
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
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Lỗi: ${error.message}`);
                    });
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Đã xóa thành công',
                                        text: 'Dữ liệu đã được gỡ bỏ khỏi hệ thống.',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        customClass: { popup: 'glass-popup' }
                                    });
                                    refreshSizeTable();
                                }
                            });
                        }

        function refreshSizeTable() {
            const container = document.getElementById('size-table-container'); 
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    container.innerHTML = doc.getElementById('size-table-container').innerHTML;
            });
        }
    </script>

    <style>
        .serif { font-family: 'Playfair Display', serif; }
        .glass-popup {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-radius: 24px !important;
            padding: 2rem !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        .btn-delete-confirm {
            background-color: #1c1917 !important;
            color: white !important;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            margin-left: 10px !important;
            font-size: 12px !important;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }
        .btn-delete-confirm:hover { background-color: #991b1b !important; transform: translateY(-2px); }
        .btn-delete-cancel {
            background-color: #f3f4f6 !important;
            color: #4b5563 !important;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            text-transform: uppercase;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }
    </style>
@endsection
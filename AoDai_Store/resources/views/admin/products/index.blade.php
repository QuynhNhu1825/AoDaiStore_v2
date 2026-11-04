@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm Áo Dài')

@section('content')
<div class="space-y-6" id="full-container">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-stone-100 flex items-center">
            <div class="p-3 bg-stone-50 rounded-xl text-stone-600 mr-4">
                <i class="fas fa-tshirt fa-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Tổng sản phẩm</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalProducts }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-stone-100 flex items-center">
            <div class="p-3 bg-green-50 rounded-xl text-green-600 mr-4">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Đang hoạt động</p>
                <p class="text-xl font-bold text-stone-800">
                    {{ $activeProducts  }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
        
        <div class="relative flex-1 max-w-[200px] min-w-[150px]">
            <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                <i class="fas fa-search text-[10px]"></i>
            </span>
            <input type="text" name="search" 
                placeholder="Tìm kiếm..." 
                value="{{ request('search') }}"
                class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-stone-800 transition-all">
        </div>

        <div class="flex flex-wrap md:flex-nowrap items-center gap-2 flex-grow">
            <select name="category" class="bg-stone-50 border border-stone-200 rounded-xl py-2 px-3 text-[11px] focus:ring-0 focus:border-stone-800 transition-all cursor-pointer min-w-[110px]">
                <option value="">-- Danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->MaLoaiSP }}" {{ request('category') == $cat->MaLoaiSP ? 'selected' : '' }}>
                        {{ $cat->TenLoaiSP }}
                    </option>
                @endforeach
            </select>

            <select name="material" class="bg-stone-50 border border-stone-200 rounded-xl py-2 px-3 text-[11px] focus:ring-0 focus:border-stone-800 cursor-pointer min-w-[110px]">
                <option value="">-- Chất liệu --</option>
                @foreach($materials as $m)
                    <option value="{{ $m->MaChatLieu }}" {{ request('material') == $m->MaChatLieu ? 'selected' : '' }}>
                        {{ $m->TenChatLieu }}
                    </option>
                @endforeach
            </select>

            <select name="color" class="bg-stone-50 border border-stone-200 rounded-xl py-2 px-3 text-[11px] focus:ring-0 focus:border-stone-800 cursor-pointer min-w-[110px]">
                <option value="">-- Màu sắc --</option>
                @foreach($colors as $c)
                    <option value="{{ $c->MaLoaiMau }}" {{ request('color') == $c->MaLoaiMau ? 'selected' : '' }}>
                        {{ $c->TenLoaiMau }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="bg-stone-900 text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-800 transition-all shadow-sm whitespace-nowrap">
                Lọc
            </button>

            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center bg-stone-100 text-stone-800 border border-stone-200 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-green-600 hover:text-white hover:border-green-600 transition-all shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i> Thêm Áo Dài
            </a>
        </div>
    </form>
</div>

    <div id="product-table-container" class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden text-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-50 text-stone-500 text-[10px] uppercase tracking-[0.2em] font-bold">
                    <th class="px-6 py-5 border-b border-stone-100">Sản Phẩm</th>
                    <th class="px-6 py-5 border-b border-stone-100 text-center">Màu Sắc</th> {{-- Mới --}}
                    <th class="px-6 py-5 border-b border-stone-100 text-center">Trạng Thái</th>
                    <th class="px-6 py-5 border-b border-stone-100 text-center">Giá Niêm Yết</th>
                    <th class="px-6 py-5 border-b border-stone-100 text-center">Ngày Tạo</th> {{-- Đã có logic --}}
                    <th class="px-6 py-5 border-b border-stone-100 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @forelse($products as $product)
                <tr class="hover:bg-stone-50/30 transition duration-150 group">
                    <td class="px-6 py-5">
                        <div class="flex items-center">
                            <div class="flex flex-col">
                                <p class="font-bold text-stone-800 group-hover:text-red-800 transition-colors uppercase tracking-tight text-sm">
                                    {{ $product->TenSanPham }}
                                </p>
                                <p class="text-[10px] text-stone-400 font-mono mt-0.5">#{{ $product->MaSanPham }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="text-xs text-stone-600">
                            {{ $product->loaimau->TenLoaiMau ?? 'Chưa xác định' }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-5">
                        @if($product->TrangThai == 1)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1 h-1 mr-1.5 rounded-full bg-green-600"></span> Đang hiện
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-stone-100 text-stone-400 border border-stone-200">
                                <span class="w-1 h-1 mr-1.5 rounded-full bg-stone-400"></span> Đang ẩn
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-center font-bold text-stone-700">
                        {{ number_format($product->GiaBan, 0, ',', '.') }}đ
                    </td>
                    <td class="px-6 py-5 text-center text-[11px] text-stone-500">
                        {{ $product->CreatedDate ? date('d/m/Y', strtotime($product->CreatedDate)) : '---' }}
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex justify-end space-x-2">
                           <button onclick="toggleStatus('{{ $product->MaSanPham }}', {{ $product->TrangThai }})"
                                title="{{ $product->TrangThai == 1 ? 'Tạm dừng bán' : 'Kích hoạt bán' }}"
                                class="flex items-center px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest {{ $product->TrangThai == 1 ? 'text-amber-600 border-amber-100 hover:bg-amber-600' : 'text-green-600 border-green-100 hover:bg-green-600' }} hover:text-white transition-all rounded-lg border">
                                <i class="fas {{ $product->TrangThai == 1 ? 'fa-eye-slash' : 'fa-eye' }} mr-1.5"></i>
                                {{ $product->TrangThai == 1 ? 'Ẩn' : 'Hiện' }}
                            </button>
                            <a href="{{ route('admin.products.edit', $product->MaSanPham) }}"
                                class="flex items-center px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-blue-600 hover:bg-blue-600 hover:text-white transition-all rounded-lg border border-blue-100">
                                <i class="fas fa-edit mr-1.5"></i> Sửa
                            </a>

                            <button onclick="confirmDeleteProduct('{{ $product->MaSanPham }}', '{{ $product->TenSanPham }}')"
                                class="flex items-center px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-red-600 hover:bg-red-700 hover:text-white transition-all rounded-lg border border-red-100">
                                <i class="fas fa-trash-alt mr-1.5"></i> Xóa
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-stone-400 italic">Không tìm thấy sản phẩm nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-stone-100">
        {{ $products->links('pagination::tailwind') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '<span class="serif text-2xl">Thành công!</span>',
            text: "{{ session('success') }}",
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
            text: "{{ session('error') }}",
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#1c1917',
            buttonsStyling: false,
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-delete-confirm'
            }
        });
    @endif
    function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus === 1 ? 0 : 1;
            const actionText = newStatus === 1 ? 'hiển thị và bán lại' : 'tạm ẩn';
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: '<span class="serif">Cập nhật trạng thái</span>',
                html: `<div class="text-stone-500 text-sm">Bạn muốn ${actionText} sản phẩm này?</div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#1c1917',
                customClass: { popup: 'glass-popup', confirmButton: 'btn-delete-confirm', cancelButton: 'btn-delete-cancel' },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/products/toggle-status/${id}`, {
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
                                timer: 1000, 
                                showConfirmButton: false, 
                                customClass: { popup: 'glass-popup' } 
                            });
                            refreshTable();;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }

    function refreshTable() {
        const container = document.getElementById('full-container'); 
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('full-container').innerHTML;
                container.innerHTML = newContent;
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
            if (result.isConfirmed && result.value.success) {
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
    .glass-popup {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        border-radius: 24px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .btn-delete-confirm {
        background-color: #1c1917 !important; color: white !important;
        padding: 10px 25px !important; border-radius: 12px !important;
        font-weight: 600 !important; font-size: 11px !important;
        text-transform: uppercase; letter-spacing: 0.1em; margin-left: 10px;
    }
    .btn-delete-cancel {
        background-color: #f3f4f6 !important; color: #4b5563 !important;
        padding: 10px 25px !important; border-radius: 12px !important;
        font-weight: 600 !important; font-size: 11px !important;
    }
</style>
@endsection
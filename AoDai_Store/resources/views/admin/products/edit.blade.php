
@extends('layouts.admin')

@section('title', 'Chỉnh sửa: ' . $product->TenSanPham)

@section('content')

@php
    $selectedCategories = old(
        'MaDanhMuc',
        $product->danhMucs->pluck('MaDanhMuc')->toArray()
    );

    $selectedColors = old(
        'MaMau',
        $product->chiTietSanPham->pluck('MaMau')->unique()->toArray()
    );

    $existingVariants = $product->chiTietSanPham
        ->map(function ($variant) {
            return [
                'colorId' => (string) $variant->MaMau,
                'sizeId' => (string) $variant->MaSize,
                'Gia' => $variant->Gia,
                'SoLuong' => $variant->SoLuong,
            ];
        })
        ->values()
        ->toArray();
@endphp

<div class="max-w-7xl mx-auto pb-10">

    {{-- HEADER --}}
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại danh sách
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">

        {{-- TITLE --}}
        <div class="px-6 py-5 border-b border-red-100 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-edit mr-2"></i>
                Chỉnh sửa sản phẩm
            </h2>

            <p class="text-xs text-stone-500 mt-1">
                Cập nhật thông tin sản phẩm trong hệ thống.
            </p>
            </div>

            <span class="text-xs font-bold text-red-800 bg-red-50 px-3 py-2 rounded-lg">
                #{{ $product->MaSanPham }}
            </span>
        </div>

        <form id="editProductForm"
        action="{{ route('admin.products.update', $product->MaSanPham) }}"
        method="POST"
        enctype="multipart/form-data"
        class="p-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- CỘT TRÁI --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- THÔNG TIN CƠ BẢN --}}
                    <div class="border border-red-100 rounded-xl p-5">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-800">
                                <i class="fas fa-info-circle text-lg"></i>
                            </div>

                            <div>
                                <h3 class="font-bold text-red-900">Thông tin cơ bản</h3>
                                <p class="text-xs text-stone-400 mt-1">
                                    Thông tin chung của sản phẩm
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">

                            {{-- TÊN SẢN PHẨM --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">
                                    Tên sản phẩm
                                </label>

                                <input type="text"
                                       name="TenSanPham"
                                       value="{{ old('TenSanPham', $product->TenSanPham) }}"
                                       required
                                       class="w-full bg-stone-50 border border-stone-200 rounded-lg px-3 py-3 text-sm focus:ring-2 focus:ring-red-200 focus:border-red-700 outline-none">

                                @error('TenSanPham')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- DANH MỤC VÀ CHẤT LIỆU --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- DANH MỤC --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">
                                        Danh mục sản phẩm
                                    </label>

                                    <details class="relative">
                                        <summary class="list-none cursor-pointer flex items-center justify-between max-w-[260px] bg-stone-50 border border-stone-200 rounded-lg px-3 py-2.5 text-xs text-stone-700 hover:border-red-700">
                                            <span>Chọn danh mục</span>
                                            <i class="fas fa-chevron-down text-[10px]"></i>
                                        </summary>

                                        <div class="absolute top-full left-0 mt-1 w-full max-w-[260px] bg-white border border-stone-200 rounded-lg shadow-xl z-40 p-3 max-h-52 overflow-y-auto">

                                            @foreach($categories as $item)
                                                <label class="flex items-center gap-2 py-2 text-xs text-stone-700 cursor-pointer hover:text-red-800">
                                                    <input type="checkbox"
                                                           name="MaDanhMuc[]"
                                                           value="{{ $item->MaDanhMuc }}"
                                                           {{ in_array($item->MaDanhMuc, $selectedCategories) ? 'checked' : '' }}
                                                           class="rounded border-stone-300 text-red-800 focus:ring-red-700">

                                                    {{ $item->TenDanhMuc ?? $item->TenLoaiSP }}
                                                </label>
                                            @endforeach

                                        </div>
                                    </details>

                                    @error('MaDanhMuc')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- CHẤT LIỆU --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">
                                        Chất liệu vải
                                    </label>

                                    <select name="MaChatLieu"
                                            required
                                            class="w-full max-w-[260px] bg-stone-50 border border-stone-200 rounded-lg px-3 py-2.5 text-xs focus:ring-2 focus:ring-red-200 outline-none cursor-pointer">

                                        <option value="">-- Chọn chất liệu --</option>

                                        @foreach($materials as $item)
                                            <option value="{{ $item->MaChatLieu }}"
                                                {{ old('MaChatLieu', $product->MaChatLieu) == $item->MaChatLieu ? 'selected' : '' }}>
                                                {{ $item->TenChatLieu }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('MaChatLieu')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            {{-- MÔ TẢ --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">
                                    Mô tả sản phẩm
                                </label>

                                <textarea name="MoTa"
                                          rows="5"
                                          class="w-full bg-stone-50 border border-stone-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-200 outline-none">{{ old('MoTa', $product->MoTa) }}</textarea>

                                @error('MoTa')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- BIẾN THỂ SẢN PHẨM --}}
                    <div class="border border-red-100 rounded-xl p-5">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-800">
                                <i class="fas fa-layer-group text-lg"></i>
                            </div>

                            <div>
                                <h3 class="font-bold text-red-900">Biến thể sản phẩm</h3>
                                <p class="text-xs text-stone-400 mt-1">
                                    Thiết lập màu sắc, kích cỡ, giá bán và số lượng
                                </p>
                            </div>
                        </div>

                        {{-- CHỌN MÀU --}}
                        <div class="mb-5">
                            <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">
                                Màu sắc sản phẩm
                            </label>

                            <details class="relative">
                                <summary class="list-none cursor-pointer flex items-center justify-between max-w-[260px] bg-stone-50 border border-stone-200 rounded-lg px-3 py-2.5 text-xs text-stone-700 hover:border-red-700">
                                    <span>Chọn màu sắc</span>
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </summary>

                                <div class="absolute top-full left-0 mt-1 w-full max-w-[260px] bg-white border border-stone-200 rounded-lg shadow-xl z-40 p-3 max-h-52 overflow-y-auto">

                                    @foreach($colors as $item)
                                        <label class="flex items-center gap-2 py-2 text-xs text-stone-700 cursor-pointer hover:text-red-800">
                                            <input type="checkbox"
                                                   name="MaMau[]"
                                                   value="{{ $item->MaMau }}"
                                                   data-color-name="{{ $item->TenMau }}"
                                                   class="color-checkbox rounded border-stone-300 text-red-800 focus:ring-red-700"
                                                   {{ in_array($item->MaMau, $selectedColors) ? 'checked' : '' }}>

                                            {{ $item->TenMau }}
                                        </label>
                                    @endforeach

                                </div>
                            </details>

                            @error('MaMau')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TABS MÀU --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-stone-600">
                                    Danh sách biến thể
                                </p>

                                <span id="variantCount"
                                      class="text-[10px] font-bold text-red-800 bg-red-50 px-3 py-1.5 rounded-full">
                                    0 màu
                                </span>
                            </div>

                            <div id="variantColorTabs" class="flex flex-wrap gap-2 mb-4"></div>

                            <div class="border border-stone-200 rounded-xl overflow-hidden">

                                <div class="grid grid-cols-3 gap-3 bg-red-50 px-4 py-3 text-[10px] uppercase font-bold tracking-wider text-red-900">
                                    <span>Kích cỡ</span>
                                    <span>Giá bán (VNĐ)</span>
                                    <span>Số lượng</span>
                                </div>

                                <div id="variantTable" class="divide-y divide-stone-100">
                                    <div class="p-8 text-center text-xs text-stone-400">
                                        Vui lòng chọn màu sắc.
                                    </div>
                                </div>

                            </div>

                            @error('variants')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

                {{-- CỘT PHẢI --}}
                <div class="space-y-6">

                    {{-- ẢNH ĐẠI DIỆN --}}
                    <div class="border border-red-100 rounded-xl p-5">

                        <h3 class="font-bold text-red-900 text-sm mb-4">
                            <i class="fas fa-image text-red-800 mr-2"></i>
                            Ảnh đại diện
                        </h3>

                        <div class="flex flex-col items-center">

                            <img id="mainImagePreview"
                                 src="{{ $product->HinhAnh ? asset('img/products/' . $product->HinhAnh) : 'https://placehold.co/400x600?text=No+Image' }}"
                                 onerror="this.src='https://placehold.co/400x600?text=No+Image'"
                                 class="w-36 h-48 object-cover rounded-xl border border-stone-200 shadow-sm mb-4">

                            <input type="file"
                                   name="HinhAnh"
                                   id="mainImageInput"
                                   accept="image/*"
                                   class="block w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-red-50 file:text-red-900 file:font-bold hover:file:bg-red-100 cursor-pointer">

                            <p class="text-[10px] text-stone-400 mt-2 italic">
                                Để trống nếu muốn giữ ảnh hiện tại.
                            </p>

                            @error('HinhAnh')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div class="border border-red-100 rounded-xl p-5">

                        <h3 class="font-bold text-red-900 text-sm mb-4">
                            <i class="fas fa-toggle-on text-red-800 mr-2"></i>
                            Trạng thái kinh doanh
                        </h3>

                        <select name="TrangThai"
                                class="w-full bg-stone-50 border border-stone-200 rounded-lg px-3 py-3 text-xs focus:ring-2 focus:ring-red-200 outline-none">

                            <option value="1"
                                {{ old('TrangThai', $product->TrangThai) == 1 ? 'selected' : '' }}>
                                Đang kinh doanh
                            </option>

                            <option value="0"
                                {{ old('TrangThai', $product->TrangThai) == 0 ? 'selected' : '' }}>
                                Tạm dừng
                            </option>

                        </select>

                    </div>

                    {{-- ALBUM ẢNH --}}
                    <div class="border border-red-100 rounded-xl p-5">

                        <h3 class="font-bold text-red-900 text-sm mb-4">
                            <i class="fas fa-images text-red-800 mr-2"></i>
                            Album ảnh chi tiết
                        </h3>

                        <div class="grid grid-cols-2 gap-3">

                            @forelse($product->hinhanhsanpham as $hinh)

                                <div class="border border-stone-200 rounded-lg p-2">

                                    <img src="{{ asset('img/details/' . $hinh->LinkAnh) }}"
                                         onerror="this.src='https://placehold.co/300x300?text=No+Image'"
                                         class="w-full h-28 object-cover rounded-md mb-2">

                                    <label class="block text-[9px] font-bold text-stone-500 mb-1">
                                        Thay ảnh
                                    </label>

                                    <input type="file"
                                           name="replace_images[{{ $hinh->MaHinhAnh }}]"
                                           accept="image/*"
                                           class="w-full text-[9px] text-stone-500">

                                    <label class="flex items-center gap-2 mt-3 cursor-pointer text-[10px] text-red-700 font-bold">
                                        <input type="checkbox"
                                               name="delete_images[]"
                                               value="{{ $hinh->MaHinhAnh }}"
                                               class="rounded border-stone-300 text-red-700 focus:ring-red-700">

                                        Xóa ảnh
                                    </label>

                                </div>

                            @empty

                                <p class="col-span-2 text-xs text-stone-400 text-center py-5">
                                    Chưa có ảnh chi tiết.
                                </p>

                            @endforelse

                        </div>

                        <div class="mt-5 border-2 border-dashed border-red-100 rounded-xl p-4 text-center">

                            <p class="text-[10px] uppercase tracking-widest font-bold text-stone-500 mb-3">
                                Thêm ảnh mới
                            </p>

                            <input type="file"
                                   name="AlbumHinh[]"
                                   multiple
                                   accept="image/*"
                                   class="w-full text-xs text-stone-500">

                        </div>

                    </div>

                </div>

            </div>

            {{-- BUTTON --}}
            <div class="mt-8 pt-6 border-t border-stone-100 flex flex-col sm:flex-row gap-3">

                <div class="mt-8 flex justify-end gap-3">
                <button type="submit"
                    class="px-8 py-3 bg-red-800 text-white rounded-xl font-bold hover:bg-red-900 shadow-md transition-all duration-300 uppercase tracking-wider text-xs">
                    <i class="fas fa-save mr-2"></i> Lưu thay đổi
                </button>

                <a href="{{ route('admin.products.index') }}"
                   class="px-8 py-3.5 bg-stone-100 text-stone-600 rounded-xl hover:bg-stone-200 transition-all font-bold uppercase tracking-widest text-xs flex items-center justify-center">
                    Hủy bỏ
                </a>

            </div>

        </form>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const colorCheckboxes = document.querySelectorAll('.color-checkbox');
    const editForm = document.getElementById('editProductForm');
    const variantTabs = document.getElementById('variantColorTabs');
    const variantTable = document.getElementById('variantTable');
    const variantCount = document.getElementById('variantCount');

    const sizes = @json($sizes->map(function ($size) {
        return [
            'id' => (string) $size->MaSize,
            'name' => $size->TenSize
        ];
    })->values());

    const existingVariants = @json($existingVariants);
    const oldVariants = @json(old('variants', []));

    let variantValues = {};

    // Nạp dữ liệu biến thể cũ
    existingVariants.forEach(item => {
        if (!variantValues[item.colorId]) {
            variantValues[item.colorId] = {};
        }

        variantValues[item.colorId][item.sizeId] = {
            Gia: item.Gia,
            SoLuong: item.SoLuong
        };
    });

    // Ưu tiên dữ liệu người dùng đã nhập nếu validation thất bại
    Object.keys(oldVariants).forEach(colorId => {
        variantValues[colorId] = oldVariants[colorId];
    });

    let activeColorId = null;

   function getSelectedColors() {
    return Array.from(
        editForm.querySelectorAll('input[name="MaMau[]"]:checked')
    ).map(input => ({
        id: input.value,
        name: input.dataset.colorName
    }));
}

    function saveVariantValues() {
        document.querySelectorAll('#variantTable input[data-color]').forEach(input => {

            const colorId = input.dataset.color;
            const sizeId = input.dataset.size;
            const field = input.dataset.field;

            if (!variantValues[colorId]) {
                variantValues[colorId] = {};
            }

            if (!variantValues[colorId][sizeId]) {
                variantValues[colorId][sizeId] = {
                    Gia: '',
                    SoLuong: ''
                };
            }

            variantValues[colorId][sizeId][field] = input.value;
        });
    }

    function showColorVariants(colorId) {
        saveVariantValues();
        activeColorId = colorId;
        renderVariants();
    }

    function renderVariants() {

        const selectedColors = getSelectedColors();

        variantTabs.innerHTML = '';
        variantTable.innerHTML = '';

        variantCount.textContent = selectedColors.length + ' màu';

        if (selectedColors.length === 0) {
            activeColorId = null;

            variantTable.innerHTML = `
                <div class="p-8 text-center text-xs text-stone-400">
                    Vui lòng chọn ít nhất một màu sắc.
                </div>
            `;

            return;
        }

        if (!selectedColors.some(color => color.id === activeColorId)) {
            activeColorId = selectedColors[0].id;
        }

        selectedColors.forEach(color => {

            const active = color.id === activeColorId;

            const button = document.createElement('button');

            button.type = 'button';
            button.textContent = color.name;

            button.className = active
                ? 'px-4 py-2 rounded-lg text-xs font-bold border bg-red-100 text-red-900 border-red-700'
                : 'px-4 py-2 rounded-lg text-xs font-bold border bg-white text-stone-600 border-stone-200 hover:border-red-400';

            button.addEventListener('click', () => {
                showColorVariants(color.id);
            });

            variantTabs.appendChild(button);

        });

        sizes.forEach(size => {

            const values =
                variantValues[activeColorId]?.[size.id] || {
                    Gia: '',
                    SoLuong: ''
                };

            const row = document.createElement('div');

            row.className = 'grid grid-cols-3 gap-3 items-center px-4 py-3';

            row.innerHTML = `
                <div>
                    <span class="text-xs font-bold text-stone-700">
                        ${escapeHtml(size.name)}
                    </span>
                </div>

                <div>
                    <input
                        type="number"
                        name="variants[${activeColorId}][${size.id}][Gia]"
                        data-color="${activeColorId}"
                        data-size="${size.id}"
                        data-field="Gia"
                        min="0"
                        step="1"
                        value="${escapeHtml(values.Gia ?? '')}"
                        placeholder="Giá bán"
                        required
                        class="w-full min-w-0 bg-white border border-stone-200 rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-red-200 outline-none">
                </div>

                <div>
                    <input
                        type="number"
                        name="variants[${activeColorId}][${size.id}][SoLuong]"
                        data-color="${activeColorId}"
                        data-size="${size.id}"
                        data-field="SoLuong"
                        min="0"
                        step="1"
                        value="${escapeHtml(values.SoLuong ?? '')}"
                        placeholder="Số lượng"
                        required
                        class="w-full min-w-0 bg-white border border-stone-200 rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-red-200 outline-none">
                </div>
            `;

            variantTable.appendChild(row);

        });

    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    editForm.addEventListener('change', function (event) {

    if (!event.target.matches('input[name="MaMau[]"]')) {
        return;
    }

    saveVariantValues();

    if (!event.target.checked && activeColorId === event.target.value) {
        activeColorId = null;
    }

    renderVariants();
});

// GỬI ĐẦY ĐỦ BIẾN THỂ CỦA TẤT CẢ MÀU ĐƯỢC CHỌN
editForm.addEventListener('submit', function () {

    // Lưu dữ liệu của màu đang hiển thị
    saveVariantValues();

    // Xóa input ẩn cũ
    editForm.querySelectorAll('.hidden-variant-input').forEach(input => {
        input.remove();
    });

    const selectedColors = getSelectedColors();

    selectedColors.forEach(color => {

        // Màu đang hiển thị đã có input thật
        if (color.id === activeColorId) {
            return;
        }

        sizes.forEach(size => {

            const values = variantValues[color.id]?.[size.id] || {
                Gia: '',
                SoLuong: ''
            };

            ['Gia', 'SoLuong'].forEach(field => {

                const hiddenInput = document.createElement('input');

                hiddenInput.type = 'hidden';
                hiddenInput.className = 'hidden-variant-input';

                hiddenInput.name =
                    `variants[${color.id}][${size.id}][${field}]`;

                hiddenInput.value = values[field] ?? '';

                editForm.appendChild(hiddenInput);
            });
        });
    });

});

// QUAN TRỌNG: GỬI DỮ LIỆU CỦA TẤT CẢ CÁC MÀU KHI LƯU


editForm.addEventListener('submit', function () {

    // Lưu giá và số lượng đang hiển thị trên màn hình
    saveVariantValues();

    // Xóa các input ẩn được tạo trước đó (nếu có)
    editForm.querySelectorAll('.hidden-variant-input').forEach(input => {
        input.remove();
    });

    const selectedColors = getSelectedColors();

    selectedColors.forEach(color => {

        // Màu đang hiển thị đã có input thật trong form
        if (color.id === activeColorId) {
            return;
        }

        // Tạo input ẩn cho các màu còn lại
        sizes.forEach(size => {

            const values = variantValues[color.id]?.[size.id] || {
                Gia: '',
                SoLuong: ''
            };

            ['Gia', 'SoLuong'].forEach(field => {

                const hiddenInput = document.createElement('input');

                hiddenInput.type = 'hidden';
                hiddenInput.className = 'hidden-variant-input';
                hiddenInput.name =
                    `variants[${color.id}][${size.id}][${field}]`;

                hiddenInput.value = values[field] ?? '';

                editForm.appendChild(hiddenInput);
            });
        });
    });
});

    // Xem trước ảnh đại diện
    const mainImageInput = document.getElementById('mainImageInput');
    const mainImagePreview = document.getElementById('mainImagePreview');

    mainImageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (file) {
            mainImagePreview.src = URL.createObjectURL(file);
        }

    });

    renderVariants();

});
</script>

<style>
    .details summary::-webkit-details-marker {
        display: none;
    }

    .serif {
        font-family: 'Playfair Display', serif;
    }
</style>

@endsection


@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="max-w-7xl mx-auto pb-10">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">
        <div>
            <h1 class="text-2xl font-bold text-stone-800">
                Thêm sản phẩm mới
            </h1>
            <p class="text-sm text-stone-500 mt-1">
                Quản lý thông tin và khởi tạo mẫu áo dài mới
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-600 hover:bg-stone-100 transition text-sm font-semibold">
            <i class="fas fa-arrow-left"></i>
            Quay lại danh sách
        </a>
    </div>


    {{-- THÔNG BÁO LỖI --}}
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
            <p class="font-bold text-red-700 text-sm mb-2">
                Vui lòng kiểm tra lại thông tin:
            </p>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="productForm">

        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- =====================================
                 CỘT TRÁI: THÔNG TIN SẢN PHẨM
            ====================================== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- THÔNG TIN CƠ BẢN --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-stone-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-800">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>

                        <div>
                            <h2 class="font-bold text-stone-800">
                                Thông tin cơ bản
                            </h2>
                            <p class="text-xs text-stone-400 mt-1">
                                Thông tin nhận diện sản phẩm
                            </p>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- TÊN SẢN PHẨM --}}
                        <div>
                            <label class="form-label">
                                Tên sản phẩm <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                   name="TenSanPham"
                                   value="{{ old('TenSanPham') }}"
                                   placeholder="Ví dụ: Áo Dài Tứ Thân Cổ Điển..."
                                   required
                                   maxlength="200"
                                   class="form-input @error('TenSanPham') border-red-500 @enderror">

                            @error('TenSanPham')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>


                    {{-- DANH MỤC VÀ CHẤT LIỆU --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- DANH MỤC --}}
                        <div>
                            <label class="form-label">
                                Danh mục <span class="text-red-500">*</span>
                            </label>

                            <div class="multi-select" data-name="MaDanhMuc">
                                <button type="button" class="multi-select-button">
                                    <span class="selected-label">-- Chọn danh mục --</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <div class="multi-select-options">
                                    @foreach($categories as $item)
                                        <label class="multi-select-option">
                                            <input type="checkbox"
                                                name="MaDanhMuc[]"
                                                value="{{ $item->MaDanhMuc }}"
                                                {{ in_array(
                                                    (string)$item->MaDanhMuc,
                                                    array_map('strval', old('MaDanhMuc', []))
                                                ) ? 'checked' : '' }}>
                                            <span>{{ $item->TenDanhMuc }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            @error('MaDanhMuc')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CHẤT LIỆU --}}
                        <div>
                            <label class="form-label">
                                Chất liệu <span class="text-red-500">*</span>
                            </label>

                            <select name="MaChatLieu" required class="form-input">
                                <option value="">-- Chọn chất liệu --</option>

                                @foreach($materials as $item)
                                    <option value="{{ $item->MaChatLieu }}"
                                        {{ old('MaChatLieu') == $item->MaChatLieu ? 'selected' : '' }}>
                                        {{ $item->TenChatLieu }}
                                    </option>
                                @endforeach
                            </select>

                            @error('MaChatLieu')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>


                        {{-- MÔ TẢ --}}
                        <div>
                            <label class="form-label">
                                Mô tả sản phẩm
                            </label>

                            <textarea name="MoTa"
                                      rows="5"
                                      placeholder="Mô tả chất liệu, kiểu dáng, đường may và ý nghĩa thiết kế..."
                                      class="form-input resize-none">{{ old('MoTa') }}</textarea>

                            @error('MoTa')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
                

                {{-- =====================================
                     THÔNG TIN BIẾN THỂ
                ====================================== --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-stone-100 flex items-center gap-3">

                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-800">
                            <i class="fas fa-layer-group text-lg"></i>
                        </div>

                        <div>
                            <h2 class="font-bold text-stone-800">
                                Biến thể sản phẩm
                            </h2>
                            <p class="text-xs text-stone-400 mt-1">
                                Thiết lập màu sắc, giá bán và số lượng theo kích cỡ
                            </p>
                        </div>

                    </div>


                    <div class="p-6 space-y-6">

                        {{-- MÀU SẮC --}}
                    <div>
                        <label class="form-label">
                            Màu sắc chủ đạo <span class="text-red-500">*</span>
                        </label>

                        <div class="multi-select" id="colorSelect">
                            <button type="button" class="multi-select-button">
                                <span class="selected-label">-- Chọn màu sắc --</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div class="multi-select-options">
                                @foreach($colors as $item)
                                    <label class="multi-select-option">
                                        <input type="checkbox"
                                            name="MaMau[]"
                                            value="{{ $item->MaMau }}"
                                            data-name="{{ $item->TenMau }}"
                                            {{ in_array(
                                                (string)$item->MaMau,
                                                array_map('strval', old('MaMau', []))
                                            ) ? 'checked' : '' }}>
                                        <span>{{ $item->TenMau }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>


                        {{-- BẢNG BIẾN THỂ --}}
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <label class="form-label mb-1">
                                    Thiết lập giá và tồn kho
                                    <span class="text-red-500">*</span>
                                </label>

                                <p class="text-xs text-stone-400">
                                    Chọn từng màu để thiết lập giá và số lượng theo kích cỡ.
                                </p>
                            </div>

                            <span id="variantCount"
                                class="text-xs text-stone-500 bg-stone-100 px-3 py-1.5 rounded-lg whitespace-nowrap">
                                0 màu
                            </span>
                        </div>

                        {{-- CÁC TAB MÀU SẮC --}}
                        <div id="variantColorTabs"
                            class="flex flex-wrap gap-2 mb-4">
                        </div>

                        {{-- BẢNG KÍCH CỠ --}}
                        <div class="border border-stone-200 rounded-xl overflow-hidden">

                            <div class="grid grid-cols-3 bg-stone-50 px-4 py-3 border-b border-stone-200 gap-3">
                                <span class="text-xs font-bold text-stone-500">Kích cỡ</span>
                                <span class="text-xs font-bold text-stone-500">Giá bán (VNĐ)</span>
                                <span class="text-xs font-bold text-stone-500">Số lượng</span>
                            </div>

                            <div id="variantTable" class="divide-y divide-stone-100">
                                <div class="p-5 text-center text-sm text-stone-400">
                                    Vui lòng chọn màu sắc để thiết lập biến thể.
                                </div>
                            </div>
                        </div>

                        @error('variants')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                    </div>
                    </div>
            {{-- =====================================
                 CỘT PHẢI: HÌNH ẢNH VÀ TRẠNG THÁI
            ====================================== --}}
            <div class="space-y-6">

                {{-- ẢNH ĐẠI DIỆN --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

                    <div class="px-5 py-4 border-b border-stone-100">
                        <h2 class="font-bold text-stone-800">
                            <i class="fas fa-image text-red-800 mr-2"></i>
                            Ảnh đại diện
                        </h2>
                    </div>

                    <div class="p-5">

                        <div id="mainPreview"
                             class="w-full aspect-square bg-stone-50 border-2 border-dashed border-stone-200 rounded-xl flex flex-col items-center justify-center overflow-hidden">

                            <i class="fas fa-cloud-upload-alt text-4xl text-stone-300 mb-3"></i>

                            <p class="text-sm text-stone-400">
                                Chưa có ảnh đại diện
                            </p>

                        </div>

                        <label class="form-label mt-5">
                            Chọn ảnh sản phẩm
                        </label>

                        <input type="file"
                               name="HinhAnh"
                               id="mainImageInput"
                               accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                               class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-stone-900 file:text-white file:font-semibold hover:file:bg-red-900 cursor-pointer">

                        <p class="text-xs text-stone-400 mt-2">
                            Định dạng JPG, PNG, WEBP. Tối đa 10MB.
                        </p>

                        @error('HinhAnh')
                            <p class="error-text">{{ $message }}</p>
                        @enderror

                    </div>
                </div>


                {{-- ALBUM ẢNH --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

                    <div class="px-5 py-4 border-b border-stone-100">
                        <h2 class="font-bold text-stone-800">
                            <i class="fas fa-images text-red-800 mr-2"></i>
                            Album ảnh chi tiết
                        </h2>
                    </div>

                    <div class="p-5">

                        <label class="block border-2 border-dashed border-stone-200 rounded-xl p-6 text-center cursor-pointer hover:border-red-300 hover:bg-red-50/30 transition">

                            <i class="fas fa-plus-circle text-3xl text-stone-300 mb-3"></i>

                            <p class="text-sm font-semibold text-stone-600">
                                Chọn nhiều hình ảnh
                            </p>

                            <p class="text-xs text-stone-400 mt-1">
                                Có thể chọn nhiều ảnh cùng lúc
                            </p>

                            <input type="file"
                                   name="AlbumHinh[]"
                                   id="albumInput"
                                   multiple
                                   accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                                   class="hidden">

                        </label>


                        <div id="albumPreview"
                             class="grid grid-cols-3 gap-2 mt-4">
                        </div>

                        <p class="text-xs text-stone-400 mt-3">
                            Tối đa 10MB cho mỗi ảnh.
                        </p>

                        @error('AlbumHinh')
                            <p class="error-text">{{ $message }}</p>
                        @enderror

                        @error('AlbumHinh.*')
                            <p class="error-text">{{ $message }}</p>
                        @enderror

                    </div>
                </div>


                {{-- TRẠNG THÁI --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5">

                    <h2 class="font-bold text-stone-800 mb-4">
                        <i class="fas fa-toggle-on text-red-800 mr-2"></i>
                        Trạng thái sản phẩm
                    </h2>

                    <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-100">

                        <div>
                            <p class="text-sm font-bold text-emerald-800">
                                Đang kinh doanh
                            </p>

                            <p class="text-xs text-emerald-600 mt-1">
                                Sản phẩm được kích hoạt sau khi tạo.
                            </p>
                        </div>

                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>

                    </div>

                </div>


                {{-- NÚT THAO TÁC --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5 space-y-3">

                    <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-red-800 text-white font-bold text-sm hover:bg-red-900 transition-all shadow-lg flex items-center justify-center gap-2">

                        <i class="fas fa-save"></i>

                        Tạo sản phẩm

                    </button>


                    <a href="{{ route('admin.products.index') }}"
                       class="w-full py-3 rounded-xl border border-stone-200 text-stone-600 font-semibold text-sm hover:bg-stone-50 transition flex items-center justify-center gap-2">

                        <i class="fas fa-times"></i>

                        Hủy bỏ

                    </a>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =====================================
     STYLE
====================================== --}}
<style>
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #57534e;
        margin-bottom: 9px;
    }

    .form-input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e7e5e4;
        border-radius: 10px;
        background: #fafaf9;
        color: #292524;
        font-size: 13px;
        outline: none;
        transition: all .2s;
    }

    .form-input:focus {
        border-color: #a8a29e;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(168, 162, 158, .15);
    }

    .error-text {
        display: block;
        color: #dc2626;
        font-size: 11px;
        margin-top: 6px;
        font-weight: 600;
    }
    .multi-select {
    position: relative;
    width: 100%;
}

.multi-select-button {
    width: 100%;
    min-height: 44px;
    padding: 12px 14px;
    border: 1px solid #e7e5e4;
    border-radius: 10px;
    background: #fafaf9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    color: #292524;
    font-size: 13px;
    cursor: pointer;
    text-align: left;
}

.multi-select-button:hover,
.multi-select.open .multi-select-button {
    border-color: #a8a29e;
    background: white;
}

.multi-select-options {
    display: none;
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    width: 100%;
    max-height: 190px;
    overflow-y: auto;
    background: white;
    border: 1px solid #e7e5e4;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0,0,0,.10);
    z-index: 50;
    padding: 6px;
}

.multi-select.open .multi-select-options {
    display: block;
}

.multi-select-option {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 10px;
    font-size: 13px;
    color: #57534e;
    border-radius: 6px;
    cursor: pointer;
}

.multi-select-option:hover {
    background: #f5f5f4;
}

.multi-select-option input {
    accent-color: #a16207;
}

.multi-select-options::-webkit-scrollbar {
    width: 6px;
}

.multi-select-options::-webkit-scrollbar-thumb {
    background: #d6d3d1;
    border-radius: 10px;
}
</style>


{{-- =====================================
     JAVASCRIPT XEM TRƯỚC ẢNH
====================================== --}}
<script>
    // ẢNH ĐẠI DIỆN
    const mainImageInput = document.getElementById('mainImageInput');
    const mainPreview = document.getElementById('mainPreview');

    mainImageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file hình ảnh hợp lệ!');
            this.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            mainPreview.innerHTML = `
                <img src="${e.target.result}"
                     class="w-full h-full object-contain"
                     alt="Ảnh đại diện">
            `;

        };

        reader.readAsDataURL(file);

    });


    // ALBUM ẢNH
    const albumInput = document.getElementById('albumInput');
    const albumPreview = document.getElementById('albumPreview');

    albumInput.addEventListener('change', function () {

        albumPreview.innerHTML = '';

        const files = Array.from(this.files);

        files.forEach(function (file) {

            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function (e) {

                const wrapper = document.createElement('div');

                wrapper.className = 'aspect-square rounded-lg overflow-hidden border border-stone-200';

                const img = document.createElement('img');

                img.src = e.target.result;

                img.alt = 'Ảnh album';

                img.className = 'w-full h-full object-cover';

                wrapper.appendChild(img);

                albumPreview.appendChild(wrapper);

            };

            reader.readAsDataURL(file);

        });

    });
    const colorSelect = document.getElementById('colorSelect');
const variantTable = document.getElementById('variantTable');
const variantCount = document.getElementById('variantCount');

const oldVariants = @json(old('variants', []));

const sizes = @json(
    $sizes->map(function ($size) {
        return [
            'id' => $size->MaSize,
            'name' => $size->TenSize
        ];
    })->values()
);

// MỞ / ĐÓNG DROPDOWN
document.querySelectorAll('.multi-select').forEach(dropdown => {
    const button = dropdown.querySelector('.multi-select-button');

    button.addEventListener('click', function (e) {
        e.stopPropagation();

        document.querySelectorAll('.multi-select').forEach(item => {
            if (item !== dropdown) {
                item.classList.remove('open');
            }
        });

        dropdown.classList.toggle('open');
    });
});

// ĐÓNG DROPDOWN KHI BẤM BÊN NGOÀI
document.addEventListener('click', function () {
    document.querySelectorAll('.multi-select').forEach(item => {
        item.classList.remove('open');
    });
});

// CẬP NHẬT TÊN HIỂN THỊ
function updateMultiSelectLabel(dropdown) {
    const checked = dropdown.querySelectorAll('input[type="checkbox"]:checked');
    const label = dropdown.querySelector('.selected-label');

    if (checked.length === 0) {
        label.textContent = dropdown.id === 'colorSelect'
            ? '-- Chọn màu sắc --'
            : '-- Chọn danh mục --';
        return;
    }

    const names = Array.from(checked).map(input => {
        return input.dataset.name ||
            input.closest('label').querySelector('span').textContent.trim();
    });

    label.textContent = names.join(', ');
}
// TẠO BẢNG BIẾN THỂ
const variantColorTabs = document.getElementById('variantColorTabs');

// LƯU DỮ LIỆU CỦA TỪNG MÀU
let variantValues = JSON.parse(JSON.stringify(oldVariants));

// LƯU GIÁ TRỊ ĐANG NHẬP
function saveVariantValues() {
    variantTable.querySelectorAll('input').forEach(input => {
        const colorId = input.dataset.color;
        const sizeId = input.dataset.size;
        const field = input.dataset.field;

        if (!variantValues[colorId]) {
            variantValues[colorId] = {};
        }

        if (!variantValues[colorId][sizeId]) {
            variantValues[colorId][sizeId] = {};
        }

        variantValues[colorId][sizeId][field] = input.value;
    });
}

// HIỂN THỊ BIẾN THỂ CỦA MỘT MÀU
function showColorVariants(colorId) {
    const panels = variantTable.querySelectorAll('[data-color-panel]');

    panels.forEach(panel => {
        panel.classList.toggle(
            'hidden',
            panel.dataset.colorPanel !== String(colorId)
        );
    });

    variantColorTabs.querySelectorAll('button').forEach(button => {
        const active = button.dataset.colorId === String(colorId);

       button.classList.toggle('bg-red-100', active);
       button.classList.toggle('text-red-900', active);
       button.classList.toggle('border-red-700', active);

       button.classList.toggle('bg-white', !active);
       button.classList.toggle('text-stone-600', !active);
       button.classList.toggle('border-stone-200', !active);
    });
}

// TẠO BẢNG BIẾN THỂ
function renderVariants() {
    saveVariantValues();

    const selectedColors = Array.from(
        colorSelect.querySelectorAll('input[type="checkbox"]:checked')
    );

    variantTable.innerHTML = '';
    variantColorTabs.innerHTML = '';

    if (selectedColors.length === 0) {
        variantTable.innerHTML = `
            <div class="p-5 text-center text-sm text-stone-400">
                Vui lòng chọn màu sắc để thiết lập biến thể.
            </div>
        `;

        variantCount.textContent = '0 màu';
        return;
    }

    variantCount.textContent = selectedColors.length + ' màu';

    selectedColors.forEach((input, index) => {
        const colorId = String(input.value);
        const colorName = input.dataset.name;

        // TẠO TAB MÀU
        const tab = document.createElement('button');

        tab.type = 'button';
        tab.dataset.colorId = colorId;

        tab.className =
            'px-4 py-2 rounded-lg border text-sm font-semibold transition';

        tab.textContent = colorName;

        tab.addEventListener('click', function () {
            showColorVariants(colorId);
        });

        variantColorTabs.appendChild(tab);

        // TẠO PANEL RIÊNG CHO TỪNG MÀU
        const panel = document.createElement('div');

        panel.dataset.colorPanel = colorId;

        if (index !== 0) {
            panel.classList.add('hidden');
        }

        sizes.forEach(size => {
            const sizeId = String(size.id);

            const oldVariant =
                variantValues[colorId]?.[sizeId] ?? {};

            const row = document.createElement('div');

            row.className =
                'grid grid-cols-3 items-center gap-3 px-4 py-3 hover:bg-stone-50';

            // KÍCH CỠ
            const sizeCell = document.createElement('span');

            sizeCell.className =
                'text-sm font-semibold text-stone-700';

            sizeCell.textContent = size.name;

            // GIÁ BÁN
            const priceInput = document.createElement('input');

            priceInput.type = 'number';
            priceInput.name =
                `variants[${colorId}][${sizeId}][Gia]`;

            priceInput.dataset.color = colorId;
            priceInput.dataset.size = sizeId;
            priceInput.dataset.field = 'Gia';

            priceInput.min = '0';
            priceInput.step = '1000';
            priceInput.required = true;
            priceInput.placeholder = 'Nhập giá';

            priceInput.value = oldVariant.Gia ?? '';

            priceInput.className =
                'w-full min-w-0 border border-stone-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-red-200';

            // SỐ LƯỢNG
            const quantityInput = document.createElement('input');

            quantityInput.type = 'number';
            quantityInput.name =
                `variants[${colorId}][${sizeId}][SoLuong]`;

            quantityInput.dataset.color = colorId;
            quantityInput.dataset.size = sizeId;
            quantityInput.dataset.field = 'SoLuong';

            quantityInput.min = '0';
            quantityInput.step = '1';
            quantityInput.required = true;
            quantityInput.placeholder = 'Số lượng';

            quantityInput.value = oldVariant.SoLuong ?? 0;

            quantityInput.className =
                'w-full min-w-0 border border-stone-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-amber-200';

            // CẬP NHẬT GIÁ TRỊ NGAY KHI NHẬP
            [priceInput, quantityInput].forEach(input => {
                input.addEventListener('input', function () {
                    saveVariantValues();
                });
            });

            row.appendChild(sizeCell);
            row.appendChild(priceInput);
            row.appendChild(quantityInput);

            panel.appendChild(row);
        });

        variantTable.appendChild(panel);
    });

    // MẶC ĐỊNH HIỂN THỊ MÀU ĐẦU TIÊN
    showColorVariants(selectedColors[0].value);
}
// LẮNG NGHE THAY ĐỔI MÀU VÀ DANH MỤC
document.querySelectorAll('.multi-select').forEach(dropdown => {
    dropdown.querySelectorAll('input[type="checkbox"]').forEach(input => {
        input.addEventListener('change', function () {
            updateMultiSelectLabel(dropdown);

            if (dropdown.id === 'colorSelect') {
                renderVariants();
            }
        });
    });

    updateMultiSelectLabel(dropdown);
});

renderVariants();
</script>

@endsection

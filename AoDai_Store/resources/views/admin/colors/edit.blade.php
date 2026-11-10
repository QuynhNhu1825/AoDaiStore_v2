@extends('layouts.admin')

@section('title', 'Chỉnh sửa màu sắc: ' . $color->TenMau)

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">

    {{-- TIÊU ĐỀ + QUAY LẠI --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa màu sắc
            </h2>
            <p class="text-xs text-stone-500 mt-1">Cập nhật thông tin màu sắc trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.colors.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-red-200 text-red-800 text-xs font-bold hover:bg-red-50 transition">
            <i class="fas fa-arrow-left"></i>Quay lại danh sách
        </a>
    </div>

    {{-- THÔNG BÁO LỖI --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs">
            <div class="font-bold mb-2"><i class="fas fa-exclamation-circle mr-1"></i>Vui lòng kiểm tra lại thông tin:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CHỈNH SỬA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-6 py-4 bg-red-50 border-b border-red-100">
            <h3 class="text-sm font-bold text-red-900 uppercase tracking-wider">
                <i class="fas fa-info-circle mr-2"></i>Thông tin màu sắc
            </h3>
            <p class="text-[11px] text-stone-500 mt-1">
                Mã màu: <span class="font-bold text-red-800">#{{ $color->MaMau }}</span>
            </p>
        </div>

        <form id="update-form" action="{{ route('admin.colors.update', $color->MaMau) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- TÊN MÀU --}}
                <div class="md:col-span-2">
                    <label for="TenMau" class="block text-xs font-bold text-stone-700 mb-2">
                        Tên màu <span class="text-red-600">*</span>
                    </label>
                    <input id="TenMau" type="text" name="TenMau"
                           value="{{ old('TenMau', $color->TenMau) }}" required maxlength="255"
                           placeholder="Ví dụ: Đỏ đô, Hồng phấn, Xanh ngọc..."
                           class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('TenMau') border-red-500 @enderror">
                    @error('TenMau')
                        <p class="text-red-600 text-[11px] mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- MÃ HEX --}}
                <div class="md:col-span-2">
                    <label for="MaHex" class="block text-xs font-bold text-stone-700 mb-2">Mã màu HEX</label>
                    <div class="flex items-center gap-3">
                        <input id="colorPicker" type="color"
                               value="{{ preg_match('/^#[0-9a-fA-F]{6}$/', old('MaHex', $color->MaHex ?? '')) ? old('MaHex', $color->MaHex) : '#800000' }}"
                               aria-label="Chọn màu"
                               class="w-12 h-11 shrink-0 rounded-lg border border-stone-200 cursor-pointer p-1 bg-white">
                        <input id="MaHex" type="text" name="MaHex"
                               value="{{ old('MaHex', $color->MaHex) }}" maxlength="7"
                               placeholder="#800000" autocomplete="off"
                               class="flex-1 min-w-0 px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('MaHex') border-red-500 @enderror">
                    </div>
                    @error('MaHex')
                        <p class="text-red-600 text-[11px] mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-stone-400 mt-2">Nhập dấu # và 6 ký tự HEX, ví dụ: #800000.</p>
                </div>

                {{-- XEM TRƯỚC --}}
                <div class="md:col-span-2 rounded-xl border border-red-100 bg-red-50/40 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-red-900 mb-3">Xem trước màu sắc</p>
                    <div class="flex items-center gap-3">
                        <div id="colorPreview" class="w-12 h-12 shrink-0 rounded-xl border border-stone-200 shadow-sm"></div>
                        <div>
                            <p class="text-xs font-semibold text-stone-700">Màu đang chọn</p>
                            <p id="hexPreview" class="text-[11px] text-stone-500 mt-1"></p>
                        </div>
                    </div>
                </div>

                {{-- THÔNG TIN KHÁC --}}
                <div class="rounded-xl bg-stone-50 border border-stone-100 p-3">
                    <p class="text-[10px] text-stone-400 mb-1">Mã màu</p>
                    <p class="text-xs font-bold text-stone-700">#{{ $color->MaMau }}</p>
                </div>
                <div class="rounded-xl bg-stone-50 border border-stone-100 p-3">
                    <p class="text-[10px] text-stone-400 mb-1">Ngày tạo</p>
                    <p class="text-xs font-bold text-stone-700">
                        {{ $color->NgayTao ? \Carbon\Carbon::parse($color->NgayTao)->format('d/m/Y H:i') : '---' }}
                    </p>
                </div>
            </div>

            {{-- LƯU Ý --}}
            <div class="mt-6 bg-red-50 p-4 rounded-xl flex items-start text-red-800 border border-red-100">
                <i class="fas fa-info-circle mr-3 mt-0.5"></i>
                <p class="text-[11px] leading-relaxed">
                    Lưu ý: Màu sắc có thể đang được sử dụng trong các biến thể sản phẩm.
                    Việc cập nhật màu sẽ ảnh hưởng đến thông tin hiển thị của những sản phẩm liên quan.
                </p>
            </div>

            {{-- NÚT CHỨC NĂNG --}}
            <div class="mt-7 pt-5 border-t border-red-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.colors.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                    <i class="fas fa-times mr-2"></i>Hủy bỏ
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-xl bg-red-800 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-900 shadow-sm transition">
                    <i class="fas fa-save mr-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('update-form');
    const picker = document.getElementById('colorPicker');
    const hexInput = document.getElementById('MaHex');
    const preview = document.getElementById('colorPreview');
    const hexPreview = document.getElementById('hexPreview');
    let confirmed = false;

    function updatePreview(value) {
        if (/^#[0-9a-fA-F]{6}$/.test(value)) {
            preview.style.backgroundColor = value;
            hexPreview.textContent = value.toUpperCase();
        }
    }

    updatePreview(/^#[0-9a-fA-F]{6}$/.test(hexInput.value.trim()) ? hexInput.value.trim() : picker.value);

    picker.addEventListener('input', function () {
        hexInput.value = this.value.toUpperCase();
        updatePreview(this.value);
    });

    hexInput.addEventListener('input', function () {
        const value = this.value.trim();
        if (/^#[0-9a-fA-F]{6}$/.test(value)) {
            picker.value = value;
            updatePreview(value);
        }
    });

    form.addEventListener('submit', function (event) {
        if (confirmed) return;
        event.preventDefault();
        if (!form.reportValidity()) return;

        if (typeof Swal === 'undefined') {
            if (window.confirm('Bạn có chắc chắn muốn lưu thay đổi màu sắc?')) {
                confirmed = true;
                form.requestSubmit();
            }
            return;
        }

        Swal.fire({
            title: 'Xác nhận cập nhật',
            text: 'Bạn có chắc chắn muốn lưu thay đổi cho màu sắc "' + document.getElementById('TenMau').value + '" không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Lưu thay đổi',
            cancelButtonText: 'Hủy bỏ',
            buttonsStyling: false,
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-save-confirm',
                cancelButton: 'btn-save-cancel'
            },
            reverseButtons: true
        }).then(function (result) {
            if (result.isConfirmed) {
                confirmed = true;
                form.requestSubmit();
            }
        });
    });
});
</script>

<style>
.glass-popup {
    background: rgba(255, 255, 255, 0.96) !important;
    backdrop-filter: blur(10px);
    border-radius: 24px !important;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.btn-save-confirm {
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
.btn-save-cancel {
    background-color: #f3f4f6 !important;
    color: #4b5563 !important;
    padding: 10px 25px !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-size: 11px !important;
}
</style>
@endsection

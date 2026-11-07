@extends('layouts.admin')

@section('title', 'Thêm màu sắc mới')

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">

    {{-- Quay lại --}}
    <div>
        <a href="{{ route('admin.colors.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold text-red-800 hover:text-red-600 transition">
            <i class="fas fa-arrow-left"></i>
            Quay lại danh sách màu sắc
        </a>
    </div>

    {{-- Form thêm màu --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-red-100 bg-red-50/50">
            <h2 class="text-sm font-bold text-red-900 uppercase tracking-wide">
                Thêm màu sắc mới
            </h2>
            <p class="text-[11px] text-stone-500 mt-1">
                Nhập tên màu và chọn mã màu hiển thị.
            </p>
        </div>

        <form action="{{ route('admin.colors.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-5">

                {{-- Tên màu --}}
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-2">
                        Tên màu <span class="text-red-600">*</span>
                    </label>

                    <input type="text"
                           name="TenMau"
                           value="{{ old('TenMau') }}"
                           required
                           maxlength="255"
                           placeholder="Ví dụ: Đỏ đô, Xanh ngọc, Vàng nhạt..."
                           class="w-full px-4 py-3 text-xs rounded-xl border border-stone-200 bg-stone-50 outline-none transition focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('TenMau') border-red-500 @enderror">

                    @error('TenMau')
                        <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mã HEX --}}
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-2">
                        Mã màu HEX
                    </label>

                    <div class="flex items-center gap-3">
                        <input type="color"
                               id="colorPicker"
                               value="{{ old('MaHex', '#800000') }}"
                               class="w-12 h-11 rounded-lg border border-stone-200 cursor-pointer p-1 bg-white">

                        <input type="text"
                               name="MaHex"
                               id="MaHex"
                               value="{{ old('MaHex', '#800000') }}"
                               maxlength="7"
                               placeholder="#800000"
                               class="flex-1 px-4 py-3 text-xs rounded-xl border border-stone-200 bg-stone-50 outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('MaHex') border-red-500 @enderror">
                    </div>

                    @error('MaHex')
                        <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-[10px] text-stone-400 mt-2">
                        Nhập mã HEX gồm dấu # và 6 ký tự, ví dụ #800000.
                    </p>
                </div>

                {{-- Xem trước --}}
                <div class="p-4 rounded-xl border border-red-100 bg-red-50/40">
                    <p class="text-[10px] font-bold uppercase text-red-900 mb-3">
                        Xem trước màu sắc
                    </p>

                    <div class="flex items-center gap-3">
                        <div id="colorPreview"
                             class="w-12 h-12 rounded-xl border border-stone-200 shadow-sm"
                             style="background-color: {{ old('MaHex', '#800000') }}">
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-stone-700">Màu được chọn</p>
                            <p id="hexPreview" class="text-[11px] text-stone-500 mt-1">
                                {{ old('MaHex', '#800000') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Nút --}}
            <div class="mt-8 pt-5 border-t border-stone-100 flex flex-col sm:flex-row gap-3">

                <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl bg-red-800 text-white text-xs font-bold hover:bg-red-900 transition shadow-sm">
                    <i class="fas fa-save"></i>
                    Lưu màu sắc
                </button>

                <a href="{{ route('admin.colors.index') }}"
                   class="inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                    <i class="fas fa-times"></i>
                    Hủy
                </a>

            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.getElementById('colorPicker');
    const hexInput = document.getElementById('MaHex');
    const preview = document.getElementById('colorPreview');
    const hexPreview = document.getElementById('hexPreview');

    function updatePreview(value) {
        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            preview.style.backgroundColor = value;
            hexPreview.textContent = value;
        }
    }

    picker.addEventListener('input', function () {
        hexInput.value = this.value.toUpperCase();
        updatePreview(this.value);
    });

    hexInput.addEventListener('input', function () {
        const value = this.value.trim();

        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            picker.value = value;
            updatePreview(value);
        }
    });
});
</script>
@endsection

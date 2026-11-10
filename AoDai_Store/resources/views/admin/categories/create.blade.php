@extends('layouts.admin')

@section('title', 'Thêm Danh Mục Mới')

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4 min-w-0">

    {{-- TIÊU ĐỀ + QUAY LẠI --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-layer-group mr-2"></i>
                Thêm danh mục mới
            </h2>
            <p class="text-xs text-stone-500 mt-1">
                Nhập thông tin để thêm danh mục sản phẩm vào hệ thống.
            </p>
        </div>

        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-red-200 text-red-800 text-xs font-bold hover:bg-red-50 transition">
            <i class="fas fa-arrow-left"></i>
            Quay lại danh sách
        </a>
    </div>

    {{-- THÔNG BÁO LỖI --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs" role="alert">
            <div class="font-bold mb-2">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Vui lòng kiểm tra lại thông tin:
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM THÊM DANH MỤC --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 bg-red-50 border-b border-red-100">
            <h3 class="text-sm font-bold text-red-900 uppercase tracking-wider">
                <i class="fas fa-info-circle mr-2"></i>
                Thông tin danh mục
            </h3>
            <p class="text-[11px] text-stone-500 mt-1">
                Các trường có dấu (*) là thông tin bắt buộc.
            </p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf

            <div class="grid grid-cols-1 gap-5">
                {{-- TÊN DANH MỤC --}}
                <div>
                    <label for="TenDanhMuc" class="block text-xs font-bold text-stone-700 mb-2">
                        Tên danh mục <span class="text-red-600">*</span>
                    </label>
                    <input type="text"
                           id="TenDanhMuc"
                           name="TenDanhMuc"
                           value="{{ old('TenDanhMuc') }}"
                           required maxlength="255"
                           placeholder="Ví dụ: Áo dài cưới, Áo dài cách tân..."
                           aria-invalid="{{ $errors->has('TenDanhMuc') ? 'true' : 'false' }}"
                           class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('TenDanhMuc') border-red-500 @enderror">
                    @error('TenDanhMuc')
                        <p class="text-red-600 text-[11px] mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- MÔ TẢ --}}
                <div>
                    <label for="MoTa" class="block text-xs font-bold text-stone-700 mb-2">
                        Mô tả danh mục <span class="text-red-600">*</span>
                    </label>
                    <textarea id="MoTa"
                              name="MoTa"
                              rows="5"
                              required
                              placeholder="Nhập mô tả về danh mục sản phẩm..."
                              aria-invalid="{{ $errors->has('MoTa') ? 'true' : 'false' }}"
                              class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none resize-y transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('MoTa') border-red-500 @enderror">{{ old('MoTa') }}</textarea>
                    @error('MoTa')
                        <p class="text-red-600 text-[11px] mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- NÚT CHỨC NĂNG --}}
            <div class="mt-7 pt-5 border-t border-red-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-xl bg-red-800 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-900 shadow-sm transition">
                    <i class="fas fa-save mr-2"></i>
                    Lưu danh mục
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

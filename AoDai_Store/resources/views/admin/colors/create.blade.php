@extends('layouts.admin')

@section('title', 'Thêm Loại Màu Mới')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Nút quay lại --}}
    <div class="mb-4">
        <a href="{{ route('admin.colors.index') }}"
           class="text-indigo-600 hover:underline flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách loại màu
        </a>
    </div>

    {{-- Nội dung --}}
    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <form action="{{ route('admin.colors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Tên loại màu --}}
                <div class="col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Tên loại màu
                    </label>
                    <input type="text" name="TenLoaiMau"
                        value="{{ old('TenLoaiMau') }}" required
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none
                        @error('TenLoaiMau') border-red-500 @enderror"
                        placeholder="Ví dụ: Đỏ, Xanh Ngọc, Vàng Nhạt...">

                    @error('TenLoaiMau')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Hình ảnh màu --}}
                <div class="col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Hình ảnh màu
                    </label>
                    <input type="file" name="HinhAnhMau"
                        accept="image/*"
                        class="w-full border rounded-lg p-2.5">
                </div>

                {{-- Mô tả --}}
                <div class="col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Mô tả
                    </label>
                    <textarea name="MoTa" rows="5"
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"
                        placeholder="Mô tả đặc điểm của màu sắc...">{{ old('MoTa') }}</textarea>
                </div>

                {{-- Trạng thái --}}
                <div class="col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Trạng thái
                    </label>
                    <select name="TrangThai"
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="1" {{ old('TrangThai', 1) == 1 ? 'selected' : '' }}>
                            Hoạt động
                        </option>
                        <option value="0" {{ old('TrangThai') == 0 ? 'selected' : '' }}>
                            Ẩn
                        </option>
                    </select>
                </div>

            </div>

            {{-- Nút lưu --}}
            <div class="mt-8 border-t pt-6">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold
                           hover:bg-indigo-700 shadow-lg transition-all
                           flex justify-center items-center uppercase tracking-widest">
                    <i class="fas fa-save mr-2"></i> Lưu loại màu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

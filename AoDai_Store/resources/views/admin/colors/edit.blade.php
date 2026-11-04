@extends('layouts.admin')

@section('title', 'Chỉnh sửa loại màu: ' . $color->TenLoaiMau)

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Nút quay lại --}}
    <div class="mb-4">
        <a href="{{ route('admin.colors.index') }}"
           class="text-indigo-600 hover:underline flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách loại màu
        </a>
    </div>

    {{-- Thẻ nội dung --}}
    <div class="bg-white p-8 rounded-xl shadow-lg border-t-4 border-indigo-600">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">
                Cập nhật thông tin loại màu
            </h2>
            <p class="text-xs text-gray-500 mt-1 italic">
                Mã loại màu: #{{ $color->MaLoaiMau }}
            </p>
        </div>

        <form action="{{ route('admin.colors.update', $color->MaLoaiMau) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">

                {{-- Tên loại màu --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Tên loại màu
                    </label>
                    <input type="text" name="TenLoaiMau"
                           value="{{ old('TenLoaiMau', $color->TenLoaiMau) }}" required
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition
                           @error('TenLoaiMau') border-red-500 @enderror"
                           placeholder="Ví dụ: Đỏ đô, Xanh ngọc...">

                    @error('TenLoaiMau')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Hình ảnh màu --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Hình ảnh màu
                    </label>

                    @if($color->HinhAnhMau)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $color->HinhAnhMau) }}"
                                 class="h-20 rounded-lg border">
                        </div>
                    @endif

                    <input type="file" name="HinhAnhMau"
                           accept="image/*"
                           class="w-full border rounded-lg p-2.5">
                </div>

                {{-- Mô tả --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Mô tả
                    </label>
                    <textarea name="MoTa" rows="5"
                              class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition shadow-sm"
                              placeholder="Mô tả chi tiết về màu sắc...">{{ old('MoTa', $color->MoTa) }}</textarea>
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Trạng thái
                    </label>
                    <select name="TrangThai"
                            class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="1" {{ old('TrangThai', $color->TrangThai) == 1 ? 'selected' : '' }}>
                            Hoạt động
                        </option>
                        <option value="0" {{ old('TrangThai', $color->TrangThai) == 0 ? 'selected' : '' }}>
                            Ẩn
                        </option>
                    </select>
                </div>
            </div>

            {{-- Nút --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-4 border-t pt-6">
                <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-3.5 rounded-lg font-bold
                               hover:bg-indigo-700 shadow-lg hover:shadow-indigo-200 transition-all
                               flex justify-center items-center uppercase tracking-widest">
                    <i class="fas fa-sync-alt mr-2"></i> Lưu thay đổi
                </button>

                <a href="{{ route('admin.colors.index') }}"
                   class="px-10 py-3.5 border border-gray-300 text-gray-500 rounded-lg
                          hover:bg-gray-50 transition font-bold text-center">
                    Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-indigo-50 p-4 rounded-lg flex items-center text-indigo-700 border border-indigo-100">
        <i class="fas fa-info-circle mr-3"></i>
        <p class="text-xs italic">
            Lưu ý: Thay đổi loại màu có thể ảnh hưởng đến các sản phẩm đang sử dụng màu này.
        </p>
    </div>
</div>
@endsection

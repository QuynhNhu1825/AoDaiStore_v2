@extends('layouts.admin')

@section('title', 'Thêm Chất Liệu Mới')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Nút quay lại danh sách --}}
    <div class="mb-4">
        <a href="{{ route('admin.materials.index') }}" class="text-blue-600 hover:underline flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    {{-- Thẻ nội dung chính --}}
    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <form action="{{ route('admin.materials.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Tên chất liệu --}}
                <div class="col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">Tên chất liệu</label>
                    <input type="text" name="TenChatLieu" value="{{ old('TenChatLieu') }}" required
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none
                        @error('TenChatLieu') border-red-500 @enderror"
                        placeholder="Ví dụ: Lụa tơ tằm, Gấm, Ren...">

                    @error('TenChatLieu')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Xuất xứ --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Xuất xứ</label>
                    <input type="text" name="Xuatxu" value="{{ old('Xuatu') }}"
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Ví dụ: Việt Nam, Nhật Bản...">
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Trạng thái</label>
                    <select name="TrangThai"
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="1" {{ old('TrangThai') == 1 ? 'selected' : '' }}>Đang sử dụng</option>
                        <option value="0" {{ old('TrangThai') == 0 ? 'selected' : '' }}>Ngưng sử dụng</option>
                    </select>
                </div>

                {{-- Hướng dẫn bảo quản --}}
                <div class="col-span-2">
                    <label class="block font-semibold mb-2 text-gray-700">Hướng dẫn bảo quản</label>
                    <textarea name="HuongDanBaoQuan" rows="5"
                        class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Ví dụ: Giặt tay, tránh ánh nắng trực tiếp...">{{ old('HuongDanBaoQuan') }}</textarea>
                </div>

            </div>

            <div class="mt-8 border-t pt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition-all flex justify-center items-center uppercase tracking-widest">
                    <i class="fas fa-save mr-2"></i> Lưu chất liệu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

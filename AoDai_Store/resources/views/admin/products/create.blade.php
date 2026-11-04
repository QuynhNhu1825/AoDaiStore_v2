@extends('layouts.admin')

@section('title', 'Thêm Áo Dài Mới')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-stone-900"></div>

        <h2 class="serif text-2xl font-bold text-stone-800 mb-8 italic">Thiết kế mẫu Áo Dài mới</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Tên sản phẩm thiết kế</label>
                    <input type="text" name="TenSanPham" value="{{ old('TenSanPham') }}" required
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none @error('TenSanPham') border-red-500 @enderror text-sm font-medium" 
                           placeholder="Ví dụ: Áo Dài Tứ Thân Cổ Điển...">
                    @error('TenSanPham') <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Phân loại mẫu</label>
                    <select name="MaLoaiSP" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer" required>
                        <option value="">-- Chọn loại --</option>
                        @foreach($categories as $item)
                            <option value="{{ $item->MaLoaiSP }}" {{ old('MaLoaiSP') == $item->MaLoaiSP ? 'selected' : '' }}>
                                {{ $item->TenLoaiSP }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Chất liệu vải</label>
                    <select name="MaChatLieu" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer" required>
                        <option value="">-- Chọn chất liệu --</option>
                        @foreach($materials as $item)
                            <option value="{{ $item->MaChatLieu }}" {{ old('MaChatLieu') == $item->MaChatLieu ? 'selected' : '' }}>
                                {{ $item->TenChatLieu }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Giá niêm yết (VNĐ)</label>
                    <input type="number" name="GiaBan" value="{{ old('GiaBan') }}" required
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm font-bold text-red-900" placeholder="0">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Tông màu chủ đạo</label>
                    <select name="MaLoaiMau" required class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer">
                        <option value="">-- Chọn màu sắc --</option>
                        @foreach($colors as $item)
                            <option value="{{ $item->MaLoaiMau }}" {{ old('MaLoaiMau') == $item->MaLoaiMau ? 'selected' : '' }}>
                              {{ $item->TenLoaiMau }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-3 text-stone-500">Bảng kích cỡ & Số lượng tồn kho</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-stone-50 p-6 rounded-2xl border border-stone-100">
                        @foreach ($sizes as $item)
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-[10px] text-stone-400 uppercase tracking-tighter">{{ $item->TenSize }}</span>
                                <input type="number" name="sizes[{{ $item->MaSize }}]" min="0"
                                       value="{{ old('sizes.' . $item->MaSize, 0) }}"
                                       class="bg-white border border-stone-100 rounded-lg p-2 w-full focus:ring-2 focus:ring-stone-200 outline-none text-sm font-mono">
                            </div>
                        @endforeach
                    </div>
                    @error('sizes') <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Xưởng / Nhà cung cấp</label>
                    <select name="MaNCC" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer">
                        <option value="">-- Chọn xưởng sản xuất --</option>
                        @foreach($suppliers as $item)
                            <option value="{{ $item->MaNCC }}" {{ old('MaNCC') == $item->MaNCC ? 'selected' : '' }}>
                                {{ $item->TenNCC }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 md:col-span-1 border-2 border-dashed border-stone-200 rounded-2xl p-4 hover:border-stone-400 transition-colors bg-stone-50/30">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Ảnh đại diện chính</label>
                    <input type="file" name="HinhAnh" 
                           class="w-full text-xs text-stone-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-stone-900 file:text-white hover:file:bg-red-900 cursor-pointer">
                    <p class="text-[9px] text-stone-400 mt-2 italic">* Hiển thị tại ảnh bìa danh mục.</p>
                </div>

                <div class="col-span-2 border-2 border-dashed border-stone-200 rounded-2xl p-6 bg-stone-50/30 hover:border-red-200 transition-colors">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-red-800">Album ảnh chi tiết thiết kế</label>
                    <input type="file" name="AlbumHinh[]" multiple
                           class="w-full text-xs text-stone-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-red-800 file:text-white hover:file:bg-red-900 cursor-pointer">
                    <p class="text-[9px] text-stone-400 mt-2 italic">* Giữ phím Ctrl để chọn nhiều tấm hình chi tiết.</p>
                </div>
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Cảm hứng & Chi tiết thiết kế</label>
                    <textarea name="MoTa" rows="4" 
                              class="w-full bg-stone-50 border border-stone-100 rounded-xl p-4 focus:ring-2 focus:ring-stone-200 outline-none text-sm" 
                              placeholder="Mô tả chất liệu, đường may, ý nghĩa thiết kế...">{{ old('MoTa') }}</textarea>
                </div>
            </div>

            <div class="mt-12">
                <button type="submit" class="w-full bg-stone-900 text-white py-4 rounded-xl font-bold hover:bg-red-900 shadow-xl transition-all duration-300 flex justify-center items-center uppercase tracking-[0.2em] text-xs">
                    <i class="fas fa-save mr-2 text-sm"></i> Khởi tạo mẫu thiết kế
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .serif { font-family: 'Playfair Display', serif; }
</style>
@endsection
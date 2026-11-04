@extends('layouts.admin')

@section('title', 'Chỉnh sửa: ' . $product->TenSanPham)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-stone-900"></div>

        <h2 class="serif text-2xl font-bold text-stone-800 mb-8 italic">Hiệu chỉnh thiết kế: {{ $product->TenSanPham }}</h2>

        <form action="{{ route('admin.products.update', $product->MaSanPham) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Tên sản phẩm</label>
                    <input type="text" name="TenSanPham" value="{{ old('TenSanPham', $product->TenSanPham) }}" 
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm font-medium">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Phân loại mẫu</label>
                    <select name="MaLoaiSP" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer">
                        @foreach($categories as $item)
                            <option value="{{ $item->MaLoaiSP }}" 
                                {{ $product->MaLoaiSP == $item->MaLoaiSP ? 'selected' : '' }}>
                                {{ $item->TenLoaiSP }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Chất liệu vải</label>
                    <select name="MaChatLieu" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer">
                        @foreach($materials as $item)
                            <option value="{{ $item->MaChatLieu }}"
                                {{ $product->MaChatLieu == $item->MaChatLieu ? 'selected' : '' }}>
                                {{ $item->TenChatLieu }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Giá bán (VNĐ)</label>
                    <input type="number" name="GiaBan" value="{{ old('GiaBan', $product->GiaBan) }}" 
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm font-bold text-red-900">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Màu sắc chủ đạo</label>
                    <select name="MaLoaiMau" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer">
                        @foreach($colors as $item)
                            <option value="{{ $item->MaLoaiMau }}"
                                {{ $product->MaLoaiMau == $item->MaLoaiMau ? 'selected' : '' }}>
                                {{ $item->TenLoaiMau }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-3 text-stone-500">Bảng kích cỡ & Số lượng hiện tại</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-stone-50 p-6 rounded-2xl border border-stone-100">
                        @foreach ($sizes as $item)
                            @php
                                $pivotSize = $product->sizes->firstWhere('MaSize', $item->MaSize);
                            @endphp
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-[10px] text-stone-400 uppercase tracking-tighter">{{ $item->TenSize }}</span>
                                <input type="number" name="sizes[{{ $item->MaSize }}]" min="0"
                                    value="{{ old('sizes.' . $item->MaSize, $pivotSize ? $pivotSize->pivot->SoLuong : 0) }}"
                                    class="bg-white border border-stone-100 rounded-lg p-2 w-full focus:ring-2 focus:ring-stone-200 outline-none text-sm font-mono">
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Xưởng sản xuất</label>
                    <select name="MaNCC" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm">
                        @foreach($suppliers as $item)
                            <option value="{{ $item->MaNCC }}"
                                {{ $product->MaNCC == $item->MaNCC ? 'selected' : '' }}>
                                {{ $item->TenNCC }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Trạng thái kinh doanh</label>
                    <select name="TrangThai" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm">
                        <option value="1" {{ $product->TrangThai == 1 ? 'selected' : '' }}>Đang kinh doanh</option>
                        <option value="0" {{ $product->TrangThai == 0 ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-3 text-stone-500">Hình ảnh đại diện</label>
                    <div class="flex items-center space-x-6 p-6 border border-stone-100 rounded-2xl bg-stone-50/50">
                        <div class="text-center">
                            <img src="{{ asset('img/products/' . $product->HinhAnh) }}" 
                                 class="w-24 h-32 object-cover rounded-xl shadow-md border-2 border-white"
                                 onerror="this.src='https://placehold.co/400x600?text=No+Image'">
                            <span class="block text-[9px] text-stone-400 mt-2 font-bold uppercase tracking-widest">Hiện tại</span>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="HinhAnh" class="block w-full text-xs text-stone-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-[10px] file:font-bold
                                file:bg-stone-900 file:text-white
                                hover:file:bg-red-900 cursor-pointer">
                            <p class="text-[10px] text-stone-400 mt-2 italic">* Để trống nếu giữ nguyên thiết kế ảnh cũ.</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-3 text-stone-500">Album ảnh chi tiết (Thay thế & Xóa)</label>
                    <div class="bg-stone-50/50 p-6 rounded-2xl border border-stone-100">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                            @foreach($product->hinhanhsanpham as $hinh)
                                <div class="bg-white p-3 rounded-xl border border-stone-100 shadow-sm relative group">
                                    <img src="{{ asset('img/details/' . $hinh->TenHinh) }}" 
                                         class="w-full h-32 object-cover rounded-lg mb-3">
                                    
                                    <div class="space-y-2">
                                        <label class="block text-[9px] font-bold text-stone-400 uppercase">Thay bằng ảnh mới:</label>
                                        <input type="file" name="replace_images[{{ $hinh->ID }}]" 
                                               class="block w-full text-[9px] text-stone-400 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:bg-stone-100 file:text-stone-700">
                                        
                                        <label class="flex items-center mt-2 cursor-pointer group-hover:text-red-600 transition-colors">
                                            <input type="checkbox" name="delete_images[]" value="{{ $hinh->ID }}" class="w-3 h-3 rounded border-stone-300 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-[9px] font-bold uppercase tracking-widest">Gỡ bỏ ảnh</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-2 border-dashed border-stone-200 p-6 rounded-xl bg-white text-center">
                            <p class="text-[10px] text-stone-400 font-bold uppercase tracking-widest mb-3">Thêm ảnh mới vào Album</p>
                            <input type="file" name="AlbumHinh[]" multiple
                                   class="inline-block w-full text-xs text-stone-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-[10px] file:font-bold
                                    file:bg-stone-100 file:text-stone-700
                                    hover:file:bg-stone-200 cursor-pointer text-center">
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Cảm hứng & Chi tiết mô tả</label>
                    <textarea name="MoTa" rows="4" 
                              class="w-full bg-stone-50 border border-stone-100 rounded-xl p-4 focus:ring-2 focus:ring-stone-200 outline-none text-sm">{{ old('MoTa', $product->MoTa) }}</textarea>
                </div>
            </div>

            <div class="mt-12 flex gap-4">
                <button type="submit" class="flex-1 bg-stone-900 text-white py-4 rounded-xl font-bold hover:bg-red-900 shadow-xl transition-all duration-300 uppercase tracking-[0.2em] text-xs">
                    <i class="fas fa-sync-alt mr-2"></i> Lưu thay đổi thiết kế
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-8 py-4 bg-stone-100 text-stone-600 rounded-xl hover:bg-stone-200 transition-all font-bold uppercase tracking-widest text-xs flex items-center">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .serif { font-family: 'Playfair Display', serif; }
</style>
@endsection
@extends('layouts.admin')

@section('title', 'Thêm Danh Mục Mới')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-stone-900"></div>

        <h2 class="serif text-2xl font-bold text-stone-800 mb-8 italic">Khởi tạo danh mục sản phẩm mới</h2>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-8">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Tên danh mục (Tên Loại)</label>
                    <input type="text" name="TenLoaiSP" value="{{ old('TenLoaiSP') }}" required
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3.5 focus:ring-2 focus:ring-stone-200 outline-none @error('TenLoaiSP') border-red-500 @enderror text-sm font-medium" 
                           placeholder="Ví dụ: Áo Dài Cưới, Áo Dài Cách Tân...">
                    
                    @error('TenLoaiSP') 
                        <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Mô tả & Ý nghĩa danh mục</label>
                    <textarea name="MoTa" rows="6" 
                              class="w-full bg-stone-50 border border-stone-100 rounded-xl p-4 focus:ring-2 focus:ring-stone-200 outline-none text-sm leading-relaxed" 
                              placeholder="Nhập thông tin mô tả chi tiết về loại sản phẩm này để hỗ trợ khách hàng tìm kiếm tốt hơn...">{{ old('MoTa') }}</textarea>
                </div>
            </div>

            <div class="mt-12">
                <button type="submit" class="w-full bg-stone-900 text-white py-4 rounded-xl font-bold hover:bg-red-900 shadow-xl transition-all duration-300 flex justify-center items-center uppercase tracking-[0.2em] text-xs">
                    <i class="fas fa-save mr-2 text-sm"></i> Lưu danh mục thiết kế
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap');
    .serif { font-family: 'Playfair Display', serif; }
</style>
@endsection
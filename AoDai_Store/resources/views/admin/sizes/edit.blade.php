@extends('layouts.admin')

@section('title', 'Chỉnh sửa Kích cỡ: ' . $size->TenSize)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.sizes.index') }}" class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách kích cỡ
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-stone-900"></div>

        <h2 class="serif text-2xl font-bold text-stone-800 mb-8 italic">Hiệu chỉnh Kích cỡ: {{ $size->TenSize }}</h2>

        <form action="{{ route('admin.sizes.update', $size->MaSize) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-1">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Mã định danh hệ thống</label>
                    <input type="text" value="#{{ $size->MaSize }}" disabled
                           class="w-full bg-stone-100 border border-stone-100 rounded-xl p-3 text-sm font-mono text-stone-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Trạng thái sử dụng</label>
                    <select name="TrangThai" class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm cursor-pointer">
                        <option value="1" {{ $size->TrangThai == 1 ? 'selected' : '' }}>Đang áp dụng</option>
                        <option value="0" {{ $size->TrangThai == 0 ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Tên kích cỡ (Ký hiệu)</label>
                    <input type="text" name="TenSize" 
                           value="{{ old('TenSize', $size->TenSize) }}" required
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3 focus:ring-2 focus:ring-stone-200 outline-none text-sm font-medium transition @error('TenSize') border-red-300 @enderror" 
                           placeholder="VD: S, M, L, XL, XXL...">
                    
                    @error('TenSize') 
                        <span class="text-red-800 text-[10px] mt-1 font-bold uppercase tracking-tight">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Thông số chi tiết (Gợi ý cân nặng/số đo)</label>
                    <textarea name="MoTa" rows="4" 
                              class="w-full bg-stone-50 border border-stone-100 rounded-xl p-4 focus:ring-2 focus:ring-stone-200 outline-none text-sm shadow-sm transition" 
                              placeholder="Nhập thông số mô tả cho size này (VD: Dưới 45kg, 45kg - 52kg...)">{{ old('MoTa', $size->MoTa) }}</textarea>
                </div>
            </div>

            <div class="mt-12 flex gap-4 border-t border-stone-50 pt-8">
                <button type="submit" class="flex-1 bg-stone-900 text-white py-4 rounded-xl font-bold hover:bg-red-900 shadow-xl transition-all duration-300 uppercase tracking-[0.2em] text-xs">
                    <i class="fas fa-sync-alt mr-2"></i> Lưu cập nhật kích cỡ
                </button>
                <a href="{{ route('admin.sizes.index') }}" class="px-8 py-4 bg-stone-100 text-stone-600 rounded-xl hover:bg-stone-200 transition-all font-bold uppercase tracking-widest text-xs flex items-center justify-center">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 flex items-center text-stone-400 px-4">
        <i class="fas fa-info-circle mr-3 text-xs"></i>
        <p class="text-[10px] italic tracking-wide">Lưu ý: Thay đổi kích cỡ sẽ ảnh hưởng trực tiếp đến việc chọn size của khách hàng khi mua sản phẩm.</p>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&display=swap');
    .serif { font-family: 'Playfair Display', serif; }
</style>
@endsection
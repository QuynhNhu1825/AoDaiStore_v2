@extends('layouts.admin')

@section('title', 'Thêm Kích Cỡ Mới')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.sizes.index') }}" class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách size
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-stone-900"></div>

        <h2 class="serif text-2xl font-bold text-stone-800 mb-8 italic">Khởi tạo thông số kích cỡ mới</h2>

        <form action="{{ route('admin.sizes.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-8">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Tên kích cỡ (Ví dụ: S, M, L, XL hoặc Số)</label>
                    <input type="text" name="TenSize" value="{{ old('TenSize') }}" required
                           class="w-full bg-stone-50 border border-stone-100 rounded-xl p-3.5 focus:ring-2 focus:ring-stone-200 outline-none @error('TenSize') border-red-500 @enderror text-sm font-medium" 
                           placeholder="Nhập ký hiệu size...">
                    
                    @error('TenSize') 
                        <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">Ghi chú & Thông số chi tiết</label>
                    <textarea name="MoTa" rows="6" 
                              class="w-full bg-stone-50 border border-stone-100 rounded-xl p-4 focus:ring-2 focus:ring-stone-200 outline-none text-sm leading-relaxed" 
                              placeholder="Ví dụ: Dành cho người từ 45kg - 50kg, vòng ngực từ 82-84cm...">{{ old('MoTa') }}</textarea>
                    <p class="mt-2 text-[10px] text-stone-400 italic">Ghi chú này giúp quản trị viên dễ dàng tư vấn cho khách hàng về độ vừa vặn.</p>
                </div>
            </div>

            <div class="mt-12">
                <button type="submit" class="w-full bg-stone-900 text-white py-4 rounded-xl font-bold hover:bg-red-900 shadow-xl transition-all duration-300 flex justify-center items-center uppercase tracking-[0.2em] text-xs">
                    <i class="fas fa-save mr-2 text-sm"></i> Lưu thông số kích cỡ
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
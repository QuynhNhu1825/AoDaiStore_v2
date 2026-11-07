
@extends('layouts.admin')

@section('title', 'Thêm Kích Cỡ Mới')

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">

    {{-- QUAY LẠI --}}
    <div>
        <a href="{{ route('admin.sizes.index') }}"
           class="text-stone-500 hover:text-red-800 flex items-center transition-colors font-bold uppercase text-[10px] tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại danh sách size
        </a>
    </div>

    {{-- FORM THÊM SIZE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">

        {{-- TIÊU ĐỀ --}}
        <div class="bg-red-50 px-6 py-5 border-b border-red-100">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-white rounded-xl text-red-800 shadow-sm border border-red-100">
                    <i class="fas fa-ruler-combined fa-lg"></i>
                </div>

                <div>
                    <h2 class="serif text-xl font-bold text-red-900">
                        Thêm kích cỡ mới
                    </h2>
                    <p class="text-[10px] text-stone-500 mt-1">
                        Nhập thông tin kích cỡ để thêm vào hệ thống
                    </p>
                </div>
            </div>
        </div>

        {{-- NỘI DUNG FORM --}}
        <form action="{{ route('admin.sizes.store') }}"
              method="POST"
              class="p-6">

            @csrf

            <div class="max-w-xl">

                <label for="TenSize"
                       class="block text-[10px] uppercase tracking-widest font-bold mb-2 text-stone-500">
                    Tên kích cỡ
                    <span class="text-red-600">*</span>
                </label>

                <input
                    type="text"
                    id="TenSize"
                    name="TenSize"
                    value="{{ old('TenSize') }}"
                    maxlength="20"
                    required
                    autofocus
                    placeholder="Ví dụ: S, M, L, XL, XXL..."
                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-700 font-medium outline-none transition-all focus:border-red-700 focus:ring-0 @error('TenSize') border-red-500 @enderror"
                >

                @error('TenSize')
                    <p class="text-red-500 text-[10px] mt-2 font-bold">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror

                <p class="text-[10px] text-stone-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Tên kích cỡ không được vượt quá 20 ký tự.
                </p>

            </div>

            {{-- NÚT THAO TÁC --}}
            <div class="mt-8 pt-5 border-t border-stone-100 flex flex-col sm:flex-row justify-end gap-2">

                <a href="{{ route('admin.sizes.index') }}"
                   class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest bg-stone-100 text-stone-600 hover:bg-stone-200 transition-all">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest bg-red-800 text-white hover:bg-red-900 shadow-sm transition-all">
                    <i class="fas fa-save mr-2"></i>
                    Lưu kích cỡ
                </button>

            </div>

        </form>
    </div>

</div>

<style>
    .serif {
        font-family: 'Playfair Display', serif;
    }
</style>
@endsection

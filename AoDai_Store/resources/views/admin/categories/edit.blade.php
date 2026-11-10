@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục: ' . $category->TenDanhMuc)

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">

    {{-- TIÊU ĐỀ + QUAY LẠI --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa danh mục
            </h2>
            <p class="text-xs text-stone-500 mt-1">Cập nhật thông tin danh mục sản phẩm trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-red-200 text-red-800 text-xs font-bold hover:bg-red-50 transition">
            <i class="fas fa-arrow-left"></i>Quay lại danh sách
        </a>
    </div>

    {{-- THÔNG BÁO LỖI --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs" role="alert">
            <div class="font-bold mb-2"><i class="fas fa-exclamation-circle mr-1"></i>Vui lòng kiểm tra lại thông tin:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CHỈNH SỬA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 bg-red-50 border-b border-red-100">
            <h3 class="text-sm font-bold text-red-900 uppercase tracking-wider">
                <i class="fas fa-info-circle mr-2"></i>Thông tin danh mục
            </h3>
            <p class="text-[11px] text-stone-500 mt-1">
                Mã danh mục: <span class="font-bold text-red-800">#{{ $category->MaDanhMuc }}</span>
            </p>
        </div>

        <form id="update-category-form"
              action="{{ route('admin.categories.update', $category->MaDanhMuc) }}"
              method="POST" class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- TÊN DANH MỤC --}}
                <div class="md:col-span-2">
                    <label for="TenDanhMuc" class="block text-xs font-bold text-stone-700 mb-2">
                        Tên danh mục <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="TenDanhMuc" name="TenDanhMuc"
                           value="{{ old('TenDanhMuc', $category->TenDanhMuc) }}"
                           required maxlength="255"
                           placeholder="Ví dụ: Áo dài truyền thống, Áo dài cách tân..."
                           class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('TenDanhMuc') border-red-500 @enderror">
                    @error('TenDanhMuc')
                        <p class="text-red-600 text-[11px] mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- MÔ TẢ --}}
                <div class="md:col-span-2">
                    <label for="MoTa" class="block text-xs font-bold text-stone-700 mb-2">
                        Mô tả danh mục <span class="text-red-600">*</span>
                    </label>
                    <textarea id="MoTa" name="MoTa" rows="5" required
                              placeholder="Nhập mô tả về danh mục sản phẩm..."
                              class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none resize-y transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('MoTa') border-red-500 @enderror">{{ old('MoTa', $category->MoTa) }}</textarea>
                    @error('MoTa')
                        <p class="text-red-600 text-[11px] mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- LƯU Ý --}}
            <div class="mt-6 bg-red-50 p-4 rounded-xl flex items-start text-red-800 border border-red-100">
                <i class="fas fa-info-circle mr-3 mt-0.5"></i>
                <p class="text-[11px] leading-relaxed">
                    Lưu ý: Thay đổi tên hoặc mô tả danh mục có thể ảnh hưởng đến cách thông tin danh mục hiển thị trên cửa hàng.
                    Việc cập nhật không thay đổi mã danh mục hoặc xóa các sản phẩm liên quan.
                </p>
            </div>

            {{-- NÚT CHỨC NĂNG --}}
            <div class="mt-7 pt-5 border-t border-red-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                    <i class="fas fa-times mr-2"></i>Hủy bỏ
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-xl bg-red-800 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-900 shadow-sm transition">
                    <i class="fas fa-save mr-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('update-category-form').addEventListener('submit', async function (event) {
        if (!this.checkValidity()) return;
        event.preventDefault();

        const result = await Swal.fire({
            title: 'Xác nhận cập nhật',
            text: 'Bạn có chắc chắn muốn lưu những thay đổi cho danh mục này không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Lưu thay đổi',
            cancelButtonText: 'Hủy bỏ',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-save-confirm',
                cancelButton: 'btn-save-cancel'
            }
        });
        if (result.isConfirmed) this.submit();
    });
</script>
<style>
    .glass-popup {
        background: rgba(255,255,255,.96) !important;
        backdrop-filter: blur(10px);
        border-radius: 24px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,.1) !important;
    }
    .btn-save-confirm {
        background-color: #991b1b !important;
        color: #fff !important;
        padding: 10px 25px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-left: 10px;
    }
    .btn-save-cancel {
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        padding: 10px 25px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
</style>
@endsection

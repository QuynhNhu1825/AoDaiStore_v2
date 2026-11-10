@extends('layouts.admin')

@section('title', 'Chỉnh sửa kích cỡ: ' . $size->TenSize)

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">
    {{-- TIÊU ĐỀ + QUAY LẠI --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa kích cỡ
            </h2>
            <p class="text-xs text-stone-500 mt-1">Cập nhật thông tin kích cỡ trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.sizes.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-red-200 text-red-800 text-xs font-bold hover:bg-red-50 transition">
            <i class="fas fa-arrow-left"></i>Quay lại danh sách
        </a>
    </div>

    {{-- THÔNG BÁO LỖI --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs">
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
        <div class="px-6 py-4 bg-red-50 border-b border-red-100">
            <h3 class="text-sm font-bold text-red-900 uppercase tracking-wider">
                <i class="fas fa-info-circle mr-2"></i>Thông tin kích cỡ
            </h3>
            <p class="text-[11px] text-stone-500 mt-1">Mã kích cỡ: <span class="font-bold text-red-800">#{{ $size->MaSize }}</span></p>
        </div>

        <form id="update-form" action="{{ route('admin.sizes.update', $size->MaSize) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="TenSize" class="block text-xs font-bold text-stone-700 mb-2">
                        Tên kích cỡ <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="TenSize" name="TenSize"
                           value="{{ old('TenSize', $size->TenSize) }}"
                           maxlength="20" required autofocus
                           placeholder="Ví dụ: S, M, L, XL, XXL..."
                           class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('TenSize') border-red-500 @enderror">
                    @error('TenSize')
                        <p class="text-red-600 text-[11px] mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-stone-400 mt-2">Tên kích cỡ không được vượt quá 20 ký tự.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-2">Mã định danh hệ thống</label>
                    <input type="text" value="#{{ $size->MaSize }}" disabled
                           class="w-full px-4 py-3 rounded-xl bg-stone-100 border border-stone-200 text-sm text-stone-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-2">Ngày tạo</label>
                    <input type="text" value="{{ $size->NgayTao ? \Carbon\Carbon::parse($size->NgayTao)->format('d/m/Y H:i') : '---' }}" disabled
                           class="w-full px-4 py-3 rounded-xl bg-stone-100 border border-stone-200 text-sm text-stone-500 cursor-not-allowed">
                </div>
            </div>

            <div class="mt-6 bg-red-50 p-4 rounded-xl flex items-start text-red-800 border border-red-100">
                <i class="fas fa-info-circle mr-3 mt-0.5"></i>
                <p class="text-[11px] leading-relaxed">Lưu ý: Thay đổi tên kích cỡ có thể ảnh hưởng đến cách khách hàng lựa chọn size của sản phẩm. Việc cập nhật không thay đổi mã kích cỡ.</p>
            </div>

            <div class="mt-7 pt-5 border-t border-red-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.sizes.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                    <i class="fas fa-times mr-2"></i>Hủy bỏ
                </a>
                <button type="submit" id="save-button"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-xl bg-red-800 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-900 shadow-sm transition">
                    <i class="fas fa-save mr-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('update-form');
    let confirmed = false;

    form.addEventListener('submit', function (event) {
        if (confirmed || !form.checkValidity()) return;
        event.preventDefault();

        if (typeof Swal === 'undefined') {
            if (window.confirm('Bạn có chắc chắn muốn lưu thay đổi kích cỡ không?')) {
                confirmed = true;
                form.requestSubmit();
            }
            return;
        }

        Swal.fire({
            title: 'Xác nhận cập nhật',
            text: 'Bạn có chắc chắn muốn lưu thay đổi cho kích cỡ này không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Lưu thay đổi',
            cancelButtonText: 'Hủy bỏ',
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            reverseButtons: true,
            customClass: { popup: 'size-edit-popup' }
        }).then(function (result) {
            if (result.isConfirmed) {
                confirmed = true;
                form.requestSubmit();
            }
        });
    });
});
</script>
<style>
.size-edit-popup {
    background: rgba(255,255,255,.96) !important;
    border-radius: 24px !important;
    box-shadow: 0 20px 40px rgba(0,0,0,.1) !important;
}
</style>
@endsection

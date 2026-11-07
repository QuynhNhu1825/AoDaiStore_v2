
@extends('layouts.admin')

@section('title', 'Chỉnh sửa chất liệu: ' . $material->TenChatLieu)

@section('content')

<div class="w-full max-w-4xl mx-auto space-y-4">

    {{-- TIÊU ĐỀ + QUAY LẠI --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        <div>
            <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-edit mr-2"></i>
                Chỉnh sửa chất liệu
            </h2>

            <p class="text-xs text-stone-500 mt-1">
                Cập nhật thông tin chất liệu trong hệ thống.
            </p>
        </div>

        <a href="{{ route('admin.materials.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-red-200 text-red-800 text-xs font-bold hover:bg-red-50 transition">

            <i class="fas fa-arrow-left"></i>
            Quay lại danh sách

        </a>

    </div>

    {{-- THÔNG BÁO LỖI --}}
    @if($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs">

            <div class="font-bold mb-2">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Vui lòng kiểm tra lại thông tin:
            </div>

            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    {{-- FORM CHỈNH SỬA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-4 bg-red-50 border-b border-red-100">

            <h3 class="text-sm font-bold text-red-900 uppercase tracking-wider">
                <i class="fas fa-info-circle mr-2"></i>
                Thông tin chất liệu
            </h3>

            <p class="text-[11px] text-stone-500 mt-1">
                Mã chất liệu:
                <span class="font-bold text-red-800">
                    #{{ $material->MaChatLieu }}
                </span>
            </p>

        </div>

        <form
            id="update-form"
            action="{{ route('admin.materials.update', $material->MaChatLieu) }}"
            method="POST"
            class="p-6"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- TÊN CHẤT LIỆU --}}
                <div class="md:col-span-2">

                    <label for="TenChatLieu"
                           class="block text-xs font-bold text-stone-700 mb-2">

                        Tên chất liệu
                        <span class="text-red-600">*</span>

                    </label>

                    <input
                        type="text"
                        id="TenChatLieu"
                        name="TenChatLieu"
                        value="{{ old('TenChatLieu', $material->TenChatLieu) }}"
                        required
                        maxlength="100"
                        placeholder="Ví dụ: Lụa tơ tằm, Gấm cao cấp..."
                        class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('TenChatLieu') border-red-500 @enderror"
                    >

                    @error('TenChatLieu')
                        <p class="text-red-600 text-[11px] mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- XUẤT XỨ --}}
                <div class="md:col-span-2">

                    <label for="XuatXu"
                           class="block text-xs font-bold text-stone-700 mb-2">

                        Xuất xứ

                    </label>

                    <input
                        type="text"
                        id="XuatXu"
                        name="XuatXu"
                        value="{{ old('XuatXu', $material->XuatXu) }}"
                        maxlength="100"
                        placeholder="Ví dụ: Việt Nam, Nhật Bản, Hàn Quốc..."
                        class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('XuatXu') border-red-500 @enderror"
                    >

                    @error('XuatXu')
                        <p class="text-red-600 text-[11px] mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- HƯỚNG DẪN BẢO QUẢN --}}
                <div class="md:col-span-2">

                    <label for="HuongDanBaoQuan"
                           class="block text-xs font-bold text-stone-700 mb-2">

                        Hướng dẫn bảo quản

                    </label>

                    <textarea
                        id="HuongDanBaoQuan"
                        name="HuongDanBaoQuan"
                        rows="5"
                        maxlength="500"
                        placeholder="Nhập hướng dẫn bảo quản chất liệu..."
                        class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none resize-y transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('HuongDanBaoQuan') border-red-500 @enderror"
                    >{{ old('HuongDanBaoQuan', $material->HuongDanBaoQuan) }}</textarea>

                    @error('HuongDanBaoQuan')
                        <p class="text-red-600 text-[11px] mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- MÔ TẢ --}}
                <div class="md:col-span-2">

                    <label for="MoTa"
                           class="block text-xs font-bold text-stone-700 mb-2">

                        Mô tả chất liệu

                    </label>

                    <textarea
                        id="MoTa"
                        name="MoTa"
                        rows="4"
                        maxlength="500"
                        placeholder="Nhập mô tả về đặc điểm và tính chất của chất liệu..."
                        class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 outline-none resize-y transition focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-100 @error('MoTa') border-red-500 @enderror"
                    >{{ old('MoTa', $material->MoTa) }}</textarea>

                    @error('MoTa')
                        <p class="text-red-600 text-[11px] mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

            {{-- LƯU Ý --}}
            <div class="mt-6 bg-red-50 p-4 rounded-xl flex items-start text-red-800 border border-red-100">

                <i class="fas fa-info-circle mr-3 mt-0.5"></i>

                <p class="text-[11px] leading-relaxed">
                    Lưu ý: Thay đổi thông tin chất liệu có thể ảnh hưởng đến các sản phẩm đang sử dụng chất liệu này.
                    Việc cập nhật không làm thay đổi mã chất liệu hoặc xóa các sản phẩm liên quan.
                </p>

            </div>

            {{-- NÚT CHỨC NĂNG --}}
            <div class="mt-7 pt-5 border-t border-red-100 flex flex-col-reverse sm:flex-row justify-end gap-3">

                <a href="{{ route('admin.materials.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">

                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ

                </a>

                <button
                    type="button"
                    onclick="confirmSave()"
                    class="inline-flex items-center justify-center px-7 py-3 rounded-xl bg-red-800 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-900 shadow-sm transition"
                >

                    <i class="fas fa-save mr-2"></i>
                    Lưu thay đổi

                </button>

            </div>

        </form>

    </div>

</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    function confirmSave() {

        Swal.fire({

            title: 'Xác nhận cập nhật',

            html: `
                <div class="text-sm text-stone-500 mt-2">
                    Bạn có chắc chắn muốn lưu những thay đổi
                    cho chất liệu
                    <b class="text-red-900">
                        "{{ addslashes($material->TenChatLieu) }}"
                    </b>
                    không?
                </div>
            `,

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Lưu thay đổi',

            cancelButtonText: 'Hủy bỏ',

            buttonsStyling: false,

            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn-save-confirm',
                cancelButton: 'btn-save-cancel'
            },

            reverseButtons: true

        }).then((result) => {

            if (result.isConfirmed) {

                document.getElementById('update-form').submit();

            }

        });

    }

</script>

{{-- STYLE --}}
<style>

    .glass-popup {
        background: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(10px);
        border-radius: 24px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }

    .btn-save-confirm {
        background-color: #991b1b !important;
        color: white !important;
        padding: 10px 25px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
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

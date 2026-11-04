@extends('layouts.admin')

@section('title', 'Chỉnh sửa chất liệu: ' . $material->TenChatLieu)

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Nút quay lại --}}
    <div class="mb-4">
        <a href="{{ route('admin.materials.index') }}"
           class="text-emerald-600 hover:underline flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách chất liệu
        </a>
    </div>

    {{-- Thẻ nội dung --}}
    <div class="bg-white p-8 rounded-xl shadow-lg border-t-4 border-emerald-600">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">
                Cập nhật thông tin chất liệu
            </h2>
            <p class="text-xs text-gray-500 mt-1 italic">
                Mã chất liệu: #{{ $material->MaChatLieu }}
            </p>
        </div>

        <form action="{{ route('admin.materials.update', $material->MaChatLieu) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                {{-- Tên chất liệu --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Tên chất liệu
                    </label>
                    <input type="text" name="TenChatLieu"
                           value="{{ old('TenChatLieu', $material->TenChatLieu) }}" required
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 outline-none transition
                           @error('TenChatLieu') border-red-500 @enderror"
                           placeholder="Ví dụ: Lụa tơ tằm, Gấm cao cấp...">

                    @error('TenChatLieu')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Xuất xứ --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Xuất xứ
                    </label>
                    <input type="text" name="Xuatxu"
                           value="{{ old('Xuatxu', $material->Xuatxu) }}"
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 outline-none transition"
                           placeholder="Ví dụ: Việt Nam, Nhật Bản, Hàn Quốc...">
                </div>

                {{-- Hướng dẫn bảo quản --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Hướng dẫn bảo quản
                    </label>
                    <textarea name="HuongDanBaoQuan" rows="5"
                              class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm"
                              placeholder="Nhập hướng dẫn bảo quản chất liệu...">{{ old('HuongDanBaoQuan', $material->HuongDanBaoQuan) }}</textarea>
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 uppercase text-xs tracking-widest">
                        Trạng thái
                    </label>
                    <select name="TrangThai"
                            class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        <option value="1" {{ old('TrangThai', $material->TrangThai) == 1 ? 'selected' : '' }}>
                            Hoạt động
                        </option>
                        <option value="0" {{ old('TrangThai', $material->TrangThai) == 0 ? 'selected' : '' }}>
                            Ẩn
                        </option>
                    </select>
                </div>
            </div>

            {{-- Nút --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-4 border-t pt-6">
                <button type="button"
                        onclick="confirmSave()"
                        class="flex-1 bg-emerald-600 text-white py-3.5 rounded-lg font-bold
                            hover:bg-emerald-700 shadow-lg hover:shadow-emerald-200 transition-all
                            flex justify-center items-center uppercase tracking-widest">
                    <i class="fas fa-sync-alt mr-2"></i> Lưu thay đổi
                </button>




                <a href="{{ route('admin.materials.index') }}"
                   class="px-10 py-3.5 border border-gray-300 text-gray-500 rounded-lg
                          hover:bg-gray-50 transition font-bold text-center">
                    Hủy
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-emerald-50 p-4 rounded-lg flex items-center text-emerald-700 border border-emerald-100">
        <i class="fas fa-info-circle mr-3"></i>
        <p class="text-xs italic">
            Lưu ý: Thay đổi thông tin chất liệu có thể ảnh hưởng đến các sản phẩm đang sử dụng chất liệu này.
        </p>
    </div>
</div>
{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmSave(id, name) {
    Swal.fire({
        title: 'Xác nhận lưu',
        text: `Bạn có chắc muốn lưu thay đổi cho chất liệu này không?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Lưu',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`update-form-${id}`).submit();
        }
    });
}
</script>

@endsection


@extends('layouts.admin')

@section('title', 'Danh sách Chất liệu')

@section('content')
<div class="space-y-6">

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm flex items-center border-l-4 border-emerald-500">
            <div class="p-3 bg-emerald-100 rounded-full text-emerald-600 mr-4">
                <i class="fas fa-leaf fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase">Tổng số chất liệu</p>
                <p class="text-xl font-bold text-gray-800">{{ $materials->count() }}</p>
            </div>
        </div>
    </div>

    {{-- TÌM KIẾM + THÊM --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between gap-4">

            <form action="{{ route('admin.materials.index') }}" method="GET" class="flex flex-1 gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                        placeholder="Tìm tên chất liệu...">
                </div>

                <button class="bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700">
                    Lọc
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.materials.index') }}"
                        class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg flex items-center">
                        Xóa lọc
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.materials.create') }}"
                class="inline-flex items-center bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold shadow">
                <i class="fas fa-plus-circle mr-2"></i> Thêm Chất Liệu
            </a>
        </div>
    </div>

    {{-- BẢNG --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold">
                    <th class="px-6 py-4">Chất liệu</th>
                    <th class="px-6 py-4">Xuất xứ</th>
                    <th class="px-6 py-4">Bảo quản</th>
                    <th class="px-6 py-4">Trạng thái</th>
                    <th class="px-6 py-4 text-center">Chức năng</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($materials as $item)
                    <tr class="hover:bg-emerald-50/40 transition">

                        {{-- TÊN --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $item->TenChatLieu }}</p>
                                    <p class="text-xs text-gray-400 font-mono">ID: {{ $item->MaChatLieu }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- XUẤT XỨ --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item->Xuatxu ?? '---' }}
                        </td>

                        {{-- BẢO QUẢN --}}
                        <td class="px-6 py-4 text-sm text-gray-500 italic">
                            {{ \Illuminate\Support\Str::limit($item->HuongDanBaoQuan, 50) ?? '---' }}
                        </td>

                        {{-- TRẠNG THÁI --}}
                        <td class="px-6 py-4">
                            @if($item->TrangThai == 1)
                               <span class="inline-flex items-center justify-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold leading-none">
                                    Hoạt động
                                </span>

                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                    Ẩn
                                </span>
                            @endif
                        </td>

                        {{-- CHỨC NĂNG --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">

                                <a href="{{ route('admin.materials.edit', $item->MaChatLieu) }}"
                                   class="flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <i class="fas fa-edit mr-1"></i> Sửa
                                </a>

                                <button type="button"onclick='confirmDelete({{ $item->MaChatLieu }}, @json($item->TenChatLieu))'
                                    class="flex items-center px-3 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                                    <i class="fas fa-trash mr-1"></i> Xóa
                                </button>


                                <form id="delete-form-{{ $item->MaChatLieu }}"
                                      action="{{ route('admin.materials.destroy', $item->MaChatLieu) }}"
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>Không có chất liệu nào</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa chất liệu "${name}" không?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

</script>
@endsection

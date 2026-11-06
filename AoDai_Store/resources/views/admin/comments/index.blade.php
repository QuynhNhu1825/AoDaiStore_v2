
@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="space-y-6">

    {{-- TIÊU ĐỀ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-[#713448]">
                Quản lý đánh giá
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Quản lý đánh giá và phản hồi của khách hàng về sản phẩm.
            </p>
        </div>

        <div class="bg-[#F7E9EE] text-[#A94463] px-4 py-2 rounded-lg text-sm font-semibold">
            Tổng đánh giá: {{ $comments->total() }}
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-[#F7E9EE] border border-[#EADDE2] text-[#8F3552] px-4 py-3 rounded-lg">
            <i class="mr-2">✓</i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- BẢNG ĐÁNH GIÁ --}}
    <div class="bg-white rounded-xl shadow-sm border border-[#EADDE2] overflow-hidden">

        <div class="px-5 py-4 border-b border-[#EADDE2] bg-[#FCF9FA]">
            <h3 class="font-bold text-[#713448]">
                Danh sách đánh giá sản phẩm
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-[#F7E9EE] text-[#713448]">
                    <tr>
                        <th class="px-4 py-4 font-semibold whitespace-nowrap">Người dùng</th>
                        <th class="px-4 py-4 font-semibold whitespace-nowrap">Sản phẩm</th>
                        <th class="px-4 py-4 font-semibold text-center whitespace-nowrap">Số sao</th>
                        <th class="px-4 py-4 font-semibold min-w-[200px]">Nội dung đánh giá</th>
                        <th class="px-4 py-4 font-semibold whitespace-nowrap">Ngày đánh giá</th>
                        <th class="px-4 py-4 font-semibold min-w-[280px]">Phản hồi đánh giá</th>
                        <th class="px-4 py-4 font-semibold text-center whitespace-nowrap">Trạng thái</th>
                        <th class="px-4 py-4 font-semibold text-center whitespace-nowrap">Hành động</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#F0E5E9]">

                    @forelse($comments as $cmt)
                    <tr class="hover:bg-[#FCF8FA] transition">

                        {{-- NGƯỜI DÙNG --}}
                        <td class="px-4 py-4">
                            <span class="font-semibold text-gray-700">
                                {{ $cmt->TenDangNhap }}
                            </span>
                        </td>

                        {{-- SẢN PHẨM --}}
                        <td class="px-4 py-4">
                            <span class="text-gray-700">
                                {{ $cmt->TenSanPham }}
                            </span>
                        </td>

                        {{-- SỐ SAO --}}
                        <td class="px-4 py-4 text-center">
                            <span class="text-amber-500 font-bold whitespace-nowrap">
                                ⭐ {{ $cmt->SoSao }}/5
                            </span>
                        </td>

                        {{-- NỘI DUNG --}}
                        <td class="px-4 py-4">
                            <div class="max-w-[250px] text-gray-600 break-words">
                                {{ $cmt->NoiDung }}
                            </div>
                        </td>

                        {{-- NGÀY --}}
                        <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                            {{ \Carbon\Carbon::parse($cmt->NgayDanhGia)->format('d/m/Y') }}
                        </td>

                        {{-- PHẢN HỒI --}}
                        <td class="px-4 py-4 min-w-[280px]">

                            @if(!empty($cmt->PhanHoi))

                                <div class="bg-[#F7E9EE] border border-[#EADDE2] rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[#A94463] font-semibold">
                                            Phản hồi của Admin
                                        </span>
                                    </div>

                                    <p class="text-gray-700 break-words">
                                        {{ $cmt->PhanHoi }}
                                    </p>
                                </div>

                                <button type="button"
                                    onclick="toggleReply({{ $cmt->MaDanhGia }})"
                                    class="text-[#A94463] hover:text-[#8F3552] font-semibold mt-2 text-xs">
                                    Chỉnh sửa phản hồi
                                </button>

                            @else

                                <p class="text-gray-400 italic mb-2">
                                    Chưa có phản hồi
                                </p>

                            @endif

                            {{-- FORM PHẢN HỒI --}}
                            <form id="reply-{{ $cmt->MaDanhGia }}"
                                action="{{ route('admin.comments.reply', $cmt->MaDanhGia) }}"
                                method="POST"
                                class="{{ !empty($cmt->PhanHoi) ? 'hidden' : '' }} mt-2">

                                @csrf
                                @method('PATCH')

                                <textarea
                                    name="PhanHoi"
                                    rows="3"
                                    required
                                    maxlength="1000"
                                    placeholder="Nhập phản hồi cho khách hàng..."
                                    class="w-full border border-[#EADDE2] rounded-lg p-3 text-sm outline-none focus:border-[#A94463] focus:ring-2 focus:ring-[#A94463]/10 resize-y"
                                >{{ $cmt->PhanHoi }}</textarea>

                                <button type="submit"
                                    class="mt-2 bg-[#A94463] hover:bg-[#8F3552] text-white px-4 py-2 rounded-lg text-xs font-semibold transition">
                                    Lưu phản hồi
                                </button>

                            </form>

                        </td>

                        {{-- TRẠNG THÁI --}}
                        <td class="px-4 py-4 text-center">

                            @if($cmt->TrangThai)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                    Đã ẩn
                                </span>
                            @endif

                        </td>

                        {{-- HÀNH ĐỘNG --}}
                        <td class="px-4 py-4 text-center">

                            @if($cmt->TrangThai)

                                <form method="POST"
                                    action="{{ route('admin.comments.hide', $cmt->MaDanhGia) }}"
                                    onsubmit="return confirm('Bạn có chắc muốn ẩn đánh giá này?')">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="bg-[#FCE8EC] hover:bg-[#F5D3DC] text-[#A94463] px-4 py-2 rounded-lg font-semibold text-xs transition whitespace-nowrap">
                                        Ẩn đánh giá
                                    </button>

                                </form>

                            @else

                                <form method="POST"
                                    action="{{ route('admin.comments.show', $cmt->MaDanhGia) }}">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold text-xs transition whitespace-nowrap">
                                        Hiện lại
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-400">
                            <div class="text-4xl mb-3">💬</div>
                            <p class="font-medium">Chưa có đánh giá nào.</p>
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        <div class="px-5 py-4 border-t border-[#EADDE2] bg-[#FCF9FA]">
            {{ $comments->links() }}
        </div>

    </div>

</div>

{{-- JAVASCRIPT --}}
<script>
    function toggleReply(id) {
        const form = document.getElementById('reply-' + id);

        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>
@endsection

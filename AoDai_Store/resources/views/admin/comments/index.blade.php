@extends('layouts.admin')

@section('title', 'Quản lý bình luận')

@section('content')
<div class="space-y-6">

    <h2 class="text-xl font-bold">Quản lý bình luận</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Người dùng</th>
                    <th class="p-3 border">Sản phẩm</th>
                    <th class="p-3 border">Số sao</th>
                    <th class="p-3 border">Nội dung</th>
                    <th class="p-3 border">Ngày</th>
                    <th class="p-3 border">Trạng thái</th>
                    <th class="p-3 border">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $cmt)
                <tr>
                    <td class="p-3 border">{{ $cmt->TenDangNhap }}</td>
                    <td class="p-3 border">{{ $cmt->TenSanPham }}</td>
                    <td class="p-3 border text-center">⭐ {{ $cmt->SoSao }}</td>
                    <td class="p-3 border">{{ $cmt->NoiDung }}</td>
                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($cmt->NgayDanhGia)->format('d/m/Y') }}
                    </td>
                    <td class="p-3 border text-center">
                        {{ $cmt->TrangThai ? 'Hiển thị' : 'Đã ẩn' }}
                    </td>
                    <td class="p-3 border text-center">
                        @if($cmt->TrangThai)
                        <form method="POST"
                              action="{{ route('admin.comments.hide', $cmt->MaDanhGia) }}"
                              onsubmit="return confirm('Ẩn bình luận này?')">
                            @csrf
                            @method('PATCH')
                            <button class="bg-red-600 text-white px-3 py-1 rounded">
                                Ẩn
                            </button>
                        </form>
                        @else
                             <form method="POST" action="{{ route('admin.comments.show', $cmt->MaDanhGia) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-3 py-1 rounded">
                                Hiện lại
                            </button>
                    </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $comments->links() }}
        </div>
    </div>

</div>
@endsection


@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')

<div class="w-full max-w-full space-y-4" id="full-container">

    {{-- TIÊU ĐỀ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-red-900">
                Quản lý đánh giá
            </h2>
            <p class="text-[11px] text-stone-500 mt-1">
                Quản lý đánh giá và phản hồi của khách hàng về sản phẩm.
            </p>
        </div>

        <div class="bg-red-50 border border-red-100 text-red-900 px-4 py-2 rounded-xl">
            <p class="text-[10px] uppercase tracking-widest font-bold">
                Tổng đánh giá
            </p>
            <p class="text-xl font-bold">
                {{ $comments->total() }}
            </p>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl text-xs">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl text-xs">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif
    {{-- TÌM KIẾM VÀ LỌC ĐÁNH GIÁ --}}
<div class="bg-white p-4 rounded-xl shadow-sm border border-red-100">

    <form action="{{ route('admin.comments.index') }}"
          method="GET"
          class="flex flex-col lg:flex-row items-center gap-3">

        {{-- Tìm kiếm --}}
        <div class="relative flex-1 w-full">

            <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                <i class="fas fa-search text-xs"></i>
            </span>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Tìm tên người dùng, sản phẩm, nội dung..."
                   class="w-full pl-9 pr-3 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs outline-none focus:border-red-700 transition">

        </div>

        {{-- Lọc số sao --}}
        <div class="w-full lg:w-40">

            <select name="stars"
                    class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-xs outline-none focus:border-red-700 cursor-pointer">

                <option value="">-- Tất cả số sao --</option>

                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}"
                        {{ request('stars') == (string)$i ? 'selected' : '' }}>
                        {{ $i }} sao
                    </option>
                @endfor

            </select>

        </div>

        {{-- Lọc trạng thái --}}
        <div class="w-full lg:w-44">

            <select name="status"
                    class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-xs outline-none focus:border-red-700 cursor-pointer">

                <option value="">-- Tất cả trạng thái --</option>

                <option value="1"
                    {{ request('status') === '1' ? 'selected' : '' }}>
                    Đang hiển thị
                </option>

                <option value="0"
                    {{ request('status') === '0' ? 'selected' : '' }}>
                    Đã ẩn
                </option>

            </select>

        </div>

        {{-- Nút lọc --}}
        <button type="submit"
                class="w-full lg:w-auto bg-red-800 hover:bg-red-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition whitespace-nowrap">

            <i class="fas fa-filter mr-1"></i>
            Lọc đánh giá

        </button>

        {{-- Đặt lại --}}
        <a href="{{ route('admin.comments.index') }}"
           class="w-full lg:w-auto text-center bg-stone-100 hover:bg-stone-200 text-stone-600 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition whitespace-nowrap">

            <i class="fas fa-redo mr-1"></i>
            Đặt lại

        </a>

    </form>

</div>
    {{-- BẢNG ĐÁNH GIÁ --}}
    <div id="comment-table-container"
         class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">

        {{-- TIÊU ĐỀ BẢNG --}}
        <div class="px-4 py-3 border-b border-red-100 bg-red-50">
            <h3 class="font-bold text-red-900 text-xs uppercase tracking-wide">
                <i class="fas fa-comments mr-2"></i>
                Danh sách đánh giá sản phẩm
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] table-fixed text-left border-collapse text-xs">

                {{-- THEAD --}}
                <thead>
                <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">
                    <th class="w-[10%] px-2 py-3">Người dùng</th>
                    <th class="w-[14%] px-2 py-3">Sản phẩm</th>
                    <th class="w-[6%] px-1 py-3 text-center">Số sao</th>
                    <th class="w-[15%] px-2 py-3">Nội dung đánh giá</th>
                    <th class="w-[10%] px-2 py-3 text-center">Hình ảnh</th>
                    <th class="w-[9%] px-1 py-3 text-center">Ngày đánh giá</th>
                    <th class="w-[19%] px-2 py-3">Phản hồi Admin</th>
                    <th class="w-[9%] px-1 py-3 text-center">Trạng thái</th>
                    <th class="w-[8%] px-1 py-3 text-center">Thao tác</th>
                </tr>
            </thead>

                {{-- TBODY --}}
                <tbody class="divide-y divide-red-50">

                    @forelse($comments as $cmt)

                        <tr class="hover:bg-red-50/40 transition">

                            {{-- NGƯỜI DÙNG --}}
                            <td class="px-2 py-3 align-middle">
                                <p class="font-bold text-red-900 text-[11px] break-words">
                                    {{ $cmt->TenDangNhap }}
                                </p>
                            </td>

                            {{-- SẢN PHẨM --}}
                            <td class="px-2 py-3 align-middle">
                                <p class="text-[11px] text-stone-700 break-words">
                                    {{ $cmt->TenSanPham }}
                                </p>
                            </td>

                            {{-- SỐ SAO --}}
                            <td class="px-1 py-3 text-center align-middle">
                                <span class="text-amber-500 font-bold text-[11px] whitespace-nowrap">
                                    <i class="fas fa-star"></i>
                                    {{ $cmt->SoSao }}/5
                                </span>
                            </td>

                            {{-- NỘI DUNG ĐÁNH GIÁ --}}
                            <td class="px-2 py-3 align-middle">
                                <p class="text-[11px] text-stone-600 break-words line-clamp-4"
                                   title="{{ $cmt->NoiDung }}">
                                    {{ $cmt->NoiDung ?: 'Không có nội dung' }}
                                </p>
                            </td>
                            {{-- HÌNH ẢNH ĐÁNH GIÁ --}}
                            <td class="px-2 py-3 text-center align-middle">
                                @php
                                    $imagePath = $cmt->HinhAnh
                                        ? public_path('img/ratings/' . basename($cmt->HinhAnh))
                                        : null;

                                    $imageUrl = $cmt->HinhAnh
                                        ? asset('img/ratings/' . basename($cmt->HinhAnh))
                                        : null;
                                @endphp

                                @if($imagePath && file_exists($imagePath))
                                    <a href="{{ $imageUrl }}"
                                    target="_blank"
                                    title="Xem ảnh đánh giá">

                                        <img src="{{ $imageUrl }}"
                                            alt="Ảnh đánh giá"
                                            class="w-14 h-14 mx-auto object-cover rounded-lg border border-stone-200 hover:scale-110 transition cursor-pointer">
                                    </a>
                                @else
                                    <span class="text-[10px] text-stone-400 italic">
                                        Không có ảnh
                                    </span>
                                @endif
                            </td>
                            {{-- NGÀY ĐÁNH GIÁ --}}
                            <td class="px-1 py-3 text-center align-middle">
                                <span class="text-[10px] text-stone-500 whitespace-nowrap">
                                    {{ $cmt->NgayDanhGia
                                        ? \Carbon\Carbon::parse($cmt->NgayDanhGia)->format('d/m/Y')
                                        : '---' }}
                                </span>
                            </td>

                            {{-- PHẢN HỒI ADMIN --}}
                            <td class="px-2 py-3 align-middle">

                                @if(!empty($cmt->PhanHoi))

                                    {{-- NỘI DUNG PHẢN HỒI ĐÃ LƯU --}}
                                    <div class="bg-red-50 border border-red-100 rounded-lg p-2">

                                        <p class="text-[10px] font-bold text-red-900 mb-1 uppercase tracking-wide">
                                            <i class="fas fa-reply mr-1"></i>
                                            Admin phản hồi
                                        </p>

                                        <p class="text-[11px] text-stone-600 break-words line-clamp-3"
                                           title="{{ $cmt->PhanHoi }}">
                                            {{ $cmt->PhanHoi }}
                                        </p>

                                    </div>

                                    <button type="button"
                                        onclick="toggleReply({{ $cmt->MaDanhGia }})"
                                        class="text-red-800 hover:text-red-950 font-bold text-[10px] mt-1.5 transition">
                                        <i class="fas fa-edit mr-1"></i>
                                        Chỉnh sửa
                                    </button>

                                @else

                                    <p class="text-[10px] text-stone-400 italic mb-1.5">
                                        Chưa có phản hồi
                                    </p>

                                @endif

                                {{-- FORM THÊM / SỬA PHẢN HỒI --}}
                                <form id="reply-{{ $cmt->MaDanhGia }}"
                                    action="{{ route('admin.comments.reply', $cmt->MaDanhGia) }}"
                                    method="POST"
                                    class="{{ !empty($cmt->PhanHoi) ? 'hidden' : '' }} mt-1.5">

                                    @csrf
                                    @method('PATCH')

                                    <textarea
                                        name="PhanHoi"
                                        rows="2"
                                        required
                                        maxlength="1000"
                                        placeholder="Nhập phản hồi..."
                                        class="w-full border border-stone-200 bg-stone-50 rounded-lg px-2.5 py-2 text-[11px] text-stone-700 outline-none focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700/10 resize-none transition"
                                    >{{ $cmt->PhanHoi }}</textarea>

                                    @error('PhanHoi')
                                        <p class="text-red-600 text-[10px] mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <button type="submit"
                                        class="mt-1.5 bg-red-800 hover:bg-red-900 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wide transition whitespace-nowrap">
                                        <i class="fas fa-save mr-1"></i>
                                        Lưu
                                    </button>

                                </form>

                            </td>

                            {{-- TRẠNG THÁI --}}
                            <td class="px-1 py-3 text-center align-middle">

                                @if($cmt->TrangThai == 1)

                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-1.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-100 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-green-600"></span>
                                        Hiển thị
                                    </span>

                                @else

                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-1.5 rounded-full text-[9px] font-bold bg-stone-100 text-stone-500 border border-stone-200 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-stone-400"></span>
                                        Đã ẩn
                                    </span>

                                @endif

                            </td>

                            {{-- THAO TÁC --}}
                            <td class="px-1 py-3 text-center align-middle">

                                @if($cmt->TrangThai == 1)

                                    <form method="POST"
                                        action="{{ route('admin.comments.hide', $cmt->MaDanhGia) }}"
                                        onsubmit="return confirmHideRating(event)">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            title="Ẩn đánh giá"
                                            class="inline-flex items-center justify-center gap-1 bg-red-50 hover:bg-red-800 text-red-800 hover:text-white border border-red-200 px-2 py-1.5 rounded-lg text-[10px] font-bold transition whitespace-nowrap">
                                            <i class="fas fa-eye-slash"></i>
                                            Ẩn
                                        </button>

                                    </form>

                                @else

                                    <form method="POST"
                                        action="{{ route('admin.comments.show', $cmt->MaDanhGia) }}"
                                        onsubmit="return confirmShowRating(event)">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            title="Hiển thị lại đánh giá"
                                            class="inline-flex items-center justify-center gap-1 bg-green-50 hover:bg-green-700 text-green-700 hover:text-white border border-green-200 px-2 py-1.5 rounded-lg text-[10px] font-bold transition whitespace-nowrap">
                                            <i class="fas fa-eye"></i>
                                            Hiện
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-stone-400">
                                <div class="text-4xl mb-3">
                                    <i class="far fa-comment-dots"></i>
                                </div>
                                <p class="font-semibold text-xs">
                                    Chưa có đánh giá nào.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        <div class="px-4 py-3 border-t border-red-100 bg-red-50">
            {{ $comments->links('pagination::tailwind') }}
        </div>

    </div>

</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    // Mở / đóng form phản hồi
    function toggleReply(id) {
        const form = document.getElementById('reply-' + id);

        if (form) {
            form.classList.toggle('hidden');

            if (!form.classList.contains('hidden')) {
                const textarea = form.querySelector('textarea');

                if (textarea) {
                    textarea.focus();
                }
            }
        }
    }

    // Xác nhận ẩn đánh giá
    function confirmHideRating(event) {
        event.preventDefault();

        const form = event.currentTarget;

        Swal.fire({
            title: 'Ẩn đánh giá?',
            text: 'Đánh giá này sẽ không còn hiển thị cho khách hàng.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ẩn đánh giá',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#9ca3af',
            reverseButtons: true,
            customClass: {
                popup: 'glass-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }

    // Xác nhận hiển thị lại đánh giá
    function confirmShowRating(event) {
        event.preventDefault();

        const form = event.currentTarget;

        Swal.fire({
            title: 'Hiển thị lại đánh giá?',
            text: 'Đánh giá này sẽ được hiển thị lại cho khách hàng.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Hiển thị',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#15803d',
            cancelButtonColor: '#9ca3af',
            reverseButtons: true,
            customClass: {
                popup: 'glass-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }

    // Thông báo thành công
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false,
            customClass: {
                popup: 'glass-popup'
            }
        });
    @endif

    // Thông báo lỗi
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Thông báo lỗi',
            text: @json(session('error')),
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#991b1b',
            customClass: {
                popup: 'glass-popup'
            }
        });
    @endif

    // Thông báo lỗi validation
    @if($errors->has('PhanHoi'))
        Swal.fire({
            icon: 'error',
            title: 'Không thể lưu phản hồi',
            text: @json($errors->first('PhanHoi')),
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#991b1b',
            customClass: {
                popup: 'glass-popup'
            }
        });
    @endif

</script>

<style>

    .serif {
        font-family: 'Playfair Display', serif;
    }

    .glass-popup {
        background: rgba(255, 255, 255, 0.97) !important;
        backdrop-filter: blur(10px);
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    }

</style>

@endsection

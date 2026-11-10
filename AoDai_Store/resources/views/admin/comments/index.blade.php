@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')

@php
    // Thống kê toàn bộ đánh giá, không phụ thuộc phân trang
    $reviewStats = \Illuminate\Support\Facades\DB::table('danhgia')
        ->selectRaw('COUNT(*) AS total')
        ->selectRaw("SUM(CASE WHEN PhanHoi IS NULL OR TRIM(PhanHoi) = '' THEN 1 ELSE 0 END) AS unanswered")
        ->selectRaw("SUM(CASE WHEN PhanHoi IS NOT NULL AND TRIM(PhanHoi) <> '' THEN 1 ELSE 0 END) AS answered")
        ->first();

    $reviewTotal = (int) ($reviewStats->total ?? 0);
    $reviewUnanswered = (int) ($reviewStats->unanswered ?? 0);
    $reviewAnswered = (int) ($reviewStats->answered ?? 0);
@endphp

<div class="w-full max-w-full min-w-0 space-y-4 admin-comments-page"
     id="full-container">

    {{-- ================= THỐNG KÊ ================= --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">

        {{-- TỔNG ĐÁNH GIÁ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-red-100
                    p-3 sm:p-4 flex items-center gap-2 sm:gap-4 min-w-0">

            <div class="shrink-0 w-9 h-9 sm:w-12 sm:h-12
                        flex items-center justify-center
                        bg-red-50 text-red-800 rounded-xl">
                <i class="far fa-comment-dots text-sm sm:text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-[9px] sm:text-[10px] text-stone-500
                          uppercase tracking-wide font-bold leading-tight">
                    Tổng đánh giá
                </p>

                <p class="text-lg sm:text-xl font-bold text-red-900 mt-1">
                    {{ number_format($reviewTotal, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- CHƯA PHẢN HỒI --}}
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100
                    p-3 sm:p-4 flex items-center gap-2 sm:gap-4 min-w-0">

            <div class="shrink-0 w-9 h-9 sm:w-12 sm:h-12
                        flex items-center justify-center
                        bg-amber-50 text-amber-700 rounded-xl">
                <i class="far fa-comment text-sm sm:text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-[9px] sm:text-[10px] text-stone-500
                          uppercase tracking-wide font-bold leading-tight">
                    Chưa phản hồi
                </p>

                <p class="text-lg sm:text-xl font-bold text-red-900 mt-1">
                    {{ number_format($reviewUnanswered, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- ĐÃ PHẢN HỒI --}}
        <div class="bg-white rounded-2xl shadow-sm border border-green-100
                    p-3 sm:p-4 flex items-center gap-2 sm:gap-4 min-w-0">

            <div class="shrink-0 w-9 h-9 sm:w-12 sm:h-12
                        flex items-center justify-center
                        bg-green-50 text-green-700 rounded-xl">
                <i class="fas fa-check-circle text-sm sm:text-lg"></i>
            </div>

            <div class="min-w-0">
                <p class="text-[9px] sm:text-[10px] text-stone-500
                          uppercase tracking-wide font-bold leading-tight">
                    Đã phản hồi
                </p>

                <p class="text-lg sm:text-xl font-bold text-red-900 mt-1">
                    {{ number_format($reviewAnswered, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ================= TÌM KIẾM VÀ LỌC ================= --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100">

        <form action="{{ route('admin.comments.index') }}"
              method="GET"
              class="grid grid-cols-1 sm:grid-cols-2
                     xl:grid-cols-[minmax(220px,1fr)_160px_180px_auto_auto]
                     items-stretch gap-3">

            {{-- Tìm kiếm --}}
            <div class="relative w-full min-w-0 sm:col-span-2 xl:col-span-1">

                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <i class="fas fa-search text-xs"></i>
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tên người dùng, sản phẩm, nội dung..."
                    class="w-full pl-9 pr-3 py-2.5 bg-stone-50
                           border border-stone-200 rounded-xl text-xs
                           focus:border-red-700 transition"
                >
            </div>

            {{-- Lọc số sao --}}
            <select
                name="stars"
                class="w-full min-w-0 bg-stone-50 border border-stone-200
                       rounded-xl py-2.5 px-3 text-xs
                       focus:border-red-700 cursor-pointer">

                <option value="">-- Tất cả số sao --</option>

                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}"
                        @selected(request('stars') == (string) $i)>
                        {{ $i }} sao
                    </option>
                @endfor
            </select>

            {{-- Lọc trạng thái --}}
            <select
                name="status"
                class="w-full min-w-0 bg-stone-50 border border-stone-200
                       rounded-xl py-2.5 px-3 text-xs
                       focus:border-red-700 cursor-pointer">

                <option value="">-- Tất cả trạng thái --</option>

                <option value="1" @selected(request('status') === '1')>
                    Đang hiển thị
                </option>

                <option value="0" @selected(request('status') === '0')>
                    Đã ẩn
                </option>
            </select>

            {{-- Lọc --}}
            <button type="submit"
                    class="w-full bg-red-800 hover:bg-red-900
                           text-white px-5 py-2.5 rounded-xl
                           text-[10px] font-bold uppercase
                           tracking-widest transition whitespace-nowrap">
                <i class="fas fa-filter mr-1"></i>
                Lọc đánh giá
            </button>

            {{-- Đặt lại --}}
            <a href="{{ route('admin.comments.index') }}"
               class="w-full text-center bg-stone-100 hover:bg-stone-200
                      text-stone-600 px-4 py-2.5 rounded-xl
                      text-[10px] font-bold uppercase
                      tracking-widest transition whitespace-nowrap">
                <i class="fas fa-redo mr-1"></i>
                Đặt lại
            </a>

        </form>
    </div>

    {{-- ================= DANH SÁCH ĐÁNH GIÁ ================= --}}
    <div id="comment-table-container"
         class="w-full max-w-full bg-white rounded-2xl
                shadow-sm border border-red-100 overflow-hidden">

        <div class="w-full min-w-0 overflow-hidden comments-table-wrap">

            <table class="comments-table w-full min-w-[1050px]
                          table-fixed text-left border-collapse text-xs">

                <thead>
                    <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">

                        <th class="w-[10%] px-2 py-3">Người dùng</th>
                        <th class="w-[13%] px-2 py-3">Sản phẩm</th>
                        <th class="w-[6%] px-1 py-3 text-center">Số sao</th>
                        <th class="w-[16%] px-2 py-3">Nội dung đánh giá</th>
                        <th class="w-[8%] px-2 py-3 text-center">Hình ảnh</th>
                        <th class="w-[10%] px-1 py-3 text-center">Ngày đánh giá</th>
                        <th class="w-[20%] px-2 py-3">Phản hồi Admin</th>
                        <th class="w-[9%] px-1 py-3 text-center">Trạng thái</th>
                        <th class="w-[8%] px-1 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-red-50">

                    @forelse($comments as $cmt)

                        <tr class="comment-card hover:bg-red-50/40 transition">

                            {{-- NGƯỜI DÙNG --}}
                            <td data-label="Người dùng"
                                class="px-2 py-3 align-middle">

                                <p class="font-bold text-red-900 text-[11px] break-words">
                                    {{ $cmt->TenDangNhap }}
                                </p>
                            </td>

                            {{-- SẢN PHẨM --}}
                            <td data-label="Sản phẩm"
                                class="px-2 py-3 align-middle">

                                <p class="text-[11px] text-stone-700 break-words">
                                    {{ $cmt->TenSanPham }}
                                </p>
                            </td>

                            {{-- SỐ SAO --}}
                            <td data-label="Số sao"
                                class="px-1 py-3 text-center align-middle">

                                <span class="text-amber-500 font-bold
                                             text-[11px] whitespace-nowrap">
                                    <i class="fas fa-star"></i>
                                    {{ $cmt->SoSao }}/5
                                </span>
                            </td>

                            {{-- NỘI DUNG ĐÁNH GIÁ --}}
                            <td data-label="Nội dung đánh giá"
                                class="px-2 py-3 align-middle">

                                <p class="text-[11px] text-stone-600
                                          break-words line-clamp-4"
                                   title="{{ $cmt->NoiDung }}">
                                    {{ $cmt->NoiDung ?: 'Không có nội dung' }}
                                </p>
                            </td>

                            {{-- HÌNH ẢNH --}}
                            <td data-label="Hình ảnh"
                                class="px-2 py-3 text-center align-middle">

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
                                       rel="noopener noreferrer"
                                       title="Xem ảnh đánh giá">

                                        <img src="{{ $imageUrl }}"
                                             alt="Ảnh đánh giá"
                                             class="w-14 h-14 mx-auto object-cover
                                                    rounded-lg border border-stone-200
                                                    hover:scale-105 transition">
                                    </a>

                                @else

                                    <span class="text-[10px] text-stone-400 italic">
                                        Không có ảnh
                                    </span>

                                @endif
                            </td>

                            {{-- NGÀY ĐÁNH GIÁ --}}
                            <td data-label="Ngày đánh giá"
                                class="px-1 py-3 text-center align-middle">

                                <span class="text-[10px] text-stone-500 whitespace-nowrap">
                                    {{ $cmt->NgayDanhGia
                                        ? \Carbon\Carbon::parse($cmt->NgayDanhGia)->format('d/m/Y')
                                        : '---' }}
                                </span>
                            </td>

                            {{-- PHẢN HỒI ADMIN --}}
                            <td data-label="Phản hồi Admin"
                                class="px-2 py-3 align-middle">

                                @if(!empty($cmt->PhanHoi))

                                    <div class="bg-red-50 border border-red-100
                                                rounded-lg p-2">

                                        <p class="text-[10px] font-bold text-red-900
                                                  mb-1 uppercase tracking-wide">
                                            <i class="fas fa-reply mr-1"></i>
                                            Admin phản hồi
                                        </p>

                                        <p class="text-[11px] text-stone-600
                                                  break-words line-clamp-3"
                                           title="{{ $cmt->PhanHoi }}">
                                            {{ $cmt->PhanHoi }}
                                        </p>
                                    </div>

                                    <button type="button"
                                            onclick="toggleReply({{ $cmt->MaDanhGia }})"
                                            class="text-red-800 hover:text-red-950
                                                   font-bold text-[10px] mt-1.5 transition">

                                        <i class="fas fa-edit mr-1"></i>
                                        Chỉnh sửa
                                    </button>

                                @else

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
                                        rows="3"
                                        required
                                        maxlength="1000"
                                        placeholder="Nhập phản hồi..."
                                        class="w-full border border-stone-200
                                               bg-stone-50 rounded-lg px-2.5 py-2
                                               text-[11px] text-stone-700
                                               focus:bg-white focus:border-red-700
                                               resize-none transition"
                                    >{{ old('PhanHoi', $cmt->PhanHoi) }}</textarea>

                                    @error('PhanHoi')
                                        <p class="text-red-600 text-[10px] mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <button type="submit"
                                            class="mt-1.5 bg-red-800 hover:bg-red-900
                                                   text-white px-3 py-2 rounded-lg
                                                   text-[10px] font-bold uppercase
                                                   tracking-wide transition">

                                        <i class="fas fa-save mr-1"></i>
                                        Lưu 
                                    </button>
                                </form>
                            </td>

                            {{-- TRẠNG THÁI --}}
                            <td data-label="Trạng thái"
                                class="px-1 py-3 text-center align-middle">

                                @if($cmt->TrangThai == 1)

                                    <span class="inline-flex items-center justify-center
                                                 gap-1 px-2 py-1.5 rounded-full
                                                 text-[9px] font-bold bg-green-50
                                                 text-green-700 border border-green-100
                                                 whitespace-nowrap">

                                        <span class="w-1.5 h-1.5 shrink-0
                                                     rounded-full bg-green-600"></span>
                                        Hiển thị
                                    </span>

                                @else

                                    <span class="inline-flex items-center justify-center
                                                 gap-1 px-2 py-1.5 rounded-full
                                                 text-[9px] font-bold bg-stone-100
                                                 text-stone-500 border border-stone-200
                                                 whitespace-nowrap">

                                        <span class="w-1.5 h-1.5 shrink-0
                                                     rounded-full bg-stone-400"></span>
                                        Đã ẩn
                                    </span>

                                @endif
                            </td>

                            {{-- THAO TÁC --}}
                            <td data-label="Thao tác"
                                class="px-1 py-3 text-center align-middle">

                                @if($cmt->TrangThai == 1)

                                    <form method="POST"
                                          action="{{ route('admin.comments.hide', $cmt->MaDanhGia) }}"
                                          onsubmit="return confirmHideRating(event)">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                title="Ẩn đánh giá"
                                                class="inline-flex items-center justify-center
                                                       gap-1 bg-red-50 hover:bg-red-800
                                                       text-red-800 hover:text-white
                                                       border border-red-200 px-3 py-2
                                                       rounded-lg text-[10px] font-bold
                                                       transition whitespace-nowrap">

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
                                                class="inline-flex items-center justify-center
                                                       gap-1 bg-green-50 hover:bg-green-700
                                                       text-green-700 hover:text-white
                                                       border border-green-200 px-3 py-2
                                                       rounded-lg text-[10px] font-bold
                                                       transition whitespace-nowrap">

                                            <i class="fas fa-eye"></i>
                                            Hiện
                                        </button>
                                    </form>

                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr class="comment-empty-row">
                            <td colspan="9"
                                class="px-4 py-12 text-center text-stone-400">

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

        {{-- ================= PHÂN TRANG ================= --}}
        @if($comments->hasPages())
            <div class="px-4 py-3 border-t border-red-100 bg-red-50">
                {{ $comments->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif

    </div>
</div>

{{-- ================= SWEETALERT2 ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Mở / đóng form phản hồi
    function toggleReply(id) {
        const form = document.getElementById('reply-' + id);

        if (!form) return;

        form.classList.toggle('hidden');

        if (!form.classList.contains('hidden')) {
            const textarea = form.querySelector('textarea');

            if (textarea) {
                textarea.focus();
            }
        }
    }

    // Popup xác nhận dùng chung
    function confirmRatingAction(event, options) {
        event.preventDefault();

        const form = event.currentTarget;

        Swal.fire({
            title: options.title,
            text: options.text,
            icon: options.icon || 'question',

            showCancelButton: true,

            confirmButtonText: options.confirmText,
            cancelButtonText: 'Quay lại',

            confirmButtonColor: options.confirmColor || '#831c30',
            cancelButtonColor: '#78716c',

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

    // Xác nhận ẩn đánh giá
    function confirmHideRating(event) {
        return confirmRatingAction(event, {
            title: 'Ẩn đánh giá?',
            text: 'Đánh giá này sẽ không còn hiển thị cho khách hàng.',
            icon: 'warning',
            confirmText: 'Ẩn đánh giá',
            confirmColor: '#991b1b'
        });
    }

    // Xác nhận hiển thị đánh giá
    function confirmShowRating(event) {
        return confirmRatingAction(event, {
            title: 'Hiển thị lại đánh giá?',
            text: 'Đánh giá này sẽ được hiển thị lại cho khách hàng.',
            icon: 'question',
            confirmText: 'Hiển thị',
            confirmColor: '#831c30'
        });
    }

    // Toast thông báo thành công / thất bại
    function showRatingToast(icon, title, message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: title,
            text: message,
            showConfirmButton: false,
            showCloseButton: true,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'glass-popup'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    @if(session('success'))
        showRatingToast(
            'success',
            'Thao tác thành công',
            @json(session('success'))
        );
    @endif

    @if(session('error'))
        showRatingToast(
            'error',
            'Thao tác thất bại',
            @json(session('error'))
        );
    @endif

    @if($errors->has('PhanHoi'))
        showRatingToast(
            'error',
            'Không thể lưu phản hồi',
            @json($errors->first('PhanHoi'))
        );
    @endif
</script>


<style>
    .admin-comments-page {
        width: 100%;
        max-width: 100%;
        min-width: 0;
    }

    .admin-comments-page input:focus,
    .admin-comments-page select:focus,
    .admin-comments-page textarea:focus {
        outline: none;
        border-color: #831c30;
        box-shadow: 0 0 0 3px rgba(131, 28, 48, .08);
    }

    .admin-comments-page .comments-table tbody tr {
        transition: background .2s ease;
    }

    .admin-comments-page .comments-table {
        width: 100%;
        min-width: 0;
        table-layout: fixed;
    }
    .admin-comments-page .comments-table th,
    .admin-comments-page .comments-table td {
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
        padding-left: 5px;
        padding-right: 5px;
    }
    .admin-comments-page .comments-table td input,
    .admin-comments-page .comments-table td textarea,
    .admin-comments-page .comments-table td form {
        width: 100%;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }
    .admin-comments-page .comments-table td img { max-width: 100%; height: auto; }
    .admin-comments-page .comments-table-wrap { max-width: 100%; overflow-x: hidden; }
    @media (min-width: 768px) and (max-width: 1280px) {
        .admin-comments-page .comments-table th { font-size: 9px; letter-spacing: 0; }
        .admin-comments-page .comments-table td { font-size: 10px; }
        .admin-comments-page .comments-table td .whitespace-nowrap { white-space: normal; }
        .admin-comments-page .comments-table td button { max-width: 100%; white-space: normal; }
    }

    /* Responsive điện thoại */
    @media (max-width: 767px) {

        .admin-comments-page .comments-table-wrap {
            overflow: visible;
            padding: 10px;
            background: #fafaf9;
        }

        .admin-comments-page .comments-table {
            display: block;
            width: 100%;
            min-width: 0 !important;
            table-layout: auto;
        }

        .admin-comments-page .comments-table thead {
            display: none;
        }

        .admin-comments-page .comments-table tbody {
            display: grid;
            gap: 12px;
        }

        .admin-comments-page .comments-table .comment-card {
            display: block;
            width: 100%;
            padding: 8px 13px;
            background: white;
            border: 1px solid #fee2e2;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .035);
        }

        .admin-comments-page .comments-table .comment-card td {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            padding: 11px 0 !important;
            border-bottom: 1px solid #f5f5f4;
            text-align: right;
            white-space: normal;
            min-width: 0;
        }

        .admin-comments-page .comments-table .comment-card td:last-child {
            border-bottom: 0;
        }

        .admin-comments-page .comments-table .comment-card td::before {
            content: attr(data-label);
            flex: 0 0 35%;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #78716c;
        }

        .admin-comments-page .comments-table .comment-card td > * {
            max-width: 63%;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        /* Phản hồi Admin hiển thị toàn chiều rộng */
        .admin-comments-page .comments-table
        .comment-card td[data-label="Phản hồi Admin"] {
            display: block;
            text-align: left;
        }

        .admin-comments-page .comments-table
        .comment-card td[data-label="Phản hồi Admin"]::before {
            display: block;
            margin-bottom: 10px;
        }

        .admin-comments-page .comments-table
        .comment-card td[data-label="Phản hồi Admin"] > * {
            width: 100%;
            max-width: 100%;
        }

        .admin-comments-page .comments-table
        .comment-card td[data-label="Phản hồi Admin"] textarea {
            width: 100%;
        }

        /* Nội dung không bị cắt trên điện thoại */
        .admin-comments-page .comments-table
        .comment-card td[data-label="Nội dung đánh giá"] p,
        .admin-comments-page .comments-table
        .comment-card td[data-label="Phản hồi Admin"] p {
            display: block;
            -webkit-line-clamp: unset;
            overflow: visible;
        }

        /* Ảnh đánh giá */
        .admin-comments-page .comments-table
        .comment-card td[data-label="Hình ảnh"] img {
            margin-left: auto;
            margin-right: 0;
        }

        /* Dòng không có đánh giá */
        .admin-comments-page .comments-table .comment-empty-row {
            display: block;
        }

        .admin-comments-page .comments-table .comment-empty-row td {
            display: block;
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 380px) {
        .admin-comments-page .comments-table
        .comment-card td::before {
            flex-basis: 38%;
        }

        .admin-comments-page .comments-table
        .comment-card td > * {
            max-width: 60%;
        }
    }

    /* Popup SweetAlert */
    .glass-popup {
        background: rgba(255, 255, 255, .98) !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .12) !important;
    }
</style>
@endsection

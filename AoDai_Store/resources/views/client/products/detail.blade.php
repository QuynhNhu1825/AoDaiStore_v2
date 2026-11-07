@extends('layouts.client')

@section('title', $product->TenSanPham)

@section('content')

<div id="product-detail-page">
    <div class="product-main-layout grid grid-cols-2 gap-12">

        {{-- =========================
             CỘT 1: HÌNH ẢNH SẢN PHẨM
        ========================== --}}
        <div class="grid grid-cols-1 gap-4">

            <div class="relative" id="productGallery">

                {{-- Ảnh chính --}}
                <div id="zoomContainer"
                    class="relative h-[520px] w-full overflow-hidden bg-white cursor-crosshair">

                    <img
                        id="mainImage"
                        src="{{ asset('img/products/' . $product->HinhAnh) }}"
                        class="w-full h-full object-cover"
                        alt="{{ $product->TenSanPham }}"
                    >

                    <div id="zoomLens"></div>
                </div>

                {{-- Khung ảnh phóng to --}}
                <div id="zoomResult">
                    <img
                        id="zoomImage"
                        src="{{ asset('img/products/' . $product->HinhAnh) }}"
                        alt="Ảnh sản phẩm phóng to"
                    >
                </div>
            </div>

            {{-- Ảnh thumbnail --}}
            <div class="grid grid-cols-4 gap-3">

                <div class="aspect-square overflow-hidden border border-stone-300 hover:border-stone-800 cursor-pointer">
                    <img
                        src="{{ asset('img/products/' . $product->HinhAnh) }}"
                        onclick="changeImage(this.src)"
                        class="w-full h-full object-cover"
                        alt="Ảnh sản phẩm"
                    >
                </div>

                @foreach($product->hinhanhsanpham as $img)
                    <div class="aspect-square overflow-hidden border border-stone-300 hover:border-stone-800 cursor-pointer">
                        <img
                            src="{{ asset('img/details/' . $img->LinkAnh) }}"
                            onclick="changeImage(this.src)"
                            class="w-full h-full object-cover"
                            alt="Ảnh chi tiết {{ $loop->iteration }}"
                        >
                    </div>
                @endforeach

            </div>
        </div>


        {{-- =========================
             CỘT 2: THÔNG TIN SẢN PHẨM
        ========================== --}}
        <div class="space-y-6">

            @php
    $details = $product->chiTietSanPham
        ->where('IsDeleted', 0)
        ->values();

    // Lấy tất cả mã màu không trùng nhau
    $availableColors = $details
        ->filter(fn($detail) => $detail->color !== null)
        ->unique('MaMau')
        ->values();

    // Chọn màu đầu tiên làm mặc định
    $selectedColorId = $details->first()?->MaMau;
@endphp

            {{-- Đã thêm id="formAddToCart" vào thẻ form --}}
            <form id="formAddToCart" action="{{ route('cart.add', $product->MaSanPham) }}" method="POST">
                @csrf

                {{-- Tên sản phẩm --}}
                <div>
                    <nav class="text-xs text-stone-400 mb-2 uppercase tracking-widest">
                        Trang chủ / {{ $product->danhMucs->first()?->TenDanhMuc ?? 'Áo dài' }}
                    </nav>

                    <h1 class="serif text-4xl text-stone-900 leading-tight uppercase">
                        {{ $product->TenSanPham }}
                    </h1>

                    {{-- LƯỢT BÁN, YÊU THÍCH, ĐÁNH GIÁ --}}
                    {{-- LƯỢT BÁN, YÊU THÍCH, ĐÁNH GIÁ --}}
<div class="flex items-center flex-wrap gap-5 mt-3 mb-4 text-sm text-stone-500">

    {{-- Lượt bán --}}
    <span class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="19" height="19"
             viewBox="0 0 24 24"
             fill="none"
             stroke="#D97706"
             stroke-width="1.8"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M6 7h12l1 13H5L6 7Z"/>
            <path d="M9 7a3 3 0 0 1 6 0"/>
            <path d="M9 11v2m6-2v2"/>
        </svg>

        {{ number_format($luotBan ?? 0) }} lượt bán
    </span>

            {{-- Lượt yêu thích --}}
        <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg"
                width="19" height="19"
                viewBox="0 0 24 24"
                fill="#E11D48"
                stroke="#BE123C"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
            </svg>

            <span id="favorite-count">{{ $soLuotThich }}</span>
            lượt yêu thích
        </span>

            {{-- Đánh giá --}}
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="19" height="19"
                    viewBox="0 0 24 24"
                    fill="#EAB308"
                    stroke="#CA8A04"
                    stroke-width="1.2"
                    stroke-linejoin="round">
                    <path d="m12 2 3.09 6.26 6.91 1-5 4.87 1.18 6.87L12 17.77l-6.18 3.23L7 14.13 2 9.26l6.91-1L12 2Z"/>
                </svg>

                {{ $avgRating ? number_format($avgRating, 1) : '0.0' }}/5
            </span>


                                        {{-- NÚT YÊU THÍCH --}}
                <button
                    type="button"
                    id="btnFavorite"
                    data-id="{{ $product->MaSanPham }}"
                    data-authenticated="{{ Auth::check() ? '1' : '0' }}"
                    aria-pressed="{{ $isFavorite ? 'true' : 'false' }}"
                    class="flex items-center gap-2 px-4 py-2 border rounded-md transition duration-200
                        {{ $isFavorite
                            ? 'bg-amber-100 border-amber-600 text-amber-900'
                            : 'bg-white border-amber-700 text-amber-800 hover:bg-amber-50' }}"
                >
                    <svg
                        id="favorite-heart"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="21"
                        height="21"
                        fill="{{ $isFavorite ? '#DC2626' : 'none' }}"
                        stroke="{{ $isFavorite ? '#DC2626' : 'currentColor' }}"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
                    </svg>

                    <span id="favorite-label">
                        {{ $isFavorite ? 'Đã yêu thích' : 'Yêu thích' }}
                    </span>
                </button>
                 </div>
                    {{-- Giá sản phẩm --}}
                    <p id="product-price" class="text-2xl text-red-800 font-bold mt-2">
                        {{ number_format($product->chiTietSanPham->first()?->Gia ?? 0, 0, ',', '.') }} đ
                    </p>
                </div>


                {{-- CHỌN MÀU SẮC --}}
                <div class="mb-5 mt-6">
                    <div class="font-bold text-sm uppercase text-stone-700 mb-3">
                        Chọn màu sắc:
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @forelse($availableColors as $colorItem)
                            @php
                                $color = $colorItem->color;
                            @endphp

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="MaMau"
                                    value="{{ $colorItem->MaMau }}"
                                    class="color-radio peer hidden"
                                    {{ (int)$colorItem->MaMau === (int)$selectedColorId ? 'checked' : '' }}
                                    required
                                >

                                <span class="inline-flex items-center gap-2 border px-3 py-2 text-sm text-stone-700
                                    peer-checked:border-stone-900
                                    peer-checked:bg-stone-900
                                    peer-checked:text-white
                                    hover:border-stone-800 transition">

                                    @if(!empty($color->MaHex))
                                        <span
                                            class="inline-block w-4 h-4 rounded-full border border-stone-300"
                                            style="background-color: {{ $color->MaHex }}">
                                        </span>
                                    @endif

                                    {{ $color->TenMau }}
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-red-600">
                                Sản phẩm chưa có màu sắc.
                            </p>
                        @endforelse
                    </div>
                </div>


                {{-- CHỌN KÍCH CỠ --}}
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-bold text-sm uppercase text-stone-700">
                            Chọn kích cỡ:
                        </span>
                        <span id="stock-info" class="text-xs font-bold text-red-700 uppercase"></span>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @foreach($allSizes as $s)
                            @php
                                $matchingDetail = $details->first(function ($detail) use ($s, $selectedColorId) {
                                    return (int) $detail->MaSize === (int) $s->MaSize
                                        && (int) $detail->MaMau === (int) $selectedColorId
                                        && !$detail->IsDeleted;
                                });

                                $stock = $matchingDetail ? (int) $matchingDetail->SoLuong : 0;
                            @endphp

                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="MaSize"
                                    value="{{ $s->MaSize }}"
                                    class="size-radio peer hidden"
                                    data-size="{{ $s->MaSize }}"
                                    data-stock="{{ $stock }}"
                                    data-price="{{ $matchingDetail?->Gia ?? 0 }}"
                                    {{ $stock <= 0 ? 'disabled' : '' }}
                                    required
                                >
                                <span class="size-label w-14 h-12 border flex items-center justify-center transition relative
                                    peer-checked:bg-stone-800 peer-checked:text-white peer-checked:border-stone-800
                                    {{ $stock <= 0 ? 'bg-stone-50 text-stone-300 cursor-not-allowed opacity-60' : 'hover:border-stone-800 text-stone-700' }}">
                                    {{ $s->TenSize }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>


                {{-- SỐ LƯỢNG --}}
                <div class="py-4">
                    <span class="font-bold text-sm uppercase text-stone-700 block mb-3">
                        Số lượng:
                    </span>
                    <div class="flex items-center w-32 border border-stone-300">
                        <button
                            type="button"
                            onclick="changeQty(-1)"
                            class="w-10 h-10 flex items-center justify-center hover:bg-stone-100"
                        >
                            -
                        </button>
                        <input
                            type="number"
                            name="SoLuong"
                            id="quantity"
                            value="1"
                            min="1"
                            max="1"
                            class="w-12 h-10 text-center bg-transparent font-semibold focus:ring-0"
                        >
                        <button
                            type="button"
                            onclick="changeQty(1)"
                            class="w-10 h-10 flex items-center justify-center hover:bg-stone-100"
                        >
                            +
                        </button>
                    </div>
                </div>


                {{-- MAY THEO SỐ ĐO RIÊNG --}}
                <div class="bg-stone-100 p-4 rounded-sm border-l-4 border-stone-800 my-6">
                    <p class="text-sm font-bold uppercase mb-1">
                        📐 May theo số đo riêng?
                    </p>
                    <p class="text-xs text-stone-500 italic">
                        Để lại ghi chú khi thanh toán hoặc nhắn tin trực tiếp để được tư vấn chi tiết.
                    </p>
                </div>


                {{-- NÚT THÊM VÀO GIỎ HÀNG --}}
                <button
                    type="submit"
                    class="w-full bg-stone-900 text-white py-4 font-bold uppercase tracking-widest hover:bg-black"
                >
                    Thêm vào giỏ hàng
                </button>

            </form>
        </div>
    </div>

{{-- THÔNG BÁO THÊM GIỎ HÀNG THÀNH CÔNG --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Thêm vào giỏ hàng thành công!',
            text: @json(session('success')),
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false,
            confirmButtonColor: '#991b1b'
        });
    });
</script>
@endif

{{-- THÔNG BÁO THÊM GIỎ HÀNG THẤT BẠI --}}
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Không thể thêm vào giỏ hàng!',
            text: @json(session('error')),
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    });
</script>
@endif


{{-- =========================
     CHI TIẾT VÀ MÔ TẢ SẢN PHẨM
========================== --}}
@php
    $productDetails = $product->chiTietSanPham->where('IsDeleted', false);
    $totalStock = $productDetails->sum('SoLuong');
    $prices = $productDetails->pluck('Gia')->filter();
    $colors = $productDetails->pluck('color')->filter()->unique('MaMau');
    $sizes = $productDetails->pluck('size')->filter()->unique('MaSize');
@endphp


<div class="mt-12 grid grid-cols-2 gap-6 items-stretch">

    {{-- CỘT 1: CHI TIẾT SẢN PHẨM --}}
    <section class="min-w-0 h-full bg-white border border-stone-200 rounded-sm overflow-hidden">
        <div class="bg-[#A71920] px-5 md:px-4 py-4 border-b border-[#A71920] text-center">
            <h2 class="text-lg md:text-xl font-medium uppercase tracking-wide text-white">
                Chi tiết sản phẩm
            </h2>
        </div>

        <div class="px-5 md:px-6 py-6 space-y-6 text-sm">
            {{-- Danh mục --}}
            <div class="grid grid-cols-[110px_1fr] md:grid-cols-[120px_1fr] gap-3">
                <span class="text-stone-400">Danh mục</span>
                <div class="text-stone-700 break-words">
                    <span class="text-amber-800">Áo Dài Heritage</span>
                    <span class="text-stone-400 mx-1">›</span>
                    <span>{{ $product->danhMucs->first()?->TenDanhMuc ?? 'Áo dài' }}</span>
                </div>
            </div>

            {{-- Tên sản phẩm --}}
            <div class="grid grid-cols-[110px_1fr] md:grid-cols-[120px_1fr] gap-3">
                <span class="text-stone-400">Tên sản phẩm</span>
                <span class="text-stone-700 break-words">{{ $product->TenSanPham }}</span>
            </div>

            {{-- Giá bán --}}
            <div class="grid grid-cols-[110px_1fr] md:grid-cols-[120px_1fr] gap-3">
                <span class="text-stone-400">Giá bán</span>
                <div class="text-stone-700">
                    @if($prices->isNotEmpty())
                        @if((float) $prices->min() == (float) $prices->max())
                            {{ number_format((float) $prices->min(), 0, ',', '.') }} đ
                        @else
                            {{ number_format((float) $prices->min(), 0, ',', '.') }} đ -
                            {{ number_format((float) $prices->max(), 0, ',', '.') }} đ
                        @endif
                    @else
                        Đang cập nhật
                    @endif
                </div>
            </div>

            {{-- Tồn kho --}}
            <div class="grid grid-cols-[110px_1fr] md:grid-cols-[120px_1fr] gap-3">
                <span class="text-stone-400">Tồn kho</span>
                <span class="{{ $totalStock > 0 ? 'text-stone-700' : 'text-red-600' }}">
                    {{ $totalStock > 0 ? number_format($totalStock) . ' sản phẩm' : 'Hết hàng' }}
                </span>
            </div>

            {{-- Màu sắc --}}
            <div class="grid grid-cols-[110px_1fr] md:grid-cols-[120px_1fr] gap-3">
                <span class="text-stone-400">Màu sắc</span>

                <div class="flex flex-wrap gap-2">
                    @forelse($availableColors as $colorItem)
                        <span class="inline-block border border-stone-200 px-3 py-1 text-stone-700">
                            {{ $colorItem->color->TenMau }}
                        </span>
                    @empty
                        <span class="text-stone-400">Đang cập nhật</span>
                    @endforelse
                </div>
            </div>

            {{-- Kích thước --}}
            <div class="grid grid-cols-[110px_1fr] md:grid-cols-[120px_1fr] gap-3">
                <span class="text-stone-400">Kích thước</span>
                <div class="flex flex-wrap gap-2">
                    @forelse($sizes as $size)
                        <span class="inline-block border border-stone-200 px-3 py-1 text-stone-700">
                            {{ $size->TenSize ?? $size->TenKichThuoc ?? $size->MaSize }}
                        </span>
                    @empty
                        <span class="text-stone-400">Đang cập nhật</span>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- CỘT 2: MÔ TẢ SẢN PHẨM --}}
    <section class="min-w-0 h-full bg-white border border-stone-200 rounded-sm overflow-hidden">
        <div class="bg-[#A71920] px-5 md:px-4 py-4 border-b border-[#A71920] text-center">
            <h2 class="text-lg md:text-xl font-medium uppercase tracking-wide text-white">
                Mô tả sản phẩm
            </h2>
        </div>

        <div class="px-5 md:px-6 py-6 space-y-6">
            {{-- GIỚI THIỆU SẢN PHẨM --}}
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-stone-800 mb-1">
                    Giới thiệu sản phẩm
                </h3>
                @if(!empty($product->MoTa))
                    <div class="text-sm text-stone-600 leading-5 whitespace-pre-line break-words">
                        {{ $product->MoTa }}
                    </div>
                @else
                    <p class="text-sm text-stone-400 italic">Sản phẩm chưa có mô tả.</p>
                @endif
            </div>

            {{-- THÔNG TIN SẢN PHẨM --}}
            <div class="pt-5 border-t border-stone-200">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-stone-800 mb-3">
                    Thông tin sản phẩm
                </h3>
                <div class="space-y-2 text-sm text-stone-600">
                    <div class="flex gap-2">
                        <span class="w-24 shrink-0 font-semibold text-stone-700">Chất liệu:</span>
                        <span>{{ $product->chatlieu?->TenChatLieu ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-24 shrink-0 font-semibold text-stone-700">Xuất xứ:</span>
                        <span>{{ $product->chatlieu?->XuatXu ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-24 shrink-0 font-semibold text-stone-700">Màu sắc:</span>
                        <span>
                            {{ $product->chiTietSanPham->pluck('color.TenMau')->filter()->unique()->implode(', ') ?: 'Chưa cập nhật' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- HƯỚNG DẪN BẢO QUẢN --}}
            <div class="pt-3 border-t border-stone-200">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-stone-800 mb-1">
                    Hướng dẫn bảo quản
                </h3>
                @if(!empty($product->chatlieu?->HuongDanBaoQuan))
                    <div class="text-sm text-stone-600 leading-5 whitespace-pre-line break-words">
                        {{ $product->chatlieu->HuongDanBaoQuan }}
                    </div>
                @else
                    <p class="text-sm text-stone-400 italic">Chưa có hướng dẫn bảo quản cho sản phẩm này.</p>
                @endif
            </div>
        </div>
    </section>

</div>


{{-- =========================
     ĐÁNH GIÁ SẢN PHẨM
========================== --}}
<details class="w-full mt-12 border-t border-stone-200 group">

    {{-- TIÊU ĐỀ CÓ THỂ BẤM --}}
    <summary class="list-none cursor-pointer py-6 flex items-center justify-between
                    hover:bg-stone-50 transition duration-200">

        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-amber-700 mb-1">
                Cảm nhận khách hàng
            </p>

            <div class="flex items-center gap-3">
                <h2 class="serif text-2xl font-bold text-stone-900">
                    Đánh giá sản phẩm
                </h2>

                <span class="text-sm text-stone-500">
                    ({{ $dsDanhGia->count() }} đánh giá)
                </span>
            </div>
        </div>

        {{-- ICON MŨI TÊN --}}
        <svg
            class="w-6 h-6 text-stone-600 transition-transform duration-300
                   group-open:rotate-180"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m19 9-7 7-7-7"/>
        </svg>

    </summary>


    {{-- NỘI DUNG ĐÁNH GIÁ --}}
    <div class="pb-8 pt-2">

        <div class="space-y-2 max-w-5xl">

            @forelse($dsDanhGia as $dg)

                <div class="bg-white border border-stone-200 rounded-lg px-4 py-2.5
                            transition duration-300
                            hover:border-amber-300 hover:shadow-md">

                    <div class="flex items-start gap-4">

                        {{-- AVATAR --}}
                        <div class="w-10 h-10 shrink-0 rounded-full
                                    bg-[#F5E9E3] border border-[#E8D3C8]
                                    flex items-center justify-center">

                            <span class="text-lg font-bold text-[#A71920] uppercase">
                                {{ mb_substr($dg->TenDangNhap ?? 'K', 0, 1, 'UTF-8') }}
                            </span>

                        </div>

                        {{-- THÔNG TIN ĐÁNH GIÁ --}}
                        <div class="flex-1 min-w-0">

                            <div class="flex flex-wrap items-center justify-between gap-2">

                                <div>
                                    <h3 class="font-bold text-stone-900 text-base">
                                        {{ $dg->TenDangNhap ?? 'Khách hàng' }}
                                    </h3>

                                    <p class="text-xs text-stone-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($dg->NgayDanhGia)->format('d/m/Y') }}
                                    </p>
                                </div>

                                {{-- SỐ SAO --}}
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl {{ $i <= $dg->SoSao ? 'text-amber-500' : 'text-stone-300' }}">
                                            ★
                                        </span>
                                    @endfor
                                </div>

                            </div>

                            {{-- NỘI DUNG --}}
                            @if(!empty($dg->NoiDung))
                                <p class="text-stone-600 text-sm leading-5 mt-1.5
                                          whitespace-pre-line break-words">
                                    {{ $dg->NoiDung }}
                                </p>
                            @else
                                <p class="text-stone-400 text-sm italic mt-3">
                                    Khách hàng không để lại nội dung đánh giá.
                                </p>
                            @endif
                            {{-- HÌNH ẢNH ĐÁNH GIÁ --}}
                            @if(!empty($dg->HinhAnh))
                                <div class="mt-3 flex flex-wrap gap-3">
                                    <img
                                        src="{{ asset('img/ratings/' . $dg->HinhAnh) }}"
                                        alt="Ảnh đánh giá sản phẩm"
                                        onclick="openReviewImage(this.src)"
                                        class="w-24 h-24 object-cover rounded-lg
                                            border border-stone-200
                                            hover:scale-105 hover:shadow-md
                                            transition duration-300 cursor-zoom-in"
                                        onerror="this.style.display='none'"
                                    >
                                </div>
                            @endif
                        {{-- PHẢN HỒI CỦA ADMIN --}}
                        @if(!empty($dg->PhanHoi))
                            <div class="mt-2 ml-2 sm:ml-4 border-l-[3px] border-[#A71920] bg-[#FAF5F3] rounded-r-lg px-3 py-2">

                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="w-6 h-6 rounded-full bg-[#F5E9E3] flex items-center justify-center">
                                        <i class="fas fa-store text-[#A71920] text-[10px]"></i>
                                    </span>

                                    <span class="text-sm font-bold text-[#A71920]">
                                        Áo Dài Heritage
                                    </span>

                                    <span class="text-[10px] text-stone-400">
                                        · Phản hồi từ cửa hàng
                                    </span>
                                </div>

                                <p class="text-sm text-stone-600 leading-5 whitespace-pre-line break-words">
                                    {{ $dg->PhanHoi }}
                                </p>

                            </div>
                        @endif
                        </div>
                    </div>

                </div>

            @empty

                <div class="bg-[#FAF8F5] border border-dashed border-stone-300
                            rounded-lg py-10 px-5 text-center">

                    <p class="text-3xl text-amber-500 mb-3">☆</p>

                    <h3 class="font-semibold text-stone-800 mb-2">
                        Chưa có đánh giá nào
                    </h3>

                    <p class="text-sm text-stone-500">
                        Hãy là người đầu tiên chia sẻ cảm nhận về sản phẩm này nhé!
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</details>
                {{-- POPUP XEM ẢNH ĐÁNH GIÁ --}}
                <div id="reviewImageModal"
                    class="fixed inset-0 z-[99999] hidden
                            items-center justify-center
                            bg-black/80 p-4
                            backdrop-blur-sm"
                    onclick="closeReviewImage()">

                    {{-- Nút đóng --}}
                    <button type="button"
                            onclick="closeReviewImage()"
                            class="absolute top-5 right-7 text-white
                                text-4xl font-light hover:text-amber-400
                                transition">
                        &times;
                    </button>

                    {{-- Ảnh phóng to --}}
                    <img id="reviewImagePreview"
                        src=""
                        alt="Ảnh đánh giá phóng to"
                        onclick="event.stopPropagation()"
                        class="max-w-full max-h-[85vh] object-contain
                                rounded-lg shadow-2xl cursor-default">
                </div>

{{-- =========================
     SẢN PHẨM LIÊN QUAN
========================== --}}
<div class="w-full px-4 py-14 border-t mt-12">
    <h2 class="serif text-2xl font-bold uppercase tracking-wide text-stone-800 mb-6">
        Sản phẩm liên quan
    </h2>
    <div class="related-slider">
        <div class="related-track">
            @foreach($relatedProducts as $item)
                <div class="related-item">
                    <a href="{{ route('product.detail', $item->MaSanPham) }}">
                        <div class="related-img">
                            <img
                                src="{{ asset('img/products/' . $item->HinhAnh) }}"
                                alt="{{ $item->TenSanPham }}"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <h3 class="related-title">{{ $item->TenSanPham }}</h3>
                        <p class="related-category">
                            {{ $item->loaisanpham->TenLoaiSP ?? 'Áo dài' }}
                        </p>
                        <p class="related-price">
                            {{ number_format($item->chiTietSanPham->first()?->Gia ?? 0, 0, ',', '.') }} đ
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>


{{-- =========================
     POPUP YÊU CẦU ĐĂNG NHẬP
========================== --}}
    <div id="loginFavoriteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">
        <div class="bg-white w-full max-w-sm rounded-lg p-6 text-center shadow-xl relative">
            <button type="button" id="closeLoginFavorite" class="absolute top-3 right-4 text-2xl text-stone-400 hover:text-black">&times;</button>
            <div class="flex justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="w-10 h-10 text-red-500"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8z"/>
        </svg>
    </div>
        <h3 class="text-lg font-bold text-stone-800 mb-2">Đăng nhập để yêu thích sản phẩm</h3>
        <p class="text-sm text-stone-500 mb-6">
            Vui lòng đăng nhập tài khoản để lưu sản phẩm yêu thích và dễ dàng tìm lại sau này.
        </p>
        <a href="{{ route('login') }}" class="block w-full bg-stone-900 text-white py-3 font-semibold uppercase text-sm hover:bg-black">
            Đăng nhập ngay
        </a>
        <button type="button" id="continueShopping" class="mt-3 text-sm text-stone-500 hover:text-stone-900">
            Tiếp tục mua sắm
        </button>
    </div>
</div>


{{-- =========================
     JAVASCRIPT
========================== --}}
@php
    $variantData = \App\Models\ProductDetail::query()
        ->where('MaSanPham', $product->MaSanPham)
        ->where('IsDeleted', 0)
        ->get(['MaMau', 'MaSize', 'Gia', 'SoLuong'])
        ->map(fn ($detail) => [
            'MaMau' => (int) $detail->MaMau,
            'MaSize' => (int) $detail->MaSize,
            'Gia' => (float) $detail->Gia,
            'SoLuong' => (int) $detail->SoLuong,
        ])
        ->values()
        ->all();
@endphp

<script>
    // ===============================
    // DỮ LIỆU BIẾN THỂ SẢN PHẨM
    // ===============================
    const productDetails = @json($variantData);

    // ===============================
    // XỬ LÝ ẢNH VÀ ZOOM
    // ===============================
    const mainImage = document.getElementById('mainImage');
    const zoomImage = document.getElementById('zoomImage');
    const zoomContainer = document.getElementById('zoomContainer');
    const zoomLens = document.getElementById('zoomLens');
    const zoom = 1.2;

    function changeImage(src) {
        mainImage.src = src;
        zoomImage.src = src;
    }

    zoomContainer.addEventListener('mousemove', function (e) {
        const rect = zoomContainer.getBoundingClientRect();
        
        // SỬA LỖI MẤT DẤU TRỪ VÀ DẤU NHÂN Ở ĐOẠN NÀY
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const lensWidth = zoomLens.offsetWidth;
        const lensHeight = zoomLens.offsetHeight;

        let lensX = x - (lensWidth / 2);
        let lensY = y - (lensHeight / 2);

        lensX = Math.max(0, Math.min(lensX, rect.width - lensWidth));
        lensY = Math.max(0, Math.min(lensY, rect.height - lensHeight));

        zoomLens.style.left = lensX + 'px';
        zoomLens.style.top = lensY + 'px';

        const scaleX = (zoomContainer.offsetWidth / lensWidth) * zoom;
        const scaleY = (zoomContainer.offsetHeight / lensHeight) * zoom;

        zoomImage.style.width = (zoomContainer.offsetWidth * scaleX) + 'px';
        zoomImage.style.height = (zoomContainer.offsetHeight * scaleY) + 'px';

        zoomImage.style.left = (-lensX * scaleX) + 'px';
        zoomImage.style.top = (-lensY * scaleY) + 'px';
    });

    zoomContainer.addEventListener('mouseleave', function () {
        zoomLens.style.display = 'none';
    });

    zoomContainer.addEventListener('mouseenter', function () {
        zoomLens.style.display = 'block';
    });


    // ===============================
    // CHỌN MÀU VÀ KÍCH CỠ
    // ===============================
    const qtyInput = document.getElementById('quantity');
    const stockInfo = document.getElementById('stock-info');
    const colorRadios = document.querySelectorAll('.color-radio');
    const sizeRadios = document.querySelectorAll('.size-radio');

    function getSelectedColor() {
        return document.querySelector('input[name="MaMau"]:checked')?.value;
    }

    function updateVariantOptions() {
        const selectedColor = parseInt(getSelectedColor() || '0', 10);
        let firstAvailableSize = null;

        sizeRadios.forEach(radio => {
            const sizeId = parseInt(radio.value, 10);
            const detail = productDetails.find(item =>
                Number(item.MaMau) === selectedColor &&
                Number(item.MaSize) === sizeId
            );
            const stock = detail ? Number(detail.SoLuong) : 0;
            const label = radio.closest('label');
            const span = label?.querySelector('.size-label');

            radio.dataset.stock = stock;
            radio.dataset.price = detail ? detail.Gia : 0;
            radio.disabled = stock <= 0;

            if (span) {
                span.classList.toggle('opacity-60', stock <= 0);
                span.classList.toggle('text-stone-300', stock <= 0);
                span.classList.toggle('cursor-not-allowed', stock <= 0);
                span.classList.toggle('bg-stone-50', stock <= 0);
                span.classList.toggle('text-stone-700', stock > 0);
                span.classList.toggle('hover:border-stone-800', stock > 0);
            }

            if (stock > 0 && firstAvailableSize === null) {
                firstAvailableSize = radio;
            }

            if (radio.checked && stock <= 0) {
                radio.checked = false;
            }
        });

        const selectedSize = document.querySelector('input[name="MaSize"]:checked');
        if (!selectedSize && firstAvailableSize) {
            firstAvailableSize.checked = true;
        }

        updateSelectedVariant();
    }

    function updateSelectedVariant() {
        const selectedColor = parseInt(getSelectedColor() || '0', 10);
        const selectedSize = document.querySelector('input[name="MaSize"]:checked');
        const sizeId = selectedSize ? parseInt(selectedSize.value, 10) : 0;
        
        const detail = productDetails.find(item =>
            Number(item.MaMau) === selectedColor &&
            Number(item.MaSize) === sizeId
        );

        const stock = detail ? Number(detail.SoLuong) : 0;
        qtyInput.max = Math.max(stock, 1);

        if (stock > 0) {
            stockInfo.innerText = `(Còn ${stock} sản phẩm)`;
            if (parseInt(qtyInput.value || '1', 10) > stock) {
                qtyInput.value = 1;
            }
            qtyInput.disabled = false;
        } else {
            stockInfo.innerText = 'Vui lòng chọn kích cỡ';
            qtyInput.value = 1;
            qtyInput.disabled = true;
        }

        const priceElement = document.getElementById('product-price');
        if (priceElement && detail) {
            priceElement.innerText = new Intl.NumberFormat('vi-VN').format(detail.Gia) + ' đ';
        }
    }

    colorRadios.forEach(radio => {
        radio.addEventListener('change', updateVariantOptions);
    });

    sizeRadios.forEach(radio => {
        radio.addEventListener('change', updateSelectedVariant);
    });

    function changeQty(step) {
        const selected = document.querySelector('input[name="MaSize"]:checked');
        if (!selected) {
            alert('Vui lòng chọn màu sắc và kích cỡ trước!');
            return;
        }

        const max = parseInt(selected.dataset.stock || '0', 10);
        let val = parseInt(qtyInput.value || '1', 10) + step;

        if (val < 1) val = 1;
        if (val > max) val = max;

        qtyInput.value = val;
    }

    // Khởi tạo biến thể mặc định
    updateVariantOptions();


    // ===============================
    // ===============================
// YÊU THÍCH SẢN PHẨM
// ===============================
const favoriteButton = document.getElementById('btnFavorite');
const loginFavoriteModal = document.getElementById('loginFavoriteModal');

if (favoriteButton) {
    favoriteButton.addEventListener('click', function (e) {

        if (this.dataset.authenticated === '0') {
            e.preventDefault();
            loginFavoriteModal.classList.remove('hidden');
            loginFavoriteModal.classList.add('flex');
            return;
        }

        if (this.disabled) return;

        const productId = this.dataset.id;
        const button = this;

        button.disabled = true;

        fetch(`/favorite/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                loginFavoriteModal.classList.remove('hidden');
                loginFavoriteModal.classList.add('flex');
                return null;
            }

            if (!response.ok) {
                throw new Error('Không thể cập nhật yêu thích');
            }

            return response.json();
        })
        .then(data => {
            if (!data) return;

            const heart = document.getElementById('favorite-heart');
            const label = document.getElementById('favorite-label');

            if (data.isFavorite) {
                heart.setAttribute('fill', '#DC2626');
                heart.setAttribute('stroke', '#DC2626');

                label.textContent = 'Đã yêu thích';

                button.classList.remove(
                    'bg-white',
                    'border-amber-700',
                    'text-amber-800',
                    'hover:bg-amber-50'
                );

                button.classList.add(
                    'bg-amber-100',
                    'border-amber-600',
                    'text-amber-900'
                );

            } else {
                heart.setAttribute('fill', 'none');
                heart.setAttribute('stroke', 'currentColor');

                label.textContent = 'Yêu thích';

                button.classList.remove(
                    'bg-amber-100',
                    'border-amber-600',
                    'text-amber-900'
                );

                button.classList.add(
                    'bg-white',
                    'border-amber-700',
                    'text-amber-800',
                    'hover:bg-amber-50'
                );
            }

            button.setAttribute(
                'aria-pressed',
                data.isFavorite ? 'true' : 'false'
            );

            document.getElementById('favorite-count').textContent = data.favoriteCount;
        })
        .catch(error => {
            alert(error.message);
        })
        .finally(() => {
            button.disabled = false;
        });
    });
}
    function closeFavoriteModal() {
        loginFavoriteModal.classList.add('hidden');
        loginFavoriteModal.classList.remove('flex');
    }

    document.getElementById('closeLoginFavorite')?.addEventListener('click', closeFavoriteModal);
    document.getElementById('continueShopping')?.addEventListener('click', closeFavoriteModal);

    loginFavoriteModal?.addEventListener('click', function (e) {
        if (e.target === loginFavoriteModal) closeFavoriteModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeFavoriteModal();
    });

    // ===============================
    // XỬ LÝ THÊM VÀO GIỎ HÀNG CHO KHÁCH VÃNG LAI
    // ===============================
    const formAddToCart = document.getElementById('formAddToCart');
    // PHP trả về 'false' (chuỗi) nếu đăng nhập, 'true' nếu chưa
    const isGuest = {{ Auth::check() ? 'false' : 'true' }};

    if (formAddToCart) {
        formAddToCart.addEventListener('submit', function(e) {
            
            // Nếu là khách vãng lai
            if (isGuest) {
                e.preventDefault(); // Ngăn form gửi request lên server

                const colorInput = document.querySelector('input[name="MaMau"]:checked');
                const sizeInput = document.querySelector('input[name="MaSize"]:checked');
                const qtyInput = document.getElementById('quantity');

                if (!colorInput || !sizeInput) {
                    alert('Vui lòng chọn đầy đủ màu sắc và kích cỡ!');
                    return;
                }

                // Dữ liệu cần thiết để lưu vào giỏ hàng
                const productId = "{{ $product->MaSanPham }}";
                const productName = "{{ $product->TenSanPham }}";
                const productImage = "{{ $product->HinhAnh }}"; // Chỉ lưu tên file ảnh
                
                const colorId = colorInput.value;
                const colorName = colorInput.nextElementSibling.innerText.trim();
                
                const sizeId = sizeInput.value;
                const sizeName = sizeInput.nextElementSibling.innerText.trim();

                const qty = parseInt(qtyInput.value) || 1;
                const price = parseFloat(sizeInput.dataset.price || 0); 

                // Lấy giỏ hàng từ LocalStorage
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];

                // Kiểm tra xem sản phẩm có trong giỏ chưa
                let existingIndex = cart.findIndex(item => 
                    item.productId == productId && 
                    item.colorId == colorId && 
                    item.sizeId == sizeId
                );

                if (existingIndex > -1) {
                    cart[existingIndex].SoLuong += qty;
                } else {
                    cart.push({
                        productId: productId,
                        productName: productName,
                        productImage: productImage,
                        price: price,
                        colorId: colorId,
                        colorName: colorName,
                        sizeId: sizeId,
                        sizeName: sizeName,
                        SoLuong: qty
                    });
                }

                // Cập nhật LocalStorage
localStorage.setItem('guest_cart', JSON.stringify(cart));

// Cập nhật số lượng trên biểu tượng giỏ hàng
if (typeof updateGuestCartBadge === 'function') {
    updateGuestCartBadge();
}


 // Thông báo thêm giỏ hàng thành công
Swal.fire({
    icon: 'success',
    title: 'Thêm vào giỏ hàng thành công!',
    text: 'Sản phẩm đã được thêm vào giỏ hàng của bạn.',
    timer: 1000,
    timerProgressBar: true,
    showConfirmButton: false,
    showCancelButton: false,
    allowOutsideClick: true
});             
            }
            // Nếu ĐÃ ĐĂNG NHẬP, form sẽ tự động gửi đi như bình thường do không vào if
        });
    }
// ===============================
// POPUP XEM ẢNH ĐÁNH GIÁ
// ===============================

function openReviewImage(src) {
    const modal = document.getElementById('reviewImageModal');
    const preview = document.getElementById('reviewImagePreview');

    preview.src = src;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.style.overflow = 'hidden';
}

function closeReviewImage() {
    const modal = document.getElementById('reviewImageModal');
    const preview = document.getElementById('reviewImagePreview');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    preview.src = '';

    document.body.style.overflow = '';
}

// Nhấn ESC để đóng ảnh
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReviewImage();
    }
});
</script>

{{-- =========================
     CSS ZOOM ẢNH
========================== --}}
<style>
    #zoomLens {
        position: absolute;
        display: none;
        width: 35%;
        height: 38%;
        border: 1px solid rgba(255, 255, 255, 0.9);
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
        pointer-events: none;
        z-index: 10;
    }
    #zoomResult {
        display: none;
        position: absolute;
        top: 0;
        left: calc(100% + 12px);
        width: 70%;
        height: 70%;
        overflow: hidden;
        background: white;
        border: 1px solid #e5e5e5;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        z-index: 100;
        pointer-events: none;
    }
    #zoomImage {
        position: absolute;
        width: 100%;
        height: 100%;
        max-width: none;
        object-fit: fill;
        pointer-events: none;
    }
    @media (min-width: 768px) {
        #zoomContainer:hover #zoomLens {
            display: block;
        }
        #zoomContainer:hover ~ #zoomResult {
            display: block;
        }
    }
    @media (max-width: 767px) {
        #zoomLens,
        #zoomResult {
            display: none !important;
        }
        #zoomContainer {
            cursor: default;
        }
    }
</style>
<style>
    html {
        overflow-x: auto;
    }

    #product-detail-page {
        width: 1200px;
        min-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    @media (max-width: 1200px) {
        #product-detail-page {
            margin-left: 0;
            margin-right: 0;
        }
    }

    #product-detail-page * {
        box-sizing: border-box;
    }
</style>
@endsection
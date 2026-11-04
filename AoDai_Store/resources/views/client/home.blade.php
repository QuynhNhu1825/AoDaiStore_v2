@extends('layouts.client')

@section('title', 'Nét đẹp truyền thống Việt')

@section('content')

{{-- ==================== BANNER ==================== --}}
<section class="css-carousel">
    <div class="slides">
        <div class="slide">
            <img
                src="https://static.dchic.vn/uploads/media/2025/12/BLOG%20WEB%20MO%20BAN%20SOM%20AO%20DAI-325256125.jpg"
                alt="Bộ sưu tập áo dài Heritage"
            >

            <div class="content">
                <h1>Dáng Việt Kiêu Sa</h1>
                <p>Tôn vinh vẻ đẹp vĩnh cửu của phụ nữ Việt</p>
                <a href="#product-section">Khám phá ngay</a>
            </div>
        </div>

        <div class="slide">
            <img
                src="{{ asset('img/logo/download.jpg') }}"
                alt="Thông tin cửa hàng Áo Dài Heritage"
            >
        </div>
        <div class="slide">
            <img
                src="{{ asset('img/logo/Hương Thời Gian, Sắc Di Sản.png') }}"
                alt="Thông tin cửa hàng Áo Dài Heritage"
            >
        </div>
    </div>
</section>


{{-- ==================== BỘ LỌC SẢN PHẨM ==================== --}}
<section
    id="product-section"
    class="bg-stone-50 border-y border-stone-200 py-8 sticky top-0 z-20 shadow-sm"
>
    <div class="max-w-7xl mx-auto px-4">
        <form
            id="filter-form"
            action="{{ route('home') }}"
            method="GET"
            class="flex flex-col md:flex-row gap-4 items-center justify-between"
        >
            {{-- Tìm kiếm --}}
            <div class="relative w-full md:w-1/3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm tên sản phẩm..."
                    class="w-full pl-10 pr-4 py-2 bg-white border border-stone-300 focus:border-red-800 focus:ring-0 rounded-lg text-sm transition-all"
                >

                <div class="absolute left-3 top-2.5 text-stone-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 w-full md:w-auto">

                {{-- Lọc màu sắc --}}
                <select
                    name="color"
                    class="filter-select border-stone-300 focus:border-red-800 focus:ring-0 rounded-lg text-sm min-w-[150px] cursor-pointer bg-white"
                >
                    <option value="">Tất cả màu sắc</option>

                    @foreach(($data['colors'] ?? collect()) as $color)
                        <option
                            value="{{ $color->MaMau }}"
                            {{ request('color') == $color->MaMau ? 'selected' : '' }}
                        >
                            {{ $color->TenMau }}
                        </option>
                    @endforeach
                </select>

                {{-- Lọc danh mục --}}
                <select
                    name="category"
                    class="filter-select border-stone-300 focus:border-red-800 focus:ring-0 rounded-lg text-sm min-w-[150px] cursor-pointer bg-white"
                >
                    <option value="">Tất cả danh mục</option>

                    @foreach(($data['categories'] ?? collect()) as $cat)
                        <option
                            value="{{ $cat->MaDanhMuc }}"
                            {{ request('category') == $cat->MaDanhMuc ? 'selected' : '' }}
                        >
                            {{ $cat->TenDanhMuc }}
                        </option>
                    @endforeach
                </select>

                {{-- Sắp xếp --}}
                <select
                    name="sort"
                    class="filter-select border-stone-300 focus:border-red-800 focus:ring-0 rounded-lg text-sm cursor-pointer bg-white"
                >
                    <option value="">Sắp xếp</option>

                    <option
                        value="price_asc"
                        {{ request('sort') == 'price_asc' ? 'selected' : '' }}
                    >
                        Giá: Thấp đến Cao
                    </option>

                    <option
                        value="price_desc"
                        {{ request('sort') == 'price_desc' ? 'selected' : '' }}
                    >
                        Giá: Cao đến Thấp
                    </option>

                    <option
                        value="newest"
                        {{ request('sort') == 'newest' ? 'selected' : '' }}
                    >
                        Mới nhất
                    </option>
                </select>
            </div>
        </form>
    </div>
</section>


{{-- ==================== TẤT CẢ SẢN PHẨM ==================== --}}
<div id="product-data-container">
    <section class="max-w-7xl mx-auto py-16 px-4">

        <div class="text-center mb-12">
            <h2 class="serif text-3xl font-bold text-stone-800 uppercase tracking-widest">
                Tất Cả Mẫu Thiết Kế
            </h2>

            <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            @forelse(($data['product'] ?? collect()) as $s)

                <div class="group bg-white overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-stone-100 flex flex-col h-full">

                    {{-- Hình ảnh sản phẩm --}}
                    <div class="relative h-96 overflow-hidden bg-stone-100">
                    @php
                        $anh = $s->hinhanhsanpham->first();
                        $tenAnh = $anh->LinkAnh ?? $s->HinhAnh ?? null;
                    @endphp

                    @if ($tenAnh)
                        <img
                            src="{{ asset('img/products/' . basename($tenAnh)) }}"
                            alt="{{ $s->TenSanPham }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                            loading="lazy"
                        >
                    @else
                        <div class="flex h-full items-center justify-center text-stone-400">
                            Chưa có hình ảnh sản phẩm
                        </div>
                    @endif
                </div>

                    {{-- Thông tin sản phẩm --}}
                    <div class="p-6 text-center flex-grow flex flex-col justify-between">

                        <div>
                            <h3 class="serif text-md font-semibold text-stone-700 uppercase tracking-tight mb-1">
                                {{ $s->TenSanPham }}
                            </h3>

                            <p class="text-[10px] text-stone-400 uppercase tracking-[0.1em] mb-3 italic">
                                {{ $s->chatlieu->TenChatLieu ?? 'Vải lụa truyền thống' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-red-800 font-bold text-lg mb-4">
                                {{ number_format((float) ($s->chiTietSanPham->first()?->Gia ?? 0), 0, ',', '.') }}đ
                            </p>

                            <div class="pt-4 border-t border-stone-50">
                                <a
                                    href="{{ route('product.detail', $s->MaSanPham) }}"
                                    class="inline-block border-b border-stone-800 text-[10px] hover:text-red-700 hover:border-red-700 transition-all uppercase tracking-[0.2em] pb-1 font-bold"
                                >
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            @empty

                <div class="col-span-full text-center py-20 text-stone-500 italic">
                    Không tìm thấy mẫu thiết kế nào phù hợp với yêu cầu của bạn.
                </div>

            @endforelse

        </div>

        <div class="mt-10 text-center">
            <a
                href="{{ route('products.index') }}"
                class="inline-block border border-stone-800 px-8 py-3 text-sm uppercase tracking-widest hover:bg-stone-800 hover:text-white transition-all duration-300 rounded-full font-bold"
            >
                Xem tất cả
            </a>
        </div>

    </section>
</div>


{{-- ==================== SẢN PHẨM MỚI ==================== --}}
<section class="max-w-7xl mx-auto py-16 px-4">

    <div class="text-center mb-12">
        <h2 class="serif text-3xl font-bold text-stone-800 uppercase tracking-widest">
            Sản Phẩm Mới
        </h2>

        <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        @forelse(($data['newProducts'] ?? collect()) as $s)

            <div class="group bg-white overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-stone-100 flex flex-col h-full">

                <div class="relative h-96 overflow-hidden bg-stone-100">

                    @if(!empty($s->HinhAnh))
                        <img
                            src="{{ asset('img/products/' . $s->HinhAnh) }}"
                            alt="{{ $s->TenSanPham }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                            loading="lazy"
                            onerror="this.onerror=null; this.src='{{ asset('img/no-image.png') }}';"
                        >
                    @else
                        <img
                            src="{{ asset('img/no-image.png') }}"
                            alt="Chưa có hình ảnh sản phẩm"
                            class="w-full h-full object-cover"
                        >
                    @endif

                </div>

                <div class="p-6 text-center flex-grow flex flex-col justify-between">

                    <div>
                        <h3 class="serif text-md font-semibold text-stone-700 uppercase tracking-tight mb-1">
                            {{ $s->TenSanPham }}
                        </h3>

                        <p class="text-[10px] text-stone-400 uppercase tracking-[0.1em] mb-3 italic">
                            {{ $s->chatlieu->TenChatLieu ?? 'Vải lụa truyền thống' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-red-800 font-bold text-lg mb-4">
                            {{ number_format((float) ($s->chiTietSanPham->first()?->Gia ?? 0), 0, ',', '.') }}đ
                        </p>

                        <div class="pt-4 border-t border-stone-50">
                            <a
                                href="{{ route('product.detail', $s->MaSanPham) }}"
                                class="inline-block border-b border-stone-800 text-[10px] hover:text-red-700 hover:border-red-700 transition-all uppercase tracking-[0.2em] pb-1 font-bold"
                            >
                                Xem chi tiết
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        @empty

            <div class="col-span-full text-center py-20 text-stone-500 italic">
                Không có sản phẩm mới.
            </div>

        @endforelse

    </div>

    <div class="mt-10 text-center">
        <a
            href="{{ route('products.index') }}"
            class="inline-block border border-stone-800 px-8 py-3 text-sm uppercase tracking-widest hover:bg-stone-800 hover:text-white transition-all duration-300 rounded-full font-bold"
        >
            Xem tất cả
        </a>
    </div>

</section>


{{-- ==================== KHÁM PHÁ DANH MỤC ==================== --}}
<section class="py-16 bg-stone-50 border-t border-stone-200">

    <div class="max-w-7xl mx-auto px-4 text-center">

        <div class="mb-12">
            <h2 class="serif text-3xl font-bold text-stone-800 uppercase tracking-widest">
                Khám phá danh mục
            </h2>

            <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

            @foreach(($data['categories'] ?? collect())->take(4) as $cat)

                <a href="{{ route('category.show', ['id' => $cat->MaDanhMuc]) }}">

                    <div class="relative h-48 group overflow-hidden rounded-xl shadow-md bg-stone-400 flex items-center justify-center">

                        <div class="absolute inset-0 bg-stone-900/40 group-hover:bg-red-900/60 transition-colors duration-500"></div>

                        <span class="relative text-white font-bold uppercase tracking-[0.15em] text-sm px-4 text-center">
                            {{ $cat->TenDanhMuc }}
                        </span>

                    </div>

                </a>

            @endforeach

        </div>

        <div class="mt-10">
            <a
                href="{{ route('products.category') }}"
                class="inline-block border border-stone-800 px-8 py-3 text-sm uppercase tracking-widest hover:bg-stone-800 hover:text-white transition-all duration-300 rounded-full font-bold"
            >
                Xem tất cả
            </a>
        </div>

    </div>

</section>


{{-- ==================== AJAX TÌM KIẾM VÀ LỌC ==================== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        function fetchProducts(url) {

            const params = $('#filter-form').serialize();

            $.ajax({
                url: url,
                type: 'GET',
                data: params,

                beforeSend: function () {
                    $('#product-data-container').css('opacity', '0.5');
                },

                success: function (response) {

                    const newContent = $(response)
                        .find('#product-data-container')
                        .html();

                    if (newContent) {
                        $('#product-data-container')
                            .html(newContent)
                            .css('opacity', '1');
                    } else {
                        console.error('Không tìm thấy vùng chứa sản phẩm.');

                        $('#product-data-container').css('opacity', '1');
                    }

                    const queryString = params ? '?' + params : '';

                    window.history.pushState(
                        {},
                        '',
                        "{{ route('home') }}" + queryString
                    );
                },

                error: function (xhr) {

                    console.error('Lỗi tải sản phẩm:', xhr.responseText);

                    alert('Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại.');

                    $('#product-data-container').css('opacity', '1');
                }
            });
        }

        // Lọc màu sắc, danh mục và sắp xếp.
        $('.filter-select').on('change', function () {
            fetchProducts("{{ route('home') }}");
        });

        // Tìm kiếm với độ trễ 500ms.
        let timer;

        $('input[name="search"]').on('input', function () {

            clearTimeout(timer);

            timer = setTimeout(function () {
                fetchProducts("{{ route('home') }}");
            }, 500);
        });

        // Xử lý phân trang AJAX nếu có.
        $(document).on('click', '.ajax-pagination a', function (e) {

            e.preventDefault();

            const url = $(this).attr('href');

            if (url) {
                fetchProducts(url);
            }
        });

        // Ngăn tải lại trang khi nhấn Enter.
        $('#filter-form').on('submit', function (e) {

            e.preventDefault();

            fetchProducts("{{ route('home') }}");
        });

    });
</script>

@endsection
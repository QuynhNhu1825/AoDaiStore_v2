@extends('layouts.client')

@section('title', 'Danh sách sản phẩm')

@section('content')

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        {{-- TIÊU ĐỀ --}}
        <div class="text-center mb-12">
            <h1 class="serif text-4xl font-bold text-stone-800 italic">
                Danh sách sản phẩm
            </h1>

            <p class="text-stone-500 uppercase tracking-widest text-xs mt-2">
                Tinh hoa di sản Việt
            </p>
        </div>

        {{-- DANH SÁCH SẢN PHẨM --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            @forelse($products as $s)

                @php
                    // Lấy các biến thể còn hoạt động
                    $variants = $s->chiTietSanPham
                        ->where('IsDeleted', 0)
                        ->values();

                    // Lấy giá thấp nhất và cao nhất
                    $minPrice = $variants->min('Gia');
                    $maxPrice = $variants->max('Gia');

                    // Đường dẫn ảnh đại diện
                    $imageName = $s->HinhAnh
                        ? basename($s->HinhAnh)
                        : null;
                @endphp

                <div class="group bg-white overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-stone-100 flex flex-col">

                    {{-- HÌNH ẢNH SẢN PHẨM --}}
                    <div class="relative h-96 overflow-hidden bg-stone-100">

                        @if($imageName)
                            <img
                                src="{{ asset('img/products/' . $imageName) }}"
                                alt="{{ $s->TenSanPham }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='https://placehold.co/400x600?text=No+Image';"
                            >
                        @else
                            <img
                                src="https://placehold.co/400x600?text=No+Image"
                                alt="Chưa có hình ảnh"
                                class="w-full h-full object-cover"
                            >
                        @endif

                    </div>

                    {{-- THÔNG TIN SẢN PHẨM --}}
                    <div class="p-6 text-center flex flex-col flex-grow">

                        {{-- TÊN SẢN PHẨM --}}
                        <h3 class="serif text-md font-semibold text-stone-700 uppercase mb-2">
                            {{ $s->TenSanPham }}
                        </h3>

                        {{-- CHẤT LIỆU --}}
                        <p class="text-[10px] text-stone-400 uppercase tracking-[0.1em] mb-3 italic">
                            {{ $s->chatlieu->TenChatLieu ?? 'Vải lụa truyền thống' }}
                        </p>

                        {{-- GIÁ SẢN PHẨM --}}
                        <p class="text-red-800 font-bold text-lg mb-4">

                            @if($minPrice !== null && $maxPrice !== null)

                                @if((float)$minPrice == (float)$maxPrice)
                                    {{ number_format($minPrice, 0, ',', '.') }}đ
                                @else
                                    {{ number_format($minPrice, 0, ',', '.') }}
                                    -
                                    {{ number_format($maxPrice, 0, ',', '.') }}đ
                                @endif

                            @else
                                <span class="text-sm text-stone-400 font-normal">
                                    Liên hệ
                                </span>
                            @endif

                        </p>

                        {{-- NÚT XEM CHI TIẾT --}}
                        <div class="mt-auto">
                            <a
                                href="{{ route('product.detail', $s->MaSanPham) }}"
                                class="inline-block border-b border-stone-800 text-[10px]
                                       uppercase font-bold tracking-widest pb-1
                                       hover:text-red-700 hover:border-red-700 transition"
                            >
                                Xem chi tiết
                            </a>
                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-20 text-stone-400 italic">
                    Không tìm thấy sản phẩm nào.
                </div>

            @endforelse

        </div>

        {{-- PHÂN TRANG --}}
        <div class="mt-16 flex justify-center">
            {{ $products->appends(request()->all())->links() }}
        </div>

    </div>
</section>

@endsection

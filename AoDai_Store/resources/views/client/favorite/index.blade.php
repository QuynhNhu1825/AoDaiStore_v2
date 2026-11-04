@extends('layouts.client')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- TIÊU ĐỀ DANH SÁCH YÊU THÍCH --}}
<div class="text-left mb-10">

    <h2 class="serif text-3xl font-bold text-stone-800 uppercase tracking-widest">
                Sản Phẩm Yêu Thích
    </h2>
    <p class="text-sm text-stone-500 mt-3">
        Những thiết kế bạn yêu thích và muốn lưu giữ
    </p>

</div>

    @if($favoriteProducts->isEmpty())

        <div class="text-center py-16">
            <p class="text-gray-500 italic mb-4">
                Bạn chưa yêu thích sản phẩm nào.
            </p>

            <a href="{{ route('home') }}"
               class="inline-block bg-stone-900 text-white px-6 py-2 hover:bg-amber-700 transition">
                Tiếp tục mua sắm
            </a>
        </div>

    @else

        {{-- DANH SÁCH SẢN PHẨM --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

            @foreach($favoriteProducts as $favorite)

                @php
                    $product = $favorite->product;
                    $price = $product?->chiTietSanPham
                        ->where('IsDeleted', 0)
                        ->first()?->Gia;
                @endphp

                @if($product)
                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-md transition relative">

                        {{-- ẢNH SẢN PHẨM --}}
                        <a href="{{ route('product.detail', $product->MaSanPham) }}">
                            <img
                                src="{{ asset('img/products/' . $product->HinhAnh) }}"
                                alt="{{ $product->TenSanPham }}"
                                class="w-full h-36 sm:h-40 object-cover"
                            >
                        </a>

                        {{-- THÔNG TIN --}}
                        <div class="p-3">

                            <a href="{{ route('product.detail', $product->MaSanPham) }}"
                               class="block text-sm font-semibold text-stone-800 hover:text-amber-700 line-clamp-2 min-h-10">
                                {{ $product->TenSanPham }}
                            </a>

                            <p class="text-red-700 font-bold text-sm mt-2">
                                {{ number_format($price ?? 0, 0, ',', '.') }} đ
                            </p>

                            {{-- NÚT XÓA --}}
                            <form action="{{ route('favorite.destroy', $favorite->MaYeuThich) }}"
                                  method="POST"
                                  class="mt-3"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="w-full border border-red-300 text-red-600 text-xs py-2 rounded hover:bg-red-50 transition">
                                    Xóa yêu thích
                                </button>
                            </form>

                        </div>
                    </div>
                @endif

            @endforeach

        </div>

        {{-- PHÂN TRANG --}}
        <div class="mt-8">
            {{ $favoriteProducts->links() }}
        </div>

    @endif

</div>
@endsection
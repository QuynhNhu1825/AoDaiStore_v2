@extends('layouts.client')

@section('title', 'Sản phẩm đã mua')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-2xl font-bold mb-6">
        Sản phẩm đã mua & đánh giá
    </h1>

    @if($products->isEmpty())
        <p class="text-gray-500 italic">
            Bạn chưa mua sản phẩm nào.
        </p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($products as $item)
                <div class="border rounded-lg p-3">

                    <a href="{{ route('product.detail', $item->MaSanPham) }}">
                        <img src="{{ asset('img/products/' . $item->HinhAnh) }}"
                             class="w-full h-48 object-cover mb-3">
                    </a>

                    <h3 class="font-semibold text-sm line-clamp-2">
                        {{ $item->TenSanPham }}
                    </h3>

                    <p class="text-red-600 font-bold mt-1">
                        {{ number_format($item->GiaBan, 0, ',', '.') }} đ
                    </p>

                    {{-- Trạng thái đánh giá --}}
                    @if($item->SoSao)
                        <span class="inline-block mt-2 text-xs 
                                     bg-green-100 text-green-700 
                                     px-2 py-1 rounded">
                            Đã đánh giá ⭐ {{ $item->SoSao }}/5
                        </span>
                    @else
                        <span class="inline-block mt-2 text-xs 
                                     bg-yellow-100 text-yellow-700 
                                     px-2 py-1 rounded">
                            Chưa đánh giá
                        </span>

                        <a href="{{ route('review.create', $item->MaSanPham) }}"
                           class="block mt-2 text-center 
                                  text-sm bg-blue-500 
                                  text-white py-1 rounded 
                                  hover:bg-blue-600">
                            Đánh giá ngay
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection

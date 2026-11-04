@extends('layouts.client')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-2xl font-bold mb-6">Sản phẩm yêu thích</h1>

    @if($favoriteProducts->isEmpty())
        <p class="text-gray-500 italic">Bạn chưa yêu thích sản phẩm nào.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($favoriteProducts as $item)
                <a href="{{ route('product.detail', $item->MaSanPham) }}"
                   class="border rounded-lg p-3 hover:shadow transition">

                    <img src="{{ asset('img/products/' . $item->HinhAnh) }}"
                         class="w-full h-48 object-cover mb-3">

                    <h3 class="font-semibold text-sm line-clamp-2">
                        {{ $item->TenSanPham }}
                    </h3>

                    <p class="text-red-600 font-bold mt-1">
                        {{ number_format($item->GiaBan, 0, ',', '.') }} đ
                    </p>
                </a>
            @endforeach
        </div>
        <div class="mt-6">
    {{ $favoriteProducts->links() }}
</div>
    @endif

</div>
@endsection

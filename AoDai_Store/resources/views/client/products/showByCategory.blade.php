@extends('layouts.client')

@section('title', 'Danh mục: ' . $category->TenLoaiSP)

@section('content')
    <section class="bg-stone-100 py-12 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="serif text-4xl font-bold text-stone-800 uppercase tracking-widest">
                {{ $category->TenLoaiSP }}
            </h1>
            <p class="text-stone-500 mt-2 italic">{{ $category->MoTa }}</p>
        </div>
    </section>

    {{-- Danh sách sản phẩm --}}
    <section class="max-w-7xl mx-auto py-16 px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $s)
                <div class="group bg-white overflow-hidden shadow-sm border border-stone-100 flex flex-col h-full">
                    <div class="relative h-80 overflow-hidden">
                        <img src="{{ asset('img/products/' . $s->HinhAnh) }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                             onerror="this.src='https://placehold.co/400x600?text=No+Image'">
                    </div>
                    <div class="p-6 text-center flex-grow">
                        <h3 class="serif text-md font-semibold text-stone-700 uppercase truncate">{{ $s->TenSanPham }}</h3>
                        <p class="text-red-800 font-bold mt-2">{{ number_format($s->GiaBan, 0, ',', '.') }}đ</p>
                        <a href="{{ route('product.detail', $s->MaSanPham) }}" 
                           class="inline-block mt-4 border-b border-stone-800 text-[10px] uppercase font-bold pb-1">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 text-stone-400 italic">
                    Chưa có sản phẩm nào trong danh mục này.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </section>
@endsection
@extends('layouts.client')

@section('title', 'Danh mục sản phẩm')

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        
        <div class="text-center mb-12">
            <h1 class="serif text-4xl font-bold text-stone-800 italic">Danh mục sản phẩm</h1>
            <p class="text-stone-500 uppercase tracking-widest text-xs mt-2">Tinh hoa di sản Việt</p>
        </div>

        <div class="flex flex-wrap justify-center gap-4 mb-16">
            <a href="{{ route('products.category') }}" 
               class="px-6 py-2 rounded-full border {{ !request('category') ? 'bg-red-900 text-white border-red-900' : 'border-stone-200 text-stone-600' }} hover:border-red-900 transition text-sm font-bold uppercase tracking-wider">
               Tất cả
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('products.category', ['category' => $cat->MaLoaiSP]) }}" 
                   class="px-6 py-2 rounded-full border {{ request('category') == $cat->MaLoaiSP ? 'bg-red-900 text-white border-red-900' : 'border-stone-200 text-stone-600' }} hover:border-red-900 transition text-sm font-bold uppercase tracking-wider">
                    {{ $cat->TenLoaiSP }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $s)
                <div class="group bg-white overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-stone-100 flex flex-col">
                    <div class="relative h-96 overflow-hidden">
                        <img src="{{ asset('img/products/' . $s->HinhAnh) }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500" 
                             onerror="this.src='https://placehold.co/400x600?text=No+Image'" alt="{{ $s->TenSanPham }}">
                    </div>

                    <div class="p-6 text-center">
                        <h3 class="serif text-md font-semibold text-stone-700 uppercase mb-2">{{ $s->TenSanPham }}</h3>
                        <p class="text-[10px] text-stone-400 uppercase tracking-[0.1em] mb-3 italic">
                                {{ $s->chatlieu->TenChatLieu ?? 'Vải lụa truyền thống' }}
                            </p>
                        <p class="text-red-800 font-bold text-lg mb-4">{{ number_format($s->GiaBan, 0, ',', '.') }}đ</p>
                        <a href="{{ route('product.detail', $s->MaSanPham) }}" class="inline-block border-b border-stone-800 text-[10px] uppercase font-bold tracking-widest pb-1 hover:text-red-700 hover:border-red-700 transition">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 text-stone-400 italic">
                    Không tìm thấy sản phẩm nào trong danh mục này.
                </div>
            @endforelse
        </div>

        <div class="mt-16 flex justify-center">
            {{ $products->appends(request()->all())->links() }}
        </div>
    </div>
</section>
@endsection
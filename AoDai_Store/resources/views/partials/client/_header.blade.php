<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-20">

{{-- LOGO VÀ TÊN THƯƠNG HIỆU --}}
<a href="{{ url('/') }}"
   class="inline-flex items-center gap-3 group">

    {{-- LOGO --}}
    <img
        src="{{ asset('img/logo/logo.jpg') }}"
        alt="Logo Áo Dài Heritage"
        class="w-12 h-12 object-contain rounded-lg
               flex-shrink-0"
    >

    {{-- TÊN THƯƠNG HIỆU --}}
    <span class="serif text-xl md:text-2xl
                 font-bold text-red-900
                 tracking-wide leading-none
                 whitespace-nowrap
                 group-hover:text-red-700
                 transition-colors">
        ÁO DÀI HERITAGE
    </span>

</a>

        
        <div class="hidden md:flex space-x-8 font-medium text-stone-600 items-center">
            <a href="/" class="hover:text-red-700 transition">Trang chủ</a>

            <div class="group relative py-2"> 
                <a href="#" class="hover:text-red-700 transition flex items-center">
                    Bộ sưu tập 
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                
                <div class="absolute hidden group-hover:block left-1/2 -translate-x-1/2 top-full w-64 pt-2 z-50">
                    <div class="bg-white shadow-xl border border-stone-100 py-2 rounded-lg transition-all">
                        @foreach($categories as $cat)
                            <a href="{{ route('category.show', $cat->MaDanhMuc) }}" 
                               class="block px-4 py-3 text-sm text-stone-600 hover:bg-red-50 hover:text-red-800 transition-colors border-b border-stone-50 last:border-0">
                                {{ $cat->TenDanhMuc }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="{{ route('pages.introduction') }}" class="hover:text-red-700 transition">Giới thiệu</a>
        </div>

        <div class="flex items-center space-x-6">
            <div class="relative group py-2">
                <div class="cursor-pointer text-stone-600 hover:text-red-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>

                <div class="absolute hidden group-hover:block right-0 top-full w-48 pt-2 z-50">
                    <div class="bg-white border rounded-lg shadow-xl py-2">
                        @guest
                            <a href="javascript:void(0)" 
                               onclick="openLoginModal()" 
                               class="block px-4 py-2 text-sm text-stone-700 hover:bg-red-50">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" 
                               class="block px-4 py-2 text-sm text-stone-700 hover:bg-red-50">
                                Đăng ký
                            </a>
                        @endguest

                       @auth
                    <div class="px-4 py-2 text-xs text-stone-400 uppercase tracking-widest font-bold">
                        Xin chào, {{ Auth::user()->HoTen }}
                    </div>

                    <a href="/profile" class="block px-4 py-2 text-sm text-stone-700 hover:bg-red-50">
                        Thông tin tài khoản
                    </a>

                    <a href="{{ route('favorite.index') }}" 
                       class="block px-4 py-2 text-sm text-stone-700 hover:bg-red-50">
                        Sản phẩm yêu thích
                    </a>

                    <a href="{{ route('order.history') }}" 
                       class="block px-4 py-2 text-sm text-stone-700 hover:bg-red-50">
                        Đơn hàng của tôi
                    </a>

                    <hr class="my-1 border-stone-100">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left px-4 py-2 text-sm text-stone-700 hover:bg-red-50">
                            Đăng xuất
                        </button>
                    </form>
                @endauth


                    </div>
                </div>
            </div>

            <a href="/cart" class="relative text-stone-600 hover:text-red-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>

                @php
                  $cartQuantity = Auth::check()
                  ? (int) \App\Models\Cart::where('MaTaiKhoan', Auth::id())->sum('SoLuong')
                  : 0;
                @endphp

                <span
                    id="header-cart-count"
                    class="absolute -top-2 -right-2 bg-red-600 text-white
                    text-[10px] font-bold rounded-full
                    min-w-4 h-4 px-1 flex items-center justify-center"
                >
                    {{ $cartQuantity }}
                </span>

            </a>
        </div>
    </div>
</nav>
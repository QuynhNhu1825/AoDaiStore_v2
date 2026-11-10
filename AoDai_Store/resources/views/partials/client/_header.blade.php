<nav class="bg-white shadow-sm sticky top-0 z-50">

    {{-- ================= HEADER DESKTOP + MOBILE ================= --}}
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center h-20">

            {{-- LOGO --}}
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 sm:gap-3 group min-w-0">

                <img
                    src="{{ asset('img/logo/logo.jpg') }}"
                    alt="Logo Áo Dài Heritage"
                    class="w-10 h-10 sm:w-12 sm:h-12 object-contain rounded-lg flex-shrink-0"
                >

                <span class="serif text-base sm:text-xl md:text-2xl
                             font-bold text-red-900 tracking-wide
                             leading-tight whitespace-nowrap
                             group-hover:text-red-700 transition-colors">
                    ÁO DÀI HERITAGE
                </span>
            </a>


            {{-- ================= MENU DESKTOP ================= --}}
            <div class="hidden md:flex space-x-6 lg:space-x-8
                        font-medium text-stone-600 items-center">

                <a href="/"
                   class="hover:text-red-700 transition whitespace-nowrap">
                    Trang chủ
                </a>

                {{-- BỘ SƯU TẬP --}}
                <div class="group relative py-2">

                    <a href="#"
                       class="hover:text-red-700 transition flex items-center">
                        Bộ sưu tập

                        <svg class="w-4 h-4 ml-1" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>

                    <div class="absolute hidden group-hover:block
                                left-1/2 -translate-x-1/2 top-full
                                w-64 pt-2 z-50">

                        <div class="bg-white shadow-xl border border-stone-100
                                    py-2 rounded-lg">

                            @foreach($categories as $cat)
                                <a href="{{ route('category.show', $cat->MaDanhMuc) }}"
                                   class="block px-4 py-3 text-sm text-stone-600
                                          hover:bg-red-50 hover:text-red-800
                                          transition-colors border-b
                                          border-stone-50 last:border-0">
                                    {{ $cat->TenDanhMuc }}
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>

                <a href="{{ route('pages.introduction') }}"
                   class="hover:text-red-700 transition whitespace-nowrap">
                    Giới thiệu
                </a>
            </div>


            {{-- ================= CÁC ICON BÊN PHẢI ================= --}}
            <div class="flex items-center gap-3 sm:gap-5">

                {{-- TÀI KHOẢN DESKTOP --}}
                <div class="relative group py-2 hidden md:block">

                    <div class="cursor-pointer text-stone-600
                                hover:text-red-700 transition">
                        <svg class="w-6 h-6" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                     M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>

                    <div class="absolute hidden group-hover:block
                                right-0 top-full w-52 pt-2 z-50">

                        <div class="bg-white border border-stone-100
                                    rounded-lg shadow-xl py-2">

                            @guest
                                <a href="javascript:void(0)"
                                   onclick="openLoginModal()"
                                   class="block px-4 py-2 text-sm
                                          text-stone-700 hover:bg-red-50">
                                    Đăng nhập
                                </a>

                                <a href="{{ route('register') }}"
                                   class="block px-4 py-2 text-sm
                                          text-stone-700 hover:bg-red-50">
                                    Đăng ký
                                </a>
                            @endguest

                            @auth
                                <div class="px-4 py-2 text-xs
                                            text-stone-400 uppercase
                                            tracking-widest font-bold">
                                    Xin chào, {{ Auth::user()->HoTen }}
                                </div>

                                <a href="/profile"
                                   class="block px-4 py-2 text-sm
                                          text-stone-700 hover:bg-red-50">
                                    Thông tin tài khoản
                                </a>

                                <a href="{{ route('favorite.index') }}"
                                   class="block px-4 py-2 text-sm
                                          text-stone-700 hover:bg-red-50">
                                    Sản phẩm yêu thích
                                </a>

                                <a href="{{ route('order.history') }}"
                                   class="block px-4 py-2 text-sm
                                          text-stone-700 hover:bg-red-50">
                                    Đơn hàng của tôi
                                </a>

                                <hr class="my-1 border-stone-100">

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit"
                                            class="w-full text-left px-4 py-2
                                                   text-sm text-stone-700
                                                   hover:bg-red-50">
                                        Đăng xuất
                                    </button>
                                </form>
                            @endauth

                        </div>
                    </div>
                </div>


                {{-- GIỎ HÀNG --}}
                <a href="/cart"
                   class="relative text-stone-600 hover:text-red-700 transition">

                    <svg class="w-6 h-6" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4
                                 M5 9h14l1 12H4L5 9z"/>
                    </svg>

                    @php
                        $cartQuantity = Auth::check()
                            ? (int) \App\Models\Cart::where(
                                'MaTaiKhoan',
                                Auth::id()
                            )->sum('SoLuong')
                            : 0;
                    @endphp

                    <span
                        id="header-cart-count"
                        class="absolute -top-2 -right-2 bg-red-600
                               text-white text-[10px] font-bold
                               rounded-full min-w-4 h-4 px-1
                               flex items-center justify-center"
                    >
                        {{ $cartQuantity }}
                    </span>
                </a>


                {{-- NÚT MENU MOBILE --}}
                <button
                    type="button"
                    id="mobileMenuButton"
                    aria-label="Mở menu"
                    aria-expanded="false"
                    aria-controls="mobileMenu"
                    class="md:hidden w-9 h-9 flex items-center
                           justify-center text-stone-700
                           hover:text-red-800 transition"
                >
                    <svg id="menuIcon"
                         class="w-7 h-7"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

            </div>
        </div>
    </div>


    {{-- ================= MENU MOBILE ================= --}}
    <div
        id="mobileMenu"
        class="hidden md:hidden bg-white border-t border-stone-100
               shadow-lg"
    >

        <div class="max-w-7xl mx-auto px-5 py-4
                    flex flex-col text-stone-700">

            {{-- TRANG CHỦ --}}
            <a href="/"
               class="py-3 border-b border-stone-100
                      hover:text-red-800 transition">
                <i class="fas fa-home w-6 text-red-800"></i>
                Trang chủ
            </a>


            {{-- BỘ SƯU TẬP --}}
            <div class="border-b border-stone-100">

                <button
                    type="button"
                    id="mobileCategoryButton"
                    aria-expanded="false"
                    aria-controls="mobileCategoryList"
                    class="w-full flex items-center justify-between
                           py-3 hover:text-red-800 transition"
                >
                    <span>
                        <i class="fas fa-layer-group w-6 text-red-800"></i>
                        Bộ sưu tập
                    </span>

                    <i id="categoryArrow"
                       class="fas fa-chevron-down text-xs transition-transform">
                    </i>
                </button>

                <div id="mobileCategoryList"
                     class="hidden pb-2 pl-8">

                    @foreach($categories as $cat)
                        <a href="{{ route('category.show', $cat->MaDanhMuc) }}"
                           class="block py-2 text-sm text-stone-500
                                  hover:text-red-800 transition">
                            {{ $cat->TenDanhMuc }}
                        </a>
                    @endforeach

                </div>
            </div>


            {{-- GIỚI THIỆU --}}
            <a href="{{ route('pages.introduction') }}"
               class="py-3 border-b border-stone-100
                      hover:text-red-800 transition">
                <i class="fas fa-info-circle w-6 text-red-800"></i>
                Giới thiệu
            </a>


            {{-- TÀI KHOẢN --}}
            <div class="border-b border-stone-100">

                <button
                    type="button"
                    id="mobileAccountButton"
                    aria-expanded="false"
                    aria-controls="mobileAccountList"
                    class="w-full flex items-center justify-between
                           py-3 hover:text-red-800 transition"
                >
                    <span>
                        <i class="fas fa-user w-6 text-red-800"></i>
                        Tài khoản
                    </span>

                    <i id="accountArrow"
                       class="fas fa-chevron-down text-xs transition-transform">
                    </i>
                </button>

                <div id="mobileAccountList"
                     class="hidden pb-2 pl-8">

                    @guest
                        <a href="javascript:void(0)"
                           onclick="openLoginModal(); closeMobileMenu();"
                           class="block py-2 text-sm text-stone-500
                                  hover:text-red-800">
                            Đăng nhập
                        </a>

                        <a href="{{ route('register') }}"
                           class="block py-2 text-sm text-stone-500
                                  hover:text-red-800">
                            Đăng ký
                        </a>
                    @endguest

                    @auth
                        <p class="py-2 text-xs text-stone-400">
                            Xin chào, {{ Auth::user()->HoTen }}
                        </p>

                        <a href="/profile"
                           class="block py-2 text-sm text-stone-500
                                  hover:text-red-800">
                            Thông tin tài khoản
                        </a>

                        <a href="{{ route('favorite.index') }}"
                           class="block py-2 text-sm text-stone-500
                                  hover:text-red-800">
                            Sản phẩm yêu thích
                        </a>

                        <a href="{{ route('order.history') }}"
                           class="block py-2 text-sm text-stone-500
                                  hover:text-red-800">
                            Đơn hàng của tôi
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="block w-full text-left py-2
                                           text-sm text-red-700">
                                Đăng xuất
                            </button>
                        </form>
                    @endauth

                </div>
            </div>

        </div>
    </div>

</nav>


{{-- ================= JAVASCRIPT MENU MOBILE ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const menuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');

    const categoryButton = document.getElementById('mobileCategoryButton');
    const categoryList = document.getElementById('mobileCategoryList');
    const categoryArrow = document.getElementById('categoryArrow');

    const accountButton = document.getElementById('mobileAccountButton');
    const accountList = document.getElementById('mobileAccountList');
    const accountArrow = document.getElementById('accountArrow');

    function toggleSubmenu(button, list, arrow) {
        const isOpen = !list.classList.contains('hidden');

        list.classList.toggle('hidden', isOpen);
        button.setAttribute('aria-expanded', String(!isOpen));

        if (arrow) {
            arrow.classList.toggle('rotate-180', !isOpen);
        }
    }

    function closeMobileMenu() {
        mobileMenu.classList.add('hidden');
        menuButton.setAttribute('aria-expanded', 'false');

        menuIcon.innerHTML =
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';

        categoryList.classList.add('hidden');
        accountList.classList.add('hidden');

        categoryButton.setAttribute('aria-expanded', 'false');
        accountButton.setAttribute('aria-expanded', 'false');

        categoryArrow.classList.remove('rotate-180');
        accountArrow.classList.remove('rotate-180');
    }

    menuButton.addEventListener('click', function () {

        const isOpen = !mobileMenu.classList.contains('hidden');

        if (isOpen) {
            closeMobileMenu();
        } else {
            mobileMenu.classList.remove('hidden');
            menuButton.setAttribute('aria-expanded', 'true');

            menuIcon.innerHTML =
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
        }
    });

    categoryButton.addEventListener('click', function () {
        toggleSubmenu(categoryButton, categoryList, categoryArrow);
    });

    accountButton.addEventListener('click', function () {
        toggleSubmenu(accountButton, accountList, accountArrow);
    });

    // Đóng menu khi chọn liên kết
    mobileMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMobileMenu);
    });

    // Đóng menu khi chuyển sang desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            closeMobileMenu();
        }
    });

    window.closeMobileMenu = closeMobileMenu;

});
</script>

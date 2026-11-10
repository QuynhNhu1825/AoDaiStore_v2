
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị') - AD Heritage</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --primary: #831c30;
            --primary-dark: #350d16;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f6f7f9;
        }

        /* SIDEBAR */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--primary-dark);
            transition: transform .25s ease, width .25s ease;
        }

        /* LOGO */
        .sidebar-logo {
            height: 64px;
            min-height: 64px;
        }

        /* MENU */
        .sidebar-nav {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 6px 0;
            scrollbar-width: thin;
            scrollbar-color: #80505a transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #80505a;
            border-radius: 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 13px;
            margin: 2px 10px;
            min-height: 38px;
            border-radius: 8px;
            border-left: 3px solid transparent;
            color: #e7cdd3;
            font-size: 12.5px;
            font-weight: 500;
            white-space: nowrap;
            transition: all .2s ease;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            background: #59202d;
            color: white;
        }

        .sidebar-link.active {
            background: var(--primary);
            color: white;
            border-left-color: #f4c77d;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(0,0,0,.12);
        }

        /* NHÓM NGHIỆP VỤ */
        .sidebar-group {
            margin-bottom: 3px;
        }

        .sidebar-group-title {
            padding: 0 23px;
            margin: 10px 0 5px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #c99ba5;
            white-space: nowrap;
        }

        .sidebar-divider {
            margin: 7px 20px;
            border-top: 1px solid rgba(255,255,255,.10);
        }

        /* MAIN */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-width: 0;
            transition: margin-left .25s ease;
        }

        /* THU GỌN SIDEBAR */
        @media (min-width: 1024px) {
            body.sidebar-collapsed .admin-sidebar {
                width: 76px;
            }

            body.sidebar-collapsed .admin-main {
                margin-left: 76px;
            }

            body.sidebar-collapsed .sidebar-text,
            body.sidebar-collapsed .sidebar-group-title,
            body.sidebar-collapsed .sidebar-footer-text,
            body.sidebar-collapsed .sidebar-logo-text,
            body.sidebar-collapsed .sidebar-divider {
                display: none;
            }

            body.sidebar-collapsed .sidebar-link {
                justify-content: center;
                padding: 11px 0;
                margin: 3px 10px;
            }

            body.sidebar-collapsed .sidebar-link i {
                width: auto;
            }

            body.sidebar-collapsed #collapsedLogo {
                display: block;
            }
        }

        /* MÀN HÌNH THẤP */
        @media (max-height: 800px) {
            .sidebar-link {
                min-height: 34px;
                padding: 7px 12px;
                margin: 1px 10px;
                font-size: 12px;
            }

            .sidebar-group-title {
                margin: 7px 0 4px;
                font-size: 9px;
            }

            .sidebar-divider {
                margin: 5px 20px;
            }

            .sidebar-logo {
                height: 58px;
                min-height: 58px;
            }
        }

        /* MOBILE */
        @media (max-width: 1023px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | MENU ADMIN THEO NHÓM NGHIỆP VỤ
    |--------------------------------------------------------------------------
    */
    $menuGroups = [
        [
            'title' => 'TỔNG QUAN',
            'menus' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-gauge-high',
                    'route' => 'admin.dashboard',
                    'active' => ['admin.dashboard'],
                ],
            ],
        ],
        [
            'title' => 'NGHIỆP VỤ KINH DOANH',
            'menus' => [
                [
                    'label' => 'Quản lý sản phẩm',
                    'icon' => 'fas fa-shirt',
                    'route' => 'admin.products.index',
                    'active' => ['admin.products.*'],
                ],
                [
                    'label' => 'Quản lý đơn hàng',
                    'icon' => 'fas fa-cart-shopping',
                    'route' => 'orders.index',
                    'active' => ['orders.*'],
                ],
                [
                    'label' => 'Quản lý đánh giá',
                    'icon' => 'fas fa-comments',
                    'route' => 'admin.comments.index',
                    'active' => ['admin.comments.*'],
                ],
            ],
        ],
        [
            'title' => 'DANH MỤC & THUỘC TÍNH',
            'menus' => [
                [
                    'label' => 'Danh mục sản phẩm',
                    'icon' => 'fas fa-layer-group',
                    'route' => 'admin.categories.index',
                    'active' => ['admin.categories.*'],
                ],
                [
                    'label' => 'Quản lý chất liệu',
                    'icon' => 'fas fa-scroll',
                    'route' => 'admin.materials.index',
                    'active' => ['admin.materials.*'],
                ],
                [
                    'label' => 'Quản lý màu sắc',
                    'icon' => 'fas fa-palette',
                    'route' => 'admin.colors.index',
                    'active' => ['admin.colors.*'],
                ],
                [
                    'label' => 'Quản lý kích thước',
                    'icon' => 'fas fa-ruler-combined',
                    'route' => 'admin.sizes.index',
                    'active' => ['admin.sizes.*'],
                ],
            ],
        ],
        [
            'title' => 'QUẢN TRỊ & BÁO CÁO',
            'menus' => [
                [
                    'label' => 'Quản lý tài khoản',
                    'icon' => 'fas fa-users',
                    'route' => 'admin.accounts.index',
                    'active' => ['admin.accounts.*'],
                ],
                [
                    'label' => 'Thống kê',
                    'icon' => 'fas fa-chart-column',
                    'route' => 'statistics.index',
                    'active' => ['statistics.*'],
                ],
            ],
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | XÁC ĐỊNH TRANG ĐANG MỞ
    |--------------------------------------------------------------------------
    */
    $currentPage = 'Trang quản trị';

    foreach ($menuGroups as $group) {
        foreach ($group['menus'] as $menu) {
            if (request()->routeIs(...$menu['active'])) {
                $currentPage = $menu['label'];
                break 2;
            }
        }
    }
@endphp

{{-- MOBILE OVERLAY --}}
<div id="sidebarOverlay"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"
     onclick="closeSidebar()">
</div>

<div class="min-h-screen">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="admin-sidebar fixed top-0 left-0 h-screen
                  text-white z-50 flex flex-col shadow-xl">

        {{-- LOGO --}}
        <div class="sidebar-logo flex items-center justify-center
                    border-b border-white/10 px-4">

            <div class="sidebar-logo-text text-center">
                <h1 class="text-xl font-extrabold tracking-wider">
                    AD HERITAGE
                </h1>

                <p class="text-[9px] text-rose-200 tracking-[2px] mt-1">
                    ADMIN MANAGEMENT
                </p>
            </div>

            <div id="collapsedLogo"
                 class="hidden text-xl font-extrabold">
                AD
            </div>
        </div>

        {{-- MENU --}}
        <nav class="sidebar-nav">

            @foreach($menuGroups as $group)

                <div class="sidebar-group">

                    {{-- TÊN NHÓM --}}
                    <h3 class="sidebar-group-title">
                        {{ $group['title'] }}
                    </h3>

                    {{-- CHỨC NĂNG --}}
                    @foreach($group['menus'] as $menu)

                        @php
                            $isActive = request()->routeIs(
                                ...$menu['active']
                            );
                        @endphp

                        <a href="{{ route($menu['route']) }}"
                           title="{{ $menu['label'] }}"
                           class="sidebar-link {{ $isActive ? 'active' : '' }}"
                           @if($isActive) aria-current="page" @endif>

                            <i class="{{ $menu['icon'] }}"></i>

                            <span class="sidebar-text flex-1">
                                {{ $menu['label'] }}
                            </span>

                            @if($isActive)
                                <i class="sidebar-text fas fa-chevron-right
                                          text-[10px] opacity-70"></i>
                            @endif

                        </a>

                    @endforeach

                </div>

                @if(!$loop->last)
                    <div class="sidebar-divider"></div>
                @endif

            @endforeach

        </nav>

        {{-- FOOTER --}}
        <div class="border-t border-white/10 px-3 py-3">

            <div class="flex items-center justify-center gap-2
                        text-rose-200/70">

                <i class="fas fa-shield-halved text-xs"></i>

                <span class="sidebar-footer-text text-[10px]">
                    © {{ date('Y') }} AD Heritage
                </span>

            </div>
        </div>

    </aside>

    {{-- ================= MAIN ================= --}}
    <div class="admin-main min-h-screen">

        {{-- HEADER --}}
        <header class="sticky top-0 z-30 bg-white
                       border-b border-gray-200 shadow-sm h-[72px]">

            <div class="h-full px-5 lg:px-8
                        flex items-center justify-between gap-4">

                {{-- LEFT --}}
                <div class="flex items-center gap-4 min-w-0">

                    {{-- MENU TOGGLE --}}
                    <button type="button"
                            onclick="toggleSidebar()"
                            aria-label="Đóng hoặc mở menu"
                            class="w-10 h-10 rounded-lg
                                   flex items-center justify-center
                                   text-gray-600 hover:bg-rose-50
                                   hover:text-[#831c30] transition">

                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    {{-- TITLE --}}
                    <div class="min-w-0">

                        <h2 class="text-lg lg:text-xl font-bold
                                   text-[#641527] truncate">
                            @yield('title', $currentPage)
                        </h2>

                        <p class="hidden sm:block text-xs
                                  text-gray-400 mt-1">
                            Hệ thống quản trị Áo Dài Heritage
                        </p>

                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-4">

                    {{-- ADMIN --}}
                    <div class="hidden sm:flex items-center gap-3">

                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ Auth::user()->HoTen ?? 'Admin' }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                Quản trị viên
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-full
                                    bg-[#f8e8eb] text-[#831c30]
                                    flex items-center justify-center">

                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>

                    <div class="hidden sm:block h-8 w-px bg-gray-200"></div>

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="flex items-center gap-2
                                       px-4 py-2.5 rounded-lg
                                       border border-red-200
                                       text-[#831c30]
                                       text-sm font-semibold
                                       hover:bg-[#831c30]
                                       hover:text-white transition-all">

                            <i class="fas fa-right-from-bracket"></i>

                            <span class="hidden sm:inline">
                                Đăng xuất
                            </span>
                        </button>
                    </form>

                </div>
            </div>
        </header>

        {{-- ================= CONTENT ================= --}}
        <main class="p-4 md:p-6 lg:p-8">

            {{-- BREADCRUMB --}}
            <div class="mb-6 flex items-center gap-2
                        text-xs text-gray-500">

                <a href="{{ route('admin.dashboard') }}"
                   class="hover:text-[#831c30] transition">

                    <i class="fas fa-house mr-1"></i>
                    Trang quản trị
                </a>

                <i class="fas fa-chevron-right text-[9px]
                          text-gray-300"></i>

                <span class="text-[#831c30] font-semibold">
                    {{ $currentPage }}
                </span>
            </div>

            {{-- NỘI DUNG TRANG --}}
            @yield('content')

        </main>

    </div>

</div>

{{-- ================= JAVASCRIPT ================= --}}
<script>
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');

        if (sidebarOverlay) {
            sidebarOverlay.classList.add('hidden');
        }
    }

    function toggleSidebar() {
        if (window.innerWidth < 1024) {
            const isOpen = document.body.classList.toggle('sidebar-open');

            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('hidden', !isOpen);
            }
        } else {
            document.body.classList.toggle('sidebar-collapsed');
        }
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });
</script>

@stack('scripts')

</body>
</html>

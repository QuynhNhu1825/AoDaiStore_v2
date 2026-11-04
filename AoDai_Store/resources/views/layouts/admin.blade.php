    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin - @yield('title')</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100 font-sans">

        <div class="flex min-h-screen">
            <aside class="w-64 bg-slate-900 text-white fixed h-full">
                <div class="p-6 text-2xl font-bold border-b border-slate-800 text-center">
                    AD HERITAGE
                </div>
                <nav class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-6 bg-blue-600 text-white">
                        <i class="fas fa-chart-line mr-3"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-tshirt mr-3"></i> Quản lý sản phẩm
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-layer-group mr-3"></i> Quản lý danh mục
                    </a>

                    <a href="{{ route('admin.materials.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-scroll mr-3"></i> Quản lý chất liệu
                    </a>

                    <a href="{{ route('admin.colors.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-palette mr-3"></i> Quản lý màu sắc
                    </a>

                    <a href="{{ route('admin.sizes.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-ruler-combined mr-3"></i> Quản lý Size
                    </a>
                    <a href="{{ route('admin.accounts.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-user mr-3"></i> Quản lý tài khoản

                    <a href="{{ route('admin.suppliers.index') }}"
                    class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-truck mr-3"></i> Quản lý nhà cung cấp
                    </a>
                    <a href="{{ route('admin.contacts.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-envelope mr-3"></i> Quản lý liên hệ
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-shopping-cart mr-3"></i> Đơn hàng
                    </a>
                    <a href="{{ route('promotions.index') }}" class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-tags mr-3"></i> Khuyến mãi
                    </a>
                    <a href="{{ route('statistics.index') }}"
                    class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-chart-line mr-3"></i> Thống kê
                    </a>
                    <a href="{{ route('admin.baiviet.index') }}"
                    class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-newspaper mr-3"></i>
                    Quản lý bài viết
                    </a>
                    <a href="{{ route('admin.comments.index') }}"
                    class="flex items-center py-3 px-6 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <i class="fas fa-comments mr-3"></i>
                    Quản lý bình luận
                    </a>

                </nav>
            </aside>

            <main class="flex-1 ml-64">
                <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-700">@yield('title')</h2>
                    <div class="flex items-center">
                        <span class="mr-4 text-gray-600">Xin chào, Admin</span>
                        <img src="https://ui-avatars.com/api/?name=Admin" class="w-8 h-8 rounded-full">
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="px-2 py-1">
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-red-600 border border-red-100 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                <span>Đăng xuất</span>
                            </button>
                        </div>
                    </form>
                </header>

                <div class="p-8">
                    @yield('content')
                </div>
            </main>
        </div>

    </body> 
    </html>
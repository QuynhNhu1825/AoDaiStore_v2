@extends('layouts.client')

@section('title', 'Hướng dẫn mua hàng')

@section('content')
<section class="bg-stone-50 min-h-screen py-12 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="serif text-3xl font-bold uppercase tracking-widest text-stone-800">
                Hướng dẫn mua hàng
            </h1>
            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>
            <p class="text-stone-500 mt-4">
                Các bước đặt mua áo dài tại Heritage.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-10">

            @php
                $steps = [
                    [
                        'title' => 'Tìm kiếm và lựa chọn sản phẩm',
                        'desc' => 'Truy cập danh sách sản phẩm, sử dụng chức năng tìm kiếm hoặc bộ lọc để lựa chọn mẫu áo dài yêu thích.',
                        'icon' => 'fa-search'
                    ],
                    [
                        'title' => 'Chọn màu sắc, kích cỡ và số lượng',
                        'desc' => 'Tại trang chi tiết sản phẩm, lựa chọn biến thể màu sắc, kích cỡ và số lượng phù hợp với nhu cầu.',
                        'icon' => 'fa-ruler'
                    ],
                    [
                        'title' => 'Thêm sản phẩm vào giỏ hàng',
                        'desc' => 'Nhấn Thêm vào giỏ hàng, sau đó kiểm tra các sản phẩm, số lượng và tổng tiền trước khi thanh toán.',
                        'icon' => 'fa-shopping-cart'
                    ],
                    [
                        'title' => 'Đăng nhập và nhập thông tin nhận hàng',
                        'desc' => 'Đăng nhập tài khoản, kiểm tra thông tin người nhận, số điện thoại và địa chỉ giao hàng.',
                        'icon' => 'fa-user'
                    ],
                    [
                        'title' => 'Lựa chọn phương thức thanh toán',
                        'desc' => 'Lựa chọn thanh toán khi nhận hàng (COD) hoặc chuyển khoản bằng VietQR.',
                        'icon' => 'fa-credit-card'
                    ],
                    [
                        'title' => 'Theo dõi trạng thái đơn hàng',
                        'desc' => 'Sau khi đặt hàng, truy cập Đơn hàng của tôi để theo dõi quá trình xác nhận, giao hàng và hoàn tất đơn hàng.',
                        'icon' => 'fa-box'
                    ],
                ];
            @endphp

            <div class="space-y-6">
                @foreach($steps as $index => $step)
                    <div class="flex gap-5 items-start p-5 rounded-xl
                                border border-stone-100 hover:bg-stone-50 transition">

                        <div class="w-12 h-12 flex-shrink-0 bg-red-900 text-white
                                    rounded-xl flex items-center justify-center font-bold">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1">
                            <h2 class="font-bold text-stone-800 mb-2">
                                <i class="fas {{ $step['icon'] }} text-red-900 mr-2"></i>
                                {{ $step['title'] }}
                            </h2>
                            <p class="text-sm text-stone-600 leading-relaxed">
                                {{ $step['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 bg-amber-50 border border-amber-200 rounded-xl p-5">
                <h3 class="font-bold text-amber-800 mb-2">
                    Lưu ý
                </h3>
                <p class="text-sm text-amber-800 leading-relaxed">
                    Với thanh toán VietQR, sau khi thông báo đã chuyển khoản,
                    khách hàng cần chờ cửa hàng kiểm tra và xác nhận thanh toán.
                    Đơn hàng sẽ được tự động xác nhận sau khi Admin xác nhận thanh toán.
                </p>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 bg-red-900 hover:bg-red-800
                          text-white px-7 py-3 rounded-xl font-semibold transition">
                    Khám phá sản phẩm
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</section>
@endsection

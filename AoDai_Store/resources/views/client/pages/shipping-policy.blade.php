@extends('layouts.client')

@section('title', 'Chính sách vận chuyển')

@section('content')
<section class="bg-stone-50 min-h-screen py-12 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="serif text-3xl font-bold uppercase tracking-widest text-stone-800">
                Chính sách vận chuyển
            </h1>
            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>
            <p class="text-stone-500 mt-4">
                Thông tin giao hàng và vận chuyển tại Áo Dài Heritage.
            </p>
        </div>
        
        {{-- NÚT QUAY LẠI --}}
<div class="text-left mt-10">
    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2
              text-base font-medium text-stone-600
              hover:text-red-900 transition-colors">

        <span>←</span>
        <span>Quay lại trang chủ</span>

    </a>
</div>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-10 space-y-8">

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-3">
                    1. Phạm vi giao hàng
                </h2>
                <p class="text-sm text-stone-600 leading-7">
                    Heritage hỗ trợ tiếp nhận đơn hàng có địa chỉ giao hàng
                    tại Việt Nam. Khả năng giao hàng cụ thể phụ thuộc vào
                    đơn vị vận chuyển và khu vực nhận hàng.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-3">
                    2. Thời gian xử lý đơn hàng
                </h2>
                <p class="text-sm text-stone-600 leading-7">
                    Đơn hàng được xử lý sau khi cửa hàng xác nhận.
                    Thời gian chuẩn bị và giao hàng có thể thay đổi tùy
                    theo tình trạng sản phẩm, địa chỉ nhận hàng và
                    hoạt động của đơn vị vận chuyển.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-3">
                    3. Phí vận chuyển
                </h2>
                <p class="text-sm text-stone-600 leading-7">
                    Phí vận chuyển được hiển thị trong phần tổng kết đơn hàng
                    tại bước thanh toán. Khách hàng nên kiểm tra tổng tiền
                    trước khi xác nhận đặt hàng.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-3">
                    4. Theo dõi đơn hàng
                </h2>
                <p class="text-sm text-stone-600 leading-7">
                    Khách hàng có thể truy cập mục Đơn hàng của tôi
                    để theo dõi các trạng thái: Chờ xác nhận,
                    Đã xác nhận, Đang giao, Đã giao hoặc Đã hủy.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-3">
                    5. Kiểm tra khi nhận hàng
                </h2>
                <p class="text-sm text-stone-600 leading-7">
                    Khi nhận hàng, khách hàng nên kiểm tra tình trạng bao bì,
                    mẫu sản phẩm, màu sắc, kích cỡ và số lượng.
                    Nếu phát hiện vấn đề, vui lòng liên hệ hotline 1900 6333 hoặc zalo 0345 310 901
                    để được hướng dẫn xử lý.
                </p>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-5">
                <p class="text-sm text-red-900 leading-relaxed">
                    <i class="fas fa-info-circle mr-2"></i>
                    Lưu ý: Thời gian giao hàng là dự kiến và có thể thay đổi
                    do thời tiết, ngày lễ hoặc các yếu tố vận chuyển khác.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection

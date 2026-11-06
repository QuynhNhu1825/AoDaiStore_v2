@extends('layouts.client')

@section('title', 'Các phương thức thanh toán')

@section('content')
<section class="bg-stone-50 min-h-screen py-12 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="serif text-3xl font-bold uppercase tracking-widest text-stone-800">
                Các phương thức thanh toán
            </h1>
            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>
            <p class="text-stone-500 mt-4">
                Lựa chọn hình thức thanh toán phù hợp khi mua sắm tại Heritage.
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

        <div class="grid md:grid-cols-2 gap-6 mb-8">

            {{-- COD --}}
            <div class="bg-white border border-stone-100 shadow-sm rounded-2xl p-7">

                <h2 class="text-xl font-bold text-red-900 mb-3">
                    Thanh toán khi nhận hàng (COD)
                </h2>

                <p class="text-sm text-stone-600 leading-7 mb-5">
                    Khách hàng đặt hàng trên website và thanh toán
                    khi nhận sản phẩm từ đơn vị giao hàng.
                </p>

                <div class="border-t border-stone-100 pt-5 space-y-3 text-sm text-stone-600">
                    <p><i class="fas fa-check text-green-600 mr-2"></i>Không cần chuyển khoản trước.</p>
                    <p><i class="fas fa-check text-green-600 mr-2"></i>Thanh toán khi nhận hàng.</p>
                    <p><i class="fas fa-check text-green-600 mr-2"></i>Theo dõi đơn hàng trên website.</p>
                </div>
            </div>

            {{-- VIETQR --}}
            <div class="bg-white border border-stone-100 shadow-sm rounded-2xl p-7">

                <h2 class="text-xl font-bold text-red-900 mb-3">
                    Chuyển khoản VietQR
                </h2>

                <p class="text-sm text-stone-600 leading-7 mb-5">
                    Khách hàng sử dụng ứng dụng ngân hàng để quét mã QR
                    và thực hiện chuyển khoản theo thông tin hiển thị.
                </p>

                <div class="border-t border-stone-100 pt-5 space-y-3 text-sm text-stone-600">
                    <p><i class="fas fa-check text-green-600 mr-2"></i>Quét mã QR bằng ứng dụng ngân hàng.</p>
                    <p><i class="fas fa-check text-green-600 mr-2"></i>Kiểm tra số tiền và nội dung chuyển khoản.</p>
                    <p><i class="fas fa-check text-green-600 mr-2"></i>Thông báo đã chuyển khoản trên website.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-7">
            <h2 class="text-xl font-bold text-red-900 mb-5">
                Quy trình xác nhận thanh toán VietQR
            </h2>

            <div class="space-y-4">
                @foreach([
                    'Khách hàng đặt đơn và chọn VietQR.',
                    'Hệ thống hiển thị mã QR và thông tin thanh toán.',
                    'Khách hàng thực hiện chuyển khoản và nhấn Đã chuyển khoản.',
                    'Đơn hàng chuyển sang trạng thái Chờ xác nhận thanh toán.',
                    'Admin kiểm tra giao dịch và xác nhận thanh toán.',
                    'Hệ thống ghi nhận Đã thanh toán và tự động xác nhận đơn hàng.',
                    'Khách hàng theo dõi trạng thái đơn hàng ở "Đơn hàng của tôi" và nhận sản phẩm.'
                ] as $index => $step)
                    <div class="flex items-start gap-4">
                        <span class="w-8 h-8 flex-shrink-0 rounded-full
                                     bg-red-900 text-white flex items-center
                                     justify-center text-xs font-bold">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-sm text-stone-600 leading-8">
                            {{ $step }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-7 bg-amber-50 border border-amber-200 rounded-xl p-5">
                <p class="text-sm text-amber-800 leading-relaxed">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Lưu ý: Hệ thống VietQR hiện tại chưa tự động đối soát
                    giao dịch ngân hàng. Việc khách hàng nhấn
                    Đã chuyển khoản chỉ là thông báo để cửa hàng kiểm tra.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.client')

@section('title', 'Thanh toán VietQR')

@section('content')

<section class="min-h-screen bg-stone-50 py-12 px-4">

    <div class="max-w-3xl mx-auto">

        {{-- TIÊU ĐỀ --}}
        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-red-900">
                THANH TOÁN VIETQR
            </h1>

            <p class="text-gray-500 mt-2">
                Thanh toán đơn hàng bằng hình thức VietQR.
            </p>

        </div>

        {{-- THÔNG TIN THANH TOÁN --}}
        <div class="bg-white shadow-lg rounded-xl
                    overflow-hidden border border-stone-200">

            {{-- HEADER --}}
            <div class="bg-red-900 text-white p-6 text-center">

                <h2 class="text-xl font-semibold">
                    Thông tin thanh toán
                </h2>

                <p class="text-red-100 text-sm mt-1">
                    Mã đơn hàng #{{ $order->MaDonHang }}
                </p>

            </div>

            <div class="p-6 md:p-8">

                {{-- ĐƠN ĐÃ HỦY --}}
                @if($order->TrangThai === 'DaHuy')

                    <div class="bg-red-50 border border-red-200
                                text-red-700 rounded-lg p-5 text-center">

                        <h3 class="font-bold text-lg">
                            Đơn hàng đã bị hủy
                        </h3>

                        <p class="text-sm mt-2">
                            Không thể thực hiện thanh toán
                            cho đơn hàng này.
                        </p>

                    </div>

                {{-- ĐÃ THANH TOÁN --}}
                @elseif($order->TrangThaiThanhToan === 'DaThanhToan')

                    <div class="bg-green-50 border border-green-200
                                rounded-lg p-6 text-center">

                        <div class="text-green-600 text-5xl mb-3">
                            ✓
                        </div>

                        <h3 class="text-xl font-bold text-green-700">
                            Đã thanh toán
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Admin đã xác nhận thanh toán
                            cho đơn hàng của bạn.
                        </p>

                        @if($order->NgayThanhToan)
                            <p class="text-sm text-gray-500 mt-3">
                                Thời gian xác nhận:
                                {{ $order->NgayThanhToan->format('d/m/Y H:i') }}
                            </p>
                        @endif

                         <a
                          href="{{ route('order.history') }}"
                          class="inline-flex items-center justify-center gap-2
                          bg-red-800 hover:bg-red-900 text-white
                          px-6 py-3 rounded-xl font-bold
                          text-sm transition shadow-md"
                        >
                        <i class="fas fa-box"></i>
                          Đơn hàng của tôi
                        <i class="fas fa-arrow-right"></i>
                        </a>

                    </div>

                {{-- CHỜ ADMIN XÁC NHẬN --}}

@elseif($order->TrangThaiThanhToan === 'ChoXacNhanThanhToan')

    <div class="w-full max-w-md mx-auto py-8">

        {{-- TIÊU ĐỀ --}}
        <div class="text-center mb-6">

            <h3 class="text-xl md:text-2xl font-bold
                       text-stone-800 leading-snug">
                Đã ghi nhận chuyển khoản!
            </h3>

            <p class="text-sm text-stone-500
                      leading-relaxed mt-3">
                Hệ thống đã ghi nhận thông báo chuyển khoản
                cho đơn hàng
                <span class="font-bold text-red-900">
                    #{{ $order->MaDonHang }}
                </span>.
            </p>

        </div>

        {{-- TRẠNG THÁI THANH TOÁN --}}
        <div class="w-full bg-amber-50
                    border border-amber-200
                    rounded-xl px-5 py-5 mb-5">

            <div class="flex items-center justify-center gap-2 mb-3">

                <i class="fas fa-clock text-amber-700"></i>

                <span class="font-bold text-sm text-amber-800">
                    Chờ xác nhận thanh toán
                </span>

            </div>

            <p class="text-sm text-amber-800
                      text-center leading-relaxed">
                Cửa hàng sẽ kiểm tra giao dịch của bạn.
            </p>

        </div>

        {{-- NÚT ĐƠN HÀNG CỦA TÔI --}}
        <a href="{{ route('order.history') }}"
           class="w-full min-h-[50px]
                  flex items-center justify-center gap-3
                  bg-red-900 hover:bg-red-800
                  text-white font-semibold text-sm
                  rounded-xl px-5 py-3
                  shadow-sm hover:shadow-md
                  transition-all duration-200">

            <i class="fas fa-box"></i>

            <span>Đơn hàng của tôi</span>

            <i class="fas fa-arrow-right text-xs"></i>

        </a>

    </div>




                {{-- CHƯA THANH TOÁN --}}
                @elseif(
                    $order->TrangThaiThanhToan
                    === 'ChuaThanhToan'
                    && $order->TrangThai === 'ChoXacNhan'
                )

                    {{-- QR CODE --}}
                    <div class="flex flex-col items-center mb-8">

                        <div class="border-4 border-red-900
                                    rounded-xl p-4 bg-white">

                            <div id="vietqr"
                                 class="w-52 h-52 flex items-center
                                        justify-center">
                            </div>

                        </div>

                        <p class="text-red-900 font-bold mt-4">
                            VIETQR
                        </p>

                        <p class="text-gray-500 text-xs mt-1 text-center">
                            Mã QR chỉ là thử nghiệm.
                        </p>

                    </div>

                    {{-- THÔNG TIN ĐƠN HÀNG --}}
                    <div class="bg-stone-50 rounded-lg p-5 mb-6">

                        <h3 class="font-bold text-gray-800
                                   mb-4 border-b pb-3">
                            Chi tiết thanh toán
                        </h3>

                        <div class="flex justify-between mb-3 gap-3">

                            <span class="text-gray-500">
                                Mã đơn hàng
                            </span>

                            <span class="font-semibold">
                                #{{ $order->MaDonHang }}
                            </span>

                        </div>

                        <div class="flex justify-between mb-3 gap-3">

                            <span class="text-gray-500">
                                Phương thức thanh toán
                            </span>

                            <span class="font-medium">
                                VietQR
                            </span>

                        </div>

                        <div class="flex justify-between mb-3 gap-3">

                            <span class="text-gray-500">
                                Nội dung
                            </span>

                            <span class="font-semibold text-red-900">
                                {{ $transferContent }}
                            </span>

                        </div>

                        <div class="border-t pt-4 mt-4
                                    flex justify-between items-center gap-3">

                            <span class="font-semibold text-gray-800">
                                Tổng thanh toán
                            </span>

                            <span class="text-2xl font-bold text-red-900">
                                {{ number_format($amount, 0, ',', '.') }}đ
                            </span>

                        </div>

                    </div>

                    {{-- NÚT XÁC NHẬN --}}
                    <form
    id="vietqr-confirm-form"
    action="{{ route('vietqr.confirm', $order->MaDonHang) }}"
    method="POST"
>
    @csrf

    <button
        type="submit"
        class="w-full bg-red-900 hover:bg-red-800
               text-white font-semibold py-4 px-6
               rounded-xl transition duration-200"
    >
        <i class="fas fa-check-circle mr-2"></i>
        Đã chuyển khoản
    </button>
</form>

                @else

                    <div class="bg-stone-100 rounded-lg p-5 text-center">

                        <p class="text-gray-600">
                            Đơn hàng hiện không thể thực hiện
                            thao tác thanh toán VietQR
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</section>

{{-- THƯ VIỆN TẠO QR CODE --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const qrElement = document.getElementById('vietqr');

    if (!qrElement) {
        return;
    }

    const qrData = @json($qrData);

    if (typeof QRCode === 'undefined') {
        qrElement.innerHTML =
            '<p class="text-red-600 text-sm text-center">' +
            'Không thể tải mã QR .' +
            '</p>';
        return;
    }

    new QRCode(qrElement, {
        text: qrData,
        width: 208,
        height: 208,
        colorDark: '#7f1d1d',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.vietqr-alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';

            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 4000);
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('vietqr-confirm-form');

    if (!form) return;

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const result = await Swal.fire({
            title: 'Xác nhận chuyển khoản?',
            html: `
                <p style="color:#57534e;font-size:14px;margin-bottom:16px;">
                    Bạn đã thực hiện chuyển khoản VietQR
                    cho đơn hàng này?
                </p>

                <div style="
                    background:#fafaf9;
                    border-radius:12px;
                    padding:16px;
                    text-align:left;
                    font-size:14px;
                ">
                    <div style="
                        display:flex;
                        justify-content:space-between;
                        margin-bottom:12px;
                    ">
                        <span>Mã đơn hàng</span>
                        <strong>#{{ $order->MaDonHang }}</strong>
                    </div>

                    <div style="
                        display:flex;
                        justify-content:space-between;
                    ">
                        <span>Số tiền</span>
                        <strong style="color:#7f1d1d;">
                            {{ number_format($amount, 0, ',', '.') }}đ
                        </strong>
                    </div>
                </div>

                <p style="
                    font-size:12px;
                    color:#78716c;
                    margin-top:16px;
                ">
                    Cửa hàng sẽ kiểm tra và xác nhận
                    thanh toán sau khi bạn gửi thông báo.
                </p>
            `,
            icon: 'question',
            iconColor: '#7f1d1d',
            showCancelButton: true,
            confirmButtonText: 'Đã chuyển khoản',
            cancelButtonText: 'Hủy bỏ',
            confirmButtonColor: '#7f1d1d',
            cancelButtonColor: '#78716c',
            reverseButtons: true,
            buttonsStyling: true,
            customClass: {
                popup: 'vietqr-swal-popup',
                confirmButton: 'vietqr-swal-button',
                cancelButton: 'vietqr-swal-button'
            }
        });

        if (result.isConfirmed) {
            const button = form.querySelector('button[type="submit"]');

            if (button) {
                button.disabled = true;
                button.innerHTML =
                    '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
            }

            // Gửi form trực tiếp, không kích hoạt lại sự kiện submit.
            form.submit();
        }
    });
});

</script>

@endsection

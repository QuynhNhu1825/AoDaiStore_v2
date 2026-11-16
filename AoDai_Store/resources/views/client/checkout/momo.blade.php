@extends('layouts.client')

@section('title', 'Thanh toán MoMo')

@section('content')

<section class="max-w-xl mx-auto py-16 px-4">

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-2xl font-bold text-center text-red-800 mb-6">
            Thanh toán MoMo
        </h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">

            <div class="flex justify-between">
                <span>Mã đơn hàng</span>
                <strong>#{{ $order->MaDonHang }}</strong>
            </div>

            <div class="flex justify-between">
                <span>Phương thức</span>
                <strong>MoMo</strong>
            </div>

            <div class="flex justify-between border-t pt-4">
                <span>Tổng thanh toán</span>
                <strong class="text-red-800 text-xl">
                    {{ number_format($amount, 0, ',', '.') }}đ
                </strong>
            </div>

            <div class="text-center bg-red-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">
                    Thời gian thanh toán còn lại
                </p>

                <p id="countdown"
                   class="text-2xl font-bold text-red-800 mt-2">
                    --:--
                </p>
            </div>

            @if($order->canPay())
                <form action="{{ route('momo.pay', $order->MaDonHang) }}"
                      method="POST">
                    @csrf

                    <button type="submit"
                            id="payButton"
                            class="w-full bg-pink-600 hover:bg-pink-700
                                   text-white py-3 rounded-lg font-semibold">
                        Thanh toán qua MoMo
                    </button>
                </form>
            @else
                <p class="text-center text-red-600">
                    Đơn hàng không còn đủ điều kiện thanh toán.
                </p>
            @endif

        </div>

    </div>

</section>

<script>
    const deadline = @json(
        $order->HanThanhToan?->toIso8601String()
    );

    const countdown = document.getElementById('countdown');
    const payButton = document.getElementById('payButton');

    function updateCountdown() {
        if (!deadline) {
            countdown.textContent = '--:--';
            return;
        }

        const remaining = Math.max(
            0,
            Math.ceil(
                (new Date(deadline).getTime() - Date.now()) / 1000
            )
        );

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        countdown.textContent =
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');

        if (remaining === 0 && payButton) {
            payButton.disabled = true;
            payButton.textContent = 'Đã hết thời gian thanh toán';
            payButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>

@endsection

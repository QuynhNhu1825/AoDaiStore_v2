@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng')

@section('content')

@php
    $statusLabels = [
        'ChoXacNhan' => 'Chờ xác nhận',
        'DaXacNhan' => 'Đã xác nhận',
        'DangGiao' => 'Đang giao',
        'DaGiao' => 'Đã giao',
        'DaHoanThanh' => 'Đã hoàn thành',
        'DaHuy' => 'Đã hủy',
    ];

    $statusColors = [
        'ChoXacNhan' => 'bg-amber-50 text-amber-700 border-amber-200',
        'DaXacNhan' => 'bg-blue-50 text-blue-700 border-blue-200',
        'DangGiao' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'DaGiao' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'DaHoanThanh' => 'bg-green-50 text-green-700 border-green-200',
        'DaHuy' => 'bg-red-50 text-red-700 border-red-200',
    ];

    $status = $order->TrangThai;

    $statusText = $statusLabels[$status] ?? $status;

    $statusClass = $statusColors[$status]
        ?? 'bg-stone-100 text-stone-700 border-stone-200';

    $details = $order->orderDetails;

    $totalQuantity = $details->sum('SoLuong');

    $subtotal = (float) $order->TienHang;
    $shipping = (float) $order->PhiVanChuyen;
    $discount = (float) ($order->GiamGia ?? 0);

    $total = $subtotal + $shipping - $discount;

    $paymentMethod = strtolower(
        (string) $order->PhuongThucThanhToan
    );
@endphp

<section class="min-h-screen bg-[#f8f7f5] py-10 px-4">

    <div class="max-w-6xl mx-auto">

        {{-- ================= TIÊU ĐỀ ================= --}}
        <div class="text-center mb-10">

            <p class="text-red-800 uppercase tracking-[0.3em]
                      text-xs font-semibold mb-3">
                Áo Dài Heritage
            </p>

            <h1 class="text-3xl md:text-4xl font-bold uppercase
                       tracking-widest text-stone-900"
                style="font-family: Georgia, serif;">
                CHI TIẾT ĐƠN HÀNG
            </h1>

            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>

            <p class="text-stone-500 text-sm mt-4">
                Xem thông tin và theo dõi đơn hàng của bạn
            </p>

        </div>

        {{-- ================= THÔNG BÁO ================= --}}
        @if(session('success'))
            <div id="success-alert"
                 class="mb-6 p-4 bg-green-50 border border-green-200
                        text-green-700 rounded-lg
                        transition-opacity duration-500">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="error-alert"
                 class="mb-6 p-4 bg-red-50 border border-red-200
                        text-red-700 rounded-lg
                        transition-opacity duration-500">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= THẺ ĐƠN HÀNG ================= --}}
        <div class="bg-white border border-stone-200
                    rounded-xl shadow-sm overflow-hidden mb-6">

            {{-- HEADER ĐỎ ĐÔ --}}
            <div class="bg-gradient-to-r from-red-950 to-red-800
                        px-6 md:px-8 py-7 text-white">

                <div class="flex flex-col sm:flex-row
                            sm:items-center sm:justify-between gap-4">

                    <div>

                        <p class="text-red-100 text-xs uppercase
                                  tracking-widest mb-2">
                            Thông tin đơn hàng
                        </p>

                        <h2 class="text-2xl font-bold">
                            Đơn hàng #{{ $order->MaDonHang }}
                        </h2>

                        <p class="text-red-100 text-sm mt-2">
                            <i class="far fa-calendar-alt mr-2"></i>

                            Ngày đặt:

                            {{ $order->NgayDat
                                ? $order->NgayDat->format('d/m/Y H:i')
                                : 'Đang cập nhật' }}
                        </p>

                    </div>

                    <div>
                        <span class="inline-flex items-center
                                     px-4 py-2 rounded-full
                                     border text-xs font-bold
                                     {{ $statusClass }}">

                            {{ $statusText }}

                        </span>
                    </div>

                </div>

            </div>

            {{-- ================= THÔNG TIN NHẬN HÀNG ================= --}}
            <div class="px-6 md:px-8 py-7">

                <div class="flex items-center gap-3 mb-6">

                    <h3 class="text-lg font-bold text-stone-800">
                        Thông tin nhận hàng
                    </h3>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-xs text-stone-500 mb-2">
                            Họ và tên người nhận
                        </p>

                        <p class="text-sm font-semibold text-stone-800">
                            {{ $order->HoTenNguoiNhan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-500 mb-2">
                            Số điện thoại
                        </p>

                        <p class="text-sm font-semibold text-stone-800">
                            {{ $order->SDTNguoiNhan }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-xs text-stone-500 mb-2">
                            Địa chỉ giao hàng
                        </p>

                        <p class="text-sm font-semibold text-stone-800">
                            {{ $order->DiaChiNhan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-500 mb-2">
                            Phương thức thanh toán
                        </p>

                        <p class="text-sm font-semibold text-stone-800">

                            @if($paymentMethod === 'cod')

                                <i class="fas fa-money-bill-wave
                                          text-green-700 mr-2"></i>

                                Thanh toán khi nhận hàng (COD)

                            @elseif($paymentMethod === 'vnpay')

                                <i class="fas fa-credit-card
                                          text-blue-700 mr-2"></i>

                                Thanh toán qua VNPay

                            @else

                                {{ $order->PhuongThucThanhToan }}

                            @endif

                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-500 mb-2">
                            Tổng số lượng
                        </p>

                        <p class="text-sm font-semibold text-stone-800">
                            {{ $totalQuantity }} sản phẩm
                        </p>
                    </div>

                    @if($order->GhiChu)

                        <div class="md:col-span-2">

                            <p class="text-xs text-stone-500 mb-2">
                                Ghi chú
                            </p>

                            <p class="text-sm text-stone-700">
                                {{ $order->GhiChu }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

        {{-- ================= SẢN PHẨM ĐÃ ĐẶT ================= --}}
        <div class="bg-white border border-stone-200
                    rounded-xl shadow-sm overflow-hidden mb-6">

            <div class="px-6 md:px-8 py-5
                        border-b border-stone-100
                        flex items-center justify-between">

                <h3 class="text-lg font-bold text-stone-800">
                    <i class="fas fa-shopping-bag text-red-800 mr-2"></i>
                    Sản phẩm đã đặt
                </h3>

                <span class="text-xs text-stone-500">
                    {{ $totalQuantity }} sản phẩm
                </span>

            </div>

            <div class="divide-y divide-stone-100">

                @forelse($details as $detail)

                    @php
                        $variant = $detail->productDetail;
                        $product = $variant?->product;

                        $productName = $product?->TenSanPham
                            ?? 'Sản phẩm không còn tồn tại';

                        $colorName = $variant?->color?->TenMau
                            ?? 'Không xác định';

                        $sizeName = $variant?->size?->TenSize
                            ?? 'Không xác định';

                        // Lấy ảnh sản phẩm
                        $imagePath = $product?->HinhAnh ?? null;

                        if (
                            !$imagePath &&
                            $product &&
                            method_exists($product, 'hinhanhsanpham')
                        ) {
                            $imagePath = $product->hinhanhsanpham
                                ->first()?->LinkAnh;
                        }

                        if ($imagePath) {

                            if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                                $imageUrl = $imagePath;
                            } elseif (
                                str_starts_with($imagePath, 'storage/') ||
                                str_starts_with($imagePath, 'img/')
                            ) {
                                $imageUrl = asset($imagePath);
                            } else {
                                $imageUrl = asset(
                                    'storage/' . ltrim($imagePath, '/')
                                );
                            }

                        } else {
                            $imageUrl = asset('img/no-image.png');
                        }

                        $lineTotal =
                            (float) $detail->DonGia *
                            (int) $detail->SoLuong;
                    @endphp

                    <div class="p-6 md:p-8
                                flex flex-col sm:flex-row gap-5">

                        {{-- ẢNH --}}
                        <div class="w-28 h-36 flex-shrink-0
                                    bg-stone-100 rounded-lg
                                    overflow-hidden border border-stone-100">

                            <img src="{{ $imageUrl }}"
                                 alt="{{ $productName }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}'">

                        </div>

                        {{-- THÔNG TIN --}}
                        <div class="flex-1 min-w-0">

                            <h4 class="font-bold text-stone-800
                                       text-base uppercase leading-relaxed"
                                style="font-family: Georgia, serif;">

                                {{ $productName }}

                            </h4>

                            <div class="flex flex-wrap gap-3 mt-3">

                                <span class="px-3 py-1 bg-stone-100
                                             text-stone-600 text-xs rounded">

                                    Màu: {{ $colorName }}

                                </span>

                                <span class="px-3 py-1 bg-stone-100
                                             text-stone-600 text-xs rounded">

                                    Size: {{ $sizeName }}

                                </span>

                            </div>

                            <p class="text-sm text-stone-500 mt-4">
                                Số lượng:
                                <strong class="text-stone-800">
                                    x{{ $detail->SoLuong }}
                                </strong>
                            </p>

                            <div class="flex flex-wrap
                                        justify-between items-end gap-4 mt-5">

                                <div>

                                    <p class="text-xs text-stone-500 mb-1">
                                        Đơn giá
                                    </p>

                                    <p class="text-sm font-semibold text-stone-700">
                                        {{ number_format(
                                            (float) $detail->DonGia,
                                            0, ',', '.'
                                        ) }}đ
                                    </p>

                                </div>

                                <div class="text-right">

                                    <p class="text-xs text-stone-500 mb-1">
                                        Thành tiền
                                    </p>

                                    <p class="text-lg font-bold text-red-800">
                                        {{ number_format(
                                            $lineTotal,
                                            0, ',', '.'
                                        ) }}đ
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="p-10 text-center text-stone-500">
                        Không tìm thấy chi tiết đơn hàng.
                    </div>

                @endforelse

            </div>

        </div>

        {{-- ================= CHI TIẾT THANH TOÁN ================= --}}
        <div class="bg-white border border-stone-200
                    rounded-xl shadow-sm overflow-hidden mb-6">

            <div class="px-6 md:px-8 py-5 border-b border-stone-100">

                <h3 class="text-lg font-bold text-stone-800">
                    <i class="fas fa-receipt text-red-800 mr-2"></i>
                    Chi tiết thanh toán
                </h3>

            </div>

            <div class="px-6 md:px-8 py-7 space-y-5">

                <div class="flex justify-between gap-4 text-sm">

                    <span class="text-stone-500">
                        Tiền hàng
                    </span>

                    <span class="font-semibold text-stone-800">
                        {{ number_format($subtotal, 0, ',', '.') }}đ
                    </span>

                </div>

                <div class="flex justify-between gap-4 text-sm">

                    <span class="text-stone-500">
                        Phí vận chuyển
                    </span>

                    <span class="font-semibold text-stone-800">
                        {{ $shipping == 0
                            ? 'Miễn phí'
                            : number_format($shipping, 0, ',', '.') . 'đ' }}
                    </span>

                </div>

                @if($discount > 0)

                    <div class="flex justify-between gap-4 text-sm">

                        <span class="text-stone-500">
                            Giảm giá
                        </span>

                        <span class="font-semibold text-green-700">
                            -{{ number_format($discount, 0, ',', '.') }}đ
                        </span>

                    </div>

                @endif

                <div class="border-t border-dashed border-stone-200
                            pt-5 flex flex-col sm:flex-row
                            sm:items-center sm:justify-between gap-3">

                    <div>

                        <p class="font-bold text-stone-800">
                            Tổng thanh toán
                        </p>

                        <p class="text-xs text-stone-500 mt-1">
                            Đã bao gồm các khoản phí
                        </p>

                    </div>

                    <p class="text-2xl font-bold text-red-800">
                        {{ number_format($total, 0, ',', '.') }}đ
                    </p>

                </div>

            </div>

        </div>

        {{-- ================= NÚT THAO TÁC ================= --}}
        <div class="bg-white border border-stone-200
                    rounded-xl shadow-sm p-6">

            <div class="flex flex-wrap justify-between
                        items-center gap-4">

                <a href="{{ route('order.history') }}"
                   class="inline-flex items-center gap-2
                          text-sm text-stone-600
                          hover:text-red-800 transition">
                    <span>←</span>
                    <i class="fas fa-arrow-left"></i>
                    Quay lại danh sách

                </a>

                <div class="flex flex-wrap gap-3">

                    {{-- HỦY ĐƠN --}}
                    @if($status === 'ChoXacNhan')

                        <form action="{{ route('order.cancel', $order->MaDonHang) }}"
                              method="POST"
                              class="cancel-order-form">

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="px-6 py-3 border border-red-800
                                           text-red-800 hover:bg-red-800
                                           hover:text-white text-xs font-bold
                                           uppercase tracking-wide transition">

                                Hủy đơn hàng

                            </button>

                        </form>

                    @endif

                    {{-- ĐÃ NHẬN HÀNG --}}
                    @if($status === 'DangGiao')

                        <form action="{{ route('order.submit', $order->MaDonHang) }}"
                              method="POST"
                              class="receive-order-form">

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="px-6 py-3 bg-red-800
                                           text-white hover:bg-red-900
                                           text-xs font-bold uppercase
                                           tracking-wide transition">

                                <i class="fas fa-check mr-2"></i>
                                Đã nhận hàng

                            </button>

                        </form>

                    @endif

                    {{-- MUA LẠI --}}
                    @if(in_array($status, ['DaGiao', 'DaHuy'], true))

                        <form action="{{ route('order.reorder', $order->MaDonHang) }}"
                              method="POST"
                              class="reorder-form">

                            @csrf

                            <button type="submit"
                                    class="px-6 py-3 bg-red-800
                                           text-white hover:bg-red-900
                                           text-xs font-bold uppercase
                                           tracking-wide transition">

                                <i class="fas fa-shopping-bag mr-2"></i>
                                Mua lại

                            </button>

                        </form>

                    @endif

                    {{-- ĐÁNH GIÁ --}}
                    @if(in_array($status, ['DaGiao'], true))

                        <form action="{{ route('order.show', $order->MaDonHang) }}"
                              method="POST"
                              class="show-form">

                            @csrf

                            <button type="submit"
                                    class="px-6 py-3 bg-red-800
                                           text-white hover:bg-red-900
                                           text-xs font-bold uppercase
                                           tracking-wide transition">

                                <i class="fas fa-shopping-bag mr-2"></i>
                                Đánh giá

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ================= SWEETALERT2 ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Tự động ẩn thông báo sau 4 giây
    ['success-alert', 'error-alert'].forEach(function (id) {

        const alert = document.getElementById(id);

        if (!alert) return;

        setTimeout(function () {

            alert.classList.add('opacity-0');

            setTimeout(function () {
                alert.remove();
            }, 500);

        }, 4000);
    });

    // Hàm xác nhận thao tác
    function confirmAction(selector, title, message, confirmText) {

        document.querySelectorAll(selector).forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#991b1b',
                    cancelButtonColor: '#78716c',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Quay lại'
                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });
            });
        });
    }

    // Hủy đơn
    confirmAction(
        '.cancel-order-form',
        'Xác nhận hủy đơn hàng?',
        'Bạn có chắc chắn muốn hủy đơn hàng này?',
        'Đồng ý hủy'
    );

    // Đã nhận hàng
    confirmAction(
        '.receive-order-form',
        'Xác nhận đã nhận hàng?',
        'Bạn xác nhận đã nhận được đơn hàng?',
        'Đã nhận hàng'
    );

    // Mua lại
    confirmAction(
        '.reorder-form',
        'Xác nhận mua lại?',
        'Sản phẩm sẽ được thêm vào giỏ hàng và chuyển đến trang xác nhận đơn hàng. Giá và tồn kho được tính theo thời điểm hiện tại.',
        'Mua lại'
    );

});
</script>

@endsection

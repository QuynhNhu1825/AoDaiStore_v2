@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')

@php
    // Danh sách tab trạng thái
    $tabs = [
        'all' => 'Tất cả',
        'ChoXacNhan' => 'Chờ xác nhận',
        'DaXacNhan' => 'Đã xác nhận',
        'DangGiao' => 'Đang giao',
        'DaGiao' => 'Đã giao',
        'DaHuy' => 'Đã hủy',
    ];

    // Màu sắc của từng trạng thái
    $statusColors = [
        'ChoXacNhan' => 'bg-amber-50 text-amber-700 border-amber-200',
        'DaXacNhan' => 'bg-blue-50 text-blue-700 border-blue-200',
        'DangGiao' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'DaGiao' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'DaHuy' => 'bg-red-50 text-red-700 border-red-200',
    ];

    $currentStatus = $status ?? request('status', 'all');
@endphp

<section class="bg-[#f8f9fa] min-h-screen py-10 px-4">

    <div class="max-w-7xl mx-auto">

        {{-- ================= TIÊU ĐỀ ================= --}}
        <div class="text-center mb-10">

            <h1 class="text-3xl font-bold uppercase tracking-widest text-stone-900"
                style="font-family: Georgia, serif;">
                ĐƠN HÀNG CỦA TÔI
            </h1>

            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>

            <p class="text-stone-500 text-sm mt-4">
                Theo dõi và quản lý các đơn hàng của bạn
            </p>

        </div>


        @if(session('error'))
            <div id="error-alert"
                 role="alert"
                 class="mb-6 p-4 bg-red-50 border border-red-200
                        text-red-700 rounded-lg transition-opacity duration-500">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= QUAY LẠI ================= --}}
        <div class="mb-6">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2
                      text-sm text-stone-600 hover:text-red-800 transition">

                <span>←</span>
                <span>Tiếp tục mua sắm</span>

            </a>
        </div>

        
{{-- THANH LỌC TRẠNG THÁI ĐƠN HÀNG --}}
@php
    $statusTabs = [
        '' => 'Tất cả',
        'ChoXacNhan' => 'Chờ xác nhận',
        'DaXacNhan' => 'Đã xác nhận',
        'DangGiao' => 'Đang giao',
        'DaGiao' => 'Đã giao',
        'DaHuy' => 'Đã hủy',
    ];

    $currentStatus = request('status', '');
@endphp

<div class="w-full bg-white rounded-xl border border-stone-200
            shadow-sm overflow-hidden mb-8">

    <div class="grid grid-cols-3 md:grid-cols-6">

        @foreach($statusTabs as $value => $label)

            @php
                $isActive = $currentStatus === $value;
            @endphp

            <a href="{{ route('order.history', ['status' => $value]) }}"
               class="relative min-h-[70px]
                      flex items-center justify-center
                      px-2 py-4 text-center
                      text-xs sm:text-sm font-semibold
                      transition-all duration-200
                      {{ $isActive
                          ? 'bg-red-50 text-red-900'
                          : 'bg-white text-stone-600 hover:bg-stone-50 hover:text-red-900' }}">

                <span class="leading-snug">
                    {{ $label }}
                </span>

                @if($isActive)
                    <span class="absolute bottom-0 left-0
                                 w-full h-[3px] bg-red-800"></span>
                @endif

            </a>

        @endforeach

    </div>
</div>


        {{-- ================= DANH SÁCH ĐƠN HÀNG ================= --}}
        <div class="space-y-6">

            @forelse($orders as $order)

                @php
                    $orderStatus = $order->TrangThai;

                    $statusLabel = $order->TrangThaiText;

                    $statusClass = $statusColors[$orderStatus]
                        ?? 'bg-gray-50 text-gray-600 border-gray-200';

                    // Đúng quan hệ trong model Order.php
                    $details = $order->orderDetails;

                    // Tổng số lượng sản phẩm
                    $totalQuantity = $details->sum('SoLuong');

                    // Tổng thanh toán
                    $totalAmount =
                        (float) $order->TienHang
                        + (float) $order->PhiVanChuyen
                        - (float) ($order->GiamGia ?? 0);
                @endphp

                <div class="bg-white border border-stone-200
                            rounded-lg shadow-sm overflow-hidden
                            hover:shadow-md transition">

                    {{-- ========== HEADER ĐƠN HÀNG ========== --}}
                    <div class="flex flex-col sm:flex-row
                                sm:items-center sm:justify-between
                                gap-3 px-6 py-5 border-b border-stone-100">

                        <div>

                            <h3 class="font-bold text-stone-800 text-base">
                                Mã đơn hàng: #{{ $order->MaDonHang }}
                            </h3>

                            <p class="text-xs text-stone-500 mt-1">
                                Ngày đặt:

                                {{ $order->NgayDat
                                    ? $order->NgayDat->format('d/m/Y H:i')
                                    : 'Đang cập nhật' }}
                            </p>

                        </div>

                        <span class="inline-flex self-start sm:self-auto
                                     items-center px-3 py-1.5 rounded-full
                                     border text-xs font-bold
                                     {{ $statusClass }}">

                            {{ $statusLabel }}

                        </span>

                    </div>

                    {{-- ========== DANH SÁCH SẢN PHẨM ========== --}}
                    <div class="divide-y divide-stone-100">

                        @forelse($details as $detail)

                            @php
                                // Đúng quan hệ trong OrderDetail.php
                                $variant = $detail->productDetail;

                                // Đúng quan hệ trong ProductDetail.php
                                $product = $variant?->product;

                                $productName = $product?->TenSanPham
                                    ?? 'Sản phẩm không còn tồn tại';

                                $colorName = $variant?->color?->TenMau
                                    ?? 'Không xác định';

                                $sizeName = $variant?->size?->TenSize
                                    ?? 'Không xác định';

                                // Ảnh sản phẩm
                                $imagePath = $product?->HinhAnh ?? null;

                                // Nếu có quan hệ ảnh thì ưu tiên ảnh chính
                                if (
                                    !$imagePath &&
                                    $product &&
                                    method_exists($product, 'hinhanhsanpham')
                                ) {
                                    $image = $product->hinhanhsanpham
                                        ->first();

                                    $imagePath = $image?->LinkAnh;
                                }

                                // Chuẩn hóa đường dẫn ảnh
                                if ($imagePath) {

                                    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                                        $imageUrl = $imagePath;
                                    } elseif (str_starts_with($imagePath, 'storage/')) {
                                        $imageUrl = asset($imagePath);
                                    } elseif (str_starts_with($imagePath, 'img/')) {
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

                            <div class="flex flex-col sm:flex-row
                                        gap-5 p-5 sm:p-6">

                                {{-- ẢNH SẢN PHẨM --}}
                                <div class="w-28 h-36 flex-shrink-0
                                            bg-stone-100 rounded-md
                                            overflow-hidden border border-stone-100">

                                    <img src="{{ $imageUrl }}"
                                         alt="{{ $productName }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}'">

                                </div>

                                {{-- THÔNG TIN SẢN PHẨM --}}
                                <div class="flex-1 min-w-0">

                                    <h4 class="font-bold text-stone-800
                                               text-base uppercase leading-relaxed"
                                        style="font-family: Georgia, serif;">

                                        {{ $productName }}

                                    </h4>

                                    <div class="flex flex-wrap
                                                gap-x-8 gap-y-2
                                                text-sm text-stone-500 mt-3">

                                        <span>
                                            Màu sắc:
                                            <strong class="text-stone-700">
                                                {{ $colorName }}
                                            </strong>
                                        </span>

                                        <span>
                                            Kích cỡ:
                                            <strong class="text-stone-700">
                                                {{ $sizeName }}
                                            </strong>
                                        </span>

                                    </div>

                                    <div class="mt-3 text-sm text-stone-500">
                                        Số lượng:
                                        <strong class="text-stone-800">
                                            {{ $detail->SoLuong }}
                                        </strong>
                                    </div>

                                    <div class="flex flex-wrap
                                                justify-between items-end
                                                gap-3 mt-5">

                                        <div class="text-sm text-stone-500">
                                            Đơn giá:
                                            {{ number_format(
                                                (float) $detail->DonGia,
                                                0, ',', '.'
                                            ) }}đ
                                        </div>

                                        <div class="text-right">

                                            <p class="text-xs text-stone-500 mb-1">
                                                Thành tiền
                                            </p>

                                            <span class="font-bold text-red-800 text-lg">
                                                {{ number_format(
                                                    $lineTotal,
                                                    0, ',', '.'
                                                ) }}đ
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="p-6 text-sm text-stone-500">
                                Không tìm thấy chi tiết đơn hàng.
                            </div>

                        @endforelse

                    </div>

                    {{-- ========== FOOTER ĐƠN HÀNG ========== --}}
                    <div class="bg-stone-50 border-t border-stone-100
                                px-6 py-5">

                        <div class="flex flex-col sm:flex-row
                                    sm:items-center sm:justify-between
                                    gap-4 mb-5">

                            <p class="text-sm text-stone-500">
                                Tổng số lượng:
                                <strong class="text-stone-800">
                                    {{ $totalQuantity }} sản phẩm
                                </strong>
                            </p>

                            <div class="text-left sm:text-right">

                                <span class="text-sm text-stone-600">
                                    Tổng thanh toán:
                                </span>

                                <span class="block sm:inline-block
                                             sm:ml-3 text-xl font-bold
                                             text-red-800">

                                    {{ number_format(
                                        $totalAmount,
                                        0, ',', '.'
                                    ) }}đ

                                </span>

                            </div>

                        </div>

                        {{-- ========== NÚT THAO TÁC ========== --}}
                        <div class="flex flex-wrap justify-end gap-3">

                            {{-- XEM CHI TIẾT --}}
                            <a href="{{ route('order.show', $order->MaDonHang) }}"
                               class="inline-flex items-center justify-center
                                      px-5 py-2.5 border border-stone-300
                                      text-stone-700 bg-white
                                      hover:border-red-800 hover:text-red-800
                                      text-xs font-bold uppercase
                                      tracking-wide transition">

                                Xem chi tiết

                            </a>

                            {{-- HỦY ĐƠN --}}
                            @if($orderStatus === 'ChoXacNhan')

                                <form action="{{ route('order.cancel', $order->MaDonHang) }}"
                                      method="POST"
                                      class="cancel-order-form">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="px-5 py-2.5
                                                   border border-red-800
                                                   text-red-800 bg-white
                                                   hover:bg-red-800 hover:text-white
                                                   text-xs font-bold uppercase
                                                   tracking-wide transition">

                                        Hủy đơn hàng

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

                            {{-- XÁC NHẬN ĐÃ NHẬN HÀNG --}}
                            @if($orderStatus === 'DangGiao')

                                <form action="{{ route('order.submit', $order->MaDonHang) }}"
                                      method="POST"
                                      class="receive-order-form">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="px-5 py-2.5
                                                   bg-red-800 text-white
                                                   hover:bg-red-900
                                                   text-xs font-bold uppercase
                                                   tracking-wide transition">

                                        Đã nhận hàng

                                    </button>

                                </form>

                            @endif

                            {{-- ĐÁNH GIÁ SẢN PHẨM --}}
                            @if($orderStatus === 'DaGiao')

                                <a href="{{ route('order.show', $order->MaDonHang) }}"
                                   class="inline-flex items-center justify-center
                                          px-5 py-2.5 bg-red-800
                                          text-white hover:bg-red-900
                                          text-xs font-bold uppercase
                                          tracking-wide transition">

                                    Đánh giá sản phẩm

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            @empty

                {{-- ========== CHƯA CÓ ĐƠN HÀNG ========== --}}
                <div class="bg-white border border-stone-200
                            rounded-lg shadow-sm text-center
                            py-20 px-6">

                    <h3 class="text-xl font-bold text-stone-800 mb-3"
                        style="font-family: Georgia, serif;">
                        Chưa có đơn hàng nào
                    </h3>

                    <p class="text-sm text-stone-500 mb-7">
                        Bạn chưa có đơn hàng trong trạng thái này.
                    </p>

                    <a href="{{ route('products.index') }}"
                       class="inline-block px-8 py-3
                              bg-red-800 hover:bg-red-900
                              text-white font-bold text-xs
                              uppercase tracking-widest transition">

                        Khám phá sản phẩm

                    </a>

                </div>

            @endforelse

        </div>

        {{-- ================= PHÂN TRANG ================= --}}
        @if($orders->hasPages())

            <div class="mt-10">
                {{ $orders->links() }}
            </div>

        @endif

    </div>

</section>

{{-- ================= SWEETALERT2 ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Tự động ẩn thông báo sau 3 giây
    ['success-alert', 'error-alert'].forEach(function (id) {

        const alert = document.getElementById(id);

        if (!alert) return;

        setTimeout(function () {

            alert.classList.add('opacity-0');

            setTimeout(function () {
                alert.remove();
            }, 500);

        }, 3000);
    });

    // Xác nhận hủy đơn hàng
    document.querySelectorAll('.cancel-order-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({
                    title: 'Xác nhận hủy đơn hàng?',
                    text: 'Bạn có chắc chắn muốn hủy đơn hàng này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#991b1b',
                    cancelButtonColor: '#78716c',
                    confirmButtonText: 'Đồng ý hủy',
                    cancelButtonText: 'Quay lại'
                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });
            });
        });

    // Xác nhận đã nhận hàng
    document.querySelectorAll('.receive-order-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({
                    title: 'Xác nhận đã nhận hàng?',
                    text: 'Bạn xác nhận đã nhận được đơn hàng này?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#991b1b',
                    cancelButtonColor: '#78716c',
                    confirmButtonText: 'Đã nhận hàng',
                    cancelButtonText: 'Quay lại'
                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });
            });
        });

        // ================= XÁC NHẬN MUA LẠI =================
document.querySelectorAll('.reorder-form')
    .forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            Swal.fire({
                title: 'Xác nhận mua lại?',
                text: 'Sản phẩm sẽ được thêm vào giỏ hàng và chuyển đến trang xác nhận đơn hàng. Giá và tồn kho được tính theo thời điểm hiện tại.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#991b1b',
                cancelButtonColor: '#78716c',
                confirmButtonText: 'Mua lại',
                cancelButtonText: 'Quay lại',
                reverseButtons: true
            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

    });
});
</script>

@endsection

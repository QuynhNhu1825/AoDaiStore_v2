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

            <h1 class="serif text-3xl font-bold text-stone-800 uppercase tracking-widest">
                ĐƠN HÀNG CỦA TÔI
            </h1>

            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>

            <p class="text-stone-500 text-sm mt-4">
                Theo dõi và quản lý các đơn hàng của bạn
            </p>

        </div>


        @if(session('success'))
            <div id="success-alert" role="alert" class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg transition-opacity duration-500">{{ session('success') }}</div>
        @endif

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

        {{-- ================= TAB TRẠNG THÁI ================= --}}
        <div class="bg-white border border-stone-200 rounded-lg shadow-sm mb-7 overflow-hidden">
            {{-- Desktop: 6 cột bằng nhau. Mobile: cuộn ngang, tab có cùng chiều rộng. --}}
            <div class="grid grid-flow-col auto-cols-[minmax(145px,1fr)] lg:grid-flow-row lg:grid-cols-6 overflow-x-auto lg:overflow-x-visible">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('order.history', ['status' => $key]) }}"
                       @if($currentStatus === $key) aria-current="page" @endif
                       class="relative flex items-center justify-center min-h-[64px] px-3 py-4
                              text-sm font-semibold text-center whitespace-nowrap transition-colors
                              {{ $currentStatus === $key
                                  ? 'text-red-800 bg-red-50'
                                  : 'text-stone-600 hover:text-red-800 hover:bg-stone-50' }}">
                        {{ $label }}
                        @if($currentStatus === $key)
                            <span class="absolute bottom-0 left-0 right-0 h-[3px] bg-red-800"></span>
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

                    
                    {{-- Yêu cầu đang chờ duyệt: luôn hiển thị vì vẫn có hiệu lực --}}
                    @if($order->TrangThaiYeuCauHuy === 'ChoDuyet')
                        <div class="mx-4 sm:mx-6 my-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start gap-3">
                            <i class="fas fa-clock mt-0.5"></i>
                            <div>
                                <strong>Yêu cầu hủy đơn hàng đang chờ xét duyệt.</strong>
                                <p class="mt-1">Đơn hàng chưa bị hủy. Cửa hàng sẽ cập nhật kết quả sau khi xem xét.</p>
                                @if($order->NgayYeuCauHuy)
                                    <p class="mt-1 text-xs">Gửi lúc: {{ \Carbon\Carbon::parse($order->NgayYeuCauHuy)->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    @elseif(in_array($order->TrangThaiYeuCauHuy, ['DaDuyet', 'TuChoi'], true))
                        {{-- Kết quả xử lý: chỉ hiện nếu chưa bấm Đã hiểu trên trình duyệt này --}}
                        <div class="cancel-result-notice hidden mx-4 sm:mx-6 my-4 p-4 rounded-xl border text-sm
                            {{ $order->TrangThaiYeuCauHuy === 'DaDuyet'
                                ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                                : 'bg-stone-50 border-stone-200 text-stone-700' }}"
                             data-user-id="{{ auth()->id() }}"
                             data-order-id="{{ $order->MaDonHang }}"
                             data-result="{{ $order->TrangThaiYeuCauHuy }}"
                             data-reviewed-at="{{ $order->NgayXuLyYeuCauHuy ? \Carbon\Carbon::parse($order->NgayXuLyYeuCauHuy)->timestamp : '0' }}"
                             role="status">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <i class="fas fa-info-circle mt-0.5 shrink-0"></i>
                                    <div class="leading-relaxed">
                                        <strong>Kết quả yêu cầu hủy đơn #{{ $order->MaDonHang }}</strong>
                                        <p class="mt-1">
                                            @if($order->TrangThaiYeuCauHuy === 'DaDuyet')
                                                @if($order->PhuongThucThanhToan === 'vietqr' && $order->TrangThaiThanhToan === 'DaThanhToan')
                                                    Yêu cầu hủy đã được chấp thuận. Đơn hàng đã được hủy. Số tiền đã thanh toán qua VietQR sẽ được cửa hàng hoàn trả thủ công trong vòng 1–2 ngày làm việc. Vui lòng kiểm tra tài khoản ngân hàng của bạn. Hệ thống không tự động chuyển tiền.
                                                @else
                                                    Yêu cầu hủy đã được chấp thuận. Đơn hàng đã được hủy thành công.
                                                @endif
                                            @elseif($orderStatus === 'DaGiao')
                                                Yêu cầu hủy trước đó không được chấp thuận. Đơn hàng hiện đã được giao.
                                            @elseif($orderStatus === 'DangGiao')
                                                Yêu cầu hủy chưa được chấp thuận. Đơn hàng hiện đang được vận chuyển.
                                            @else
                                                Yêu cầu hủy chưa được chấp thuận. Đơn hàng tiếp tục được xử lý.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <button type="button" class="dismiss-cancel-notice shrink-0 px-3 py-1.5 rounded-lg border border-current/20 font-semibold hover:bg-white/70 transition" aria-label="Đánh dấu đã xem thông báo hủy đơn">
                                    Đã hiểu
                                </button>
                            </div>
                        </div>
                    @endif

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

                                // Lấy tên ảnh từ cột HinhAnh
                                $imagePath = $product?->HinhAnh;

                                // Chuẩn hóa đường dẫn ảnh
                                if (!empty($imagePath)) {

                                    // Nếu là đường dẫn ảnh bên ngoài
                                    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {

                                        $imageUrl = $imagePath;

                                    } else {

                                        // Chỉ lấy tên file ảnh
                                        $fileName = basename($imagePath);

                                        // Ảnh nằm trong public/img/products/
                                        $imageUrl = asset('img/products/' . $fileName);
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

                                    @if($product)
                                        <a href="{{ route('product.detail', $product->MaSanPham) }}"
                                           class="block w-full h-full group"
                                           title="Xem chi tiết {{ $productName }}">
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $productName }}"
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                 onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}'">
                                        </a>
                                    @else
                                        <img src="{{ $imageUrl }}"
                                             alt="{{ $productName }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}'">
                                    @endif

                                </div>

                                {{-- THÔNG TIN SẢN PHẨM --}}
                                <div class="flex-1 min-w-0">

                                    <h4 class="serif font-bold text-stone-900
                                       text-base sm:text-lg uppercase
                                       leading-relaxed tracking-wide">
                                        @if($product)
                                            <a href="{{ route('product.detail', $product->MaSanPham) }}"
                                               class="hover:text-red-800 underline-offset-4 transition-colors"
                                               title="Xem chi tiết {{ $productName }}">
                                                {{ $productName }}
                                            </a>
                                        @else
                                            <span class="text-stone-400">{{ $productName }}</span>
                                        @endif
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

                            @if($order->canPayWithVietQr())
                                <a href="{{ route('vietqr.show', $order->MaDonHang) }}"
                                   class="inline-flex items-center justify-center px-5 py-2.5
                                          bg-red-800 text-white hover:bg-red-900
                                          text-xs font-bold uppercase tracking-wide transition">
                                    Tiếp tục thanh toán VietQR
                                </a>
                            @elseif($orderStatus !== 'DaHuy' && $order->PhuongThucThanhToan === 'vietqr' && $order->TrangThaiThanhToan === 'ChoXacNhanThanhToan')
                                <span class="self-center text-xs font-semibold text-amber-800">
                                    VietQR đang chờ xác nhận
                                </span>
                            @endif

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
                            @if($order->canCancel() && ($order->TrangThaiThanhToan !== 'DaThanhToan' || $orderStatus === 'DaXacNhan'))

                                <form action="{{ route('order.cancel', $order->MaDonHang) }}"
                                      method="POST"
                                      class="cancel-order-form" data-request="{{ $orderStatus === 'DaXacNhan' ? '1' : '0' }}">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="px-5 py-2.5
                                                   border border-red-800
                                                   text-red-800 bg-white
                                                   hover:bg-red-800 hover:text-white
                                                   text-xs font-bold uppercase
                                                   tracking-wide transition">

                                        {{ $orderStatus === 'DaXacNhan' ? 'Yêu cầu hủy đơn' : 'Hủy đơn hàng' }}

                                    </button>

                                </form>

                            @endif

                            {{-- MUA LẠI --}}
                    @if(in_array($orderStatus, ['DaGiao', 'DaHuy'], true))

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

                            {{-- ĐÁNH GIÁ SẢN PHẨM: chỉ hiện nếu còn sản phẩm chưa đánh giá --}}
                            @if($orderStatus === 'DaGiao')
                                @php
                                    // Mỗi sản phẩm được đánh giá một lần trong mỗi đơn hàng.
                                    // Nhiều biến thể của cùng sản phẩm chỉ tính một lần.
                                    $productIds = $details
                                        ->map(fn ($detail) => $detail->productDetail?->MaSanPham)
                                        ->filter(fn ($id) => $id !== null)
                                        ->map(fn ($id) => (int) $id)
                                        ->unique()
                                        ->values()
                                        ->all();

                                    $reviewedIds = $reviewedByOrder->get($order->MaDonHang, []);
                                    $hasUnreviewedProducts = count(array_diff($productIds, $reviewedIds)) > 0;
                                @endphp

                                @if($hasUnreviewedProducts)
                                    <a href="{{ route('order.show', $order->MaDonHang) }}"
                                       class="inline-flex items-center justify-center
                                              px-5 py-2.5 bg-red-800
                                              text-white hover:bg-red-900
                                              text-xs font-bold uppercase
                                              tracking-wide transition">
                                        <i class="fas fa-star mr-2"></i>
                                        Đánh giá sản phẩm
                                    </a>
                                @endif
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

    // Chỉ hiển thị kết quả xét duyệt hủy một lần trên trình duyệt này.
    // Khóa dùng chung với trang chi tiết đơn hàng.
    document.querySelectorAll('.cancel-result-notice').forEach(function (notice) {
        const key = [
            'heritage_cancel_notice',
            notice.dataset.userId,
            notice.dataset.orderId,
            notice.dataset.result,
            notice.dataset.reviewedAt
        ].join('_');

        let seen = false;
        try { seen = localStorage.getItem(key) === 'seen'; } catch (error) {}
        if (seen) return;

        notice.classList.remove('hidden');
        const dismiss = notice.querySelector('.dismiss-cancel-notice');
        if (!dismiss) return;
        dismiss.addEventListener('click', function () {
            try { localStorage.setItem(key, 'seen'); } catch (error) {}
            notice.classList.add('hidden');
        });
    });


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
                    title: form.dataset.request === '1' ? 'Gửi yêu cầu hủy?' : 'Xác nhận hủy đơn hàng?',
                    text: form.dataset.request === '1' ? 'Yêu cầu sẽ được gửi đến Admin xét duyệt. Đơn hàng chưa bị hủy ngay.' : 'Bạn có chắc chắn muốn hủy đơn hàng này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#991b1b',
                    cancelButtonColor: '#78716c',
                    confirmButtonText: form.dataset.request === '1' ? 'Gửi yêu cầu' : 'Đồng ý hủy',
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

@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->MaDonHang)

@section('content')

@php
    $statusLabels = [
        'ChoXacNhan' => 'Chờ xác nhận',
        'DaXacNhan' => 'Đã xác nhận',
        'DangGiao' => 'Đang giao',
        'DaGiao' => 'Đã giao',
        'DaHuy' => 'Đã hủy',
    ];

    $statusColors = [
        'ChoXacNhan' => 'bg-amber-50 text-amber-700',
        'DaXacNhan' => 'bg-blue-50 text-blue-700',
        'DangGiao' => 'bg-indigo-50 text-indigo-700',
        'DaGiao' => 'bg-green-50 text-green-700',
        'DaHuy' => 'bg-red-50 text-red-700',
    ];

    $paymentLabels = [
        'ChuaThanhToan' => 'Chưa thanh toán',
        'ChoXacNhanThanhToan' => 'Chờ xác nhận thanh toán',
        'DaThanhToan' => 'Đã thanh toán',
    ];

    $canConfirmPayment =
        $order->PhuongThucThanhToan === 'vietqr' &&
        $order->TrangThaiThanhToan === 'ChoXacNhanThanhToan' &&
        $order->TrangThai === 'ChoXacNhan';

    $canConfirmOrder =
        $order->TrangThai === 'ChoXacNhan' &&
        $order->PhuongThucThanhToan === 'cod';

    $canCancel =
        in_array($order->TrangThai, ['ChoXacNhan', 'DaXacNhan'], true) &&
        $order->TrangThaiThanhToan !== 'DaThanhToan';

    $total = (float) $order->TienHang
           + (float) $order->PhiVanChuyen;
@endphp

<div class="space-y-6">

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="admin-order-alert flex items-center gap-3
                    bg-green-50 border border-green-200
                    text-green-700 p-4 rounded-xl"
             role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="admin-order-alert flex items-center gap-3
                    bg-red-50 border border-red-200
                    text-red-700 p-4 rounded-xl"
             role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center gap-2 text-xs font-bold
                      text-stone-500 hover:text-red-900 transition">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách
            </a>

            <h2 class="text-2xl font-bold text-stone-800 mt-3">
                Chi tiết đơn hàng #{{ $order->MaDonHang }}
            </h2>

            <div class="flex flex-wrap items-center gap-3 mt-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    {{ $statusColors[$order->TrangThai] ?? 'bg-stone-100 text-stone-600' }}">
                    {{ $statusLabels[$order->TrangThai] ?? $order->TrangThai }}
                </span>

                <span class="text-xs text-stone-500">
                    <i class="far fa-calendar-alt mr-1"></i>
                    {{ $order->NgayDat
                        ? \Carbon\Carbon::parse($order->NgayDat)->format('d/m/Y H:i')
                        : '—' }}
                </span>
            </div>
        </div>

        <button type="button"
                onclick="window.print()"
                class="px-5 py-3 bg-white border border-stone-200
                       text-stone-600 rounded-xl text-sm font-bold
                       hover:bg-stone-50 transition">
            <i class="fas fa-print mr-2"></i>
            In hóa đơn
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CỘT TRÁI --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- DANH SÁCH SẢN PHẨM --}}
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">

                <div class="px-6 py-5 border-b border-stone-100 flex justify-between items-center">
                    <h3 class="font-bold text-stone-800">
                        <i class="fas fa-shopping-bag text-red-900 mr-2"></i>
                        Sản phẩm trong đơn
                    </h3>

                    <span class="text-xs text-stone-500">
                        {{ $orderDetails->count() }} mặt hàng
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-stone-50 text-stone-500 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-4">Sản phẩm</th>
                                <th class="px-4 py-4">Màu / Size</th>
                                <th class="px-4 py-4 text-center">SL</th>
                                <th class="px-4 py-4 text-right">Đơn giá</th>
                                <th class="px-5 py-4 text-right">Thành tiền</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @forelse($orderDetails as $item)
                                @php
                                    $variant = $item->productDetail;
                                    $product = $variant?->product;
                                    $color = $variant?->color;
                                    $size = $variant?->size;

                                    $lineTotal = (float) $item->DonGia
                                               * (int) $item->SoLuong;

                                    $productName = $item->TenSanPham
                                        ?: ($product?->TenSanPham ?? 'Sản phẩm');

                                    $imagePath = $product?->HinhAnh
                                        ? asset('img/products/' . $product->HinhAnh)
                                        : asset('img/no-image.png');
                                @endphp

                                <tr>
                                    <td class="px-5 py-5">
                                        <div class="flex items-center gap-3">
                                            <img
                                                src="{{ $imagePath }}"
                                                alt="{{ $productName }}"
                                                class="w-14 h-16 object-cover rounded-lg bg-stone-100"
                                                onerror="this.onerror=null;this.style.display='none';"
                                            >

                                            <div>
                                                <p class="font-bold text-stone-800">
                                                    {{ $productName }}
                                                </p>
                                                <p class="text-xs text-stone-400 mt-1">
                                                    Biến thể #{{ $item->MaChiTietSP }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-5 text-xs text-stone-600">
                                        <p>
                                            Màu: {{ $color?->TenMau ?? '—' }}
                                        </p>
                                        <p class="mt-1">
                                            Size: {{ $size?->TenSize ?? '—' }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-5 text-center font-bold text-stone-800">
                                        {{ $item->SoLuong }}
                                    </td>

                                    <td class="px-4 py-5 text-right whitespace-nowrap">
                                        {{ number_format($item->DonGia, 0, ',', '.') }}đ
                                    </td>

                                    <td class="px-5 py-5 text-right font-bold text-red-900 whitespace-nowrap">
                                        {{ number_format($lineTotal, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-stone-400">
                                        Không có sản phẩm trong đơn hàng.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TỔNG TIỀN --}}
                <div class="bg-stone-50 p-6 border-t border-stone-100">
                    <div class="max-w-sm ml-auto space-y-3">

                        <div class="flex justify-between text-sm text-stone-600">
                            <span>Tiền hàng</span>
                            <span>
                                {{ number_format($order->TienHang, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex justify-between text-sm text-stone-600">
                            <span>Phí vận chuyển</span>
                            <span>
                                {{ number_format($order->PhiVanChuyen, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex justify-between pt-4 border-t border-stone-200">
                            <span class="font-bold text-stone-800">
                                Tổng thanh toán
                            </span>

                            <span class="text-xl font-black text-red-900">
                                {{ number_format($total, 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- XỬ LÝ ĐƠN HÀNG --}}
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
                <h3 class="font-bold text-stone-800 mb-5">
                    <i class="fas fa-tasks text-red-900 mr-2"></i>
                    Xử lý đơn hàng
                </h3>

                <div class="flex flex-wrap items-center gap-3">

                    {{-- COD: XÁC NHẬN ĐƠN --}}
                    @if($canConfirmOrder)
                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="status" value="DaXacNhan">

                            <button type="submit"
                                    class="px-5 py-3 bg-green-700 hover:bg-green-800
                                           text-white rounded-xl text-sm font-bold transition">
                                <i class="fas fa-check mr-2"></i>
                                Xác nhận đơn
                            </button>
                        </form>
                    @endif

                    {{-- ĐANG GIAO --}}
                    @if($order->TrangThai === 'DaXacNhan')
                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="status" value="DangGiao">

                            <button type="submit"
                                    class="px-5 py-3 bg-indigo-700 hover:bg-indigo-800
                                           text-white rounded-xl text-sm font-bold transition">
                                <i class="fas fa-truck mr-2"></i>
                                Bắt đầu giao hàng
                            </button>
                        </form>
                    @endif

                    {{-- ĐÃ GIAO --}}
                    @if($order->TrangThai === 'DangGiao')
                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                              method="POST"
                              onsubmit="return confirm('Xác nhận đơn hàng đã được giao thành công?')">
                            @csrf
                            <input type="hidden" name="status" value="DaGiao">

                            <button type="submit"
                                    class="px-5 py-3 bg-blue-700 hover:bg-blue-800
                                           text-white rounded-xl text-sm font-bold transition">
                                <i class="fas fa-box-open mr-2"></i>
                                Xác nhận đã giao
                            </button>
                        </form>
                    @endif

                    {{-- HỦY ĐƠN --}}
                    @if($canCancel)
                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                              method="POST"
                              onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này? Sản phẩm sẽ được hoàn kho.')">
                            @csrf
                            <input type="hidden" name="status" value="DaHuy">

                            <button type="submit"
                                    class="px-5 py-3 bg-red-50 hover:bg-red-100
                                           border border-red-200 text-red-700
                                           rounded-xl text-sm font-bold transition">
                                <i class="fas fa-times mr-2"></i>
                                Hủy đơn hàng
                            </button>
                        </form>
                    @endif

                    {{-- THÔNG BÁO VIETQR CHƯA THANH TOÁN --}}
                    @if(
                        $order->TrangThai === 'ChoXacNhan' &&
                        $order->PhuongThucThanhToan === 'vietqr' &&
                        $order->TrangThaiThanhToan === 'ChuaThanhToan'
                    )
                        <div class="w-full p-4 bg-amber-50 border border-amber-200
                                    rounded-xl text-sm text-amber-800">
                            <i class="fas fa-clock mr-2"></i>
                            Đang chờ khách hàng thông báo chuyển khoản.
                        </div>
                    @endif

                    @if(
                        $order->TrangThai === 'ChoXacNhan' &&
                        $order->PhuongThucThanhToan === 'vietqr' &&
                        $order->TrangThaiThanhToan === 'ChoXacNhanThanhToan'
                    )
                        <div class="w-full p-4 bg-blue-50 border border-blue-200
                                    rounded-xl text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Khách đã thông báo chuyển khoản.
                            Vui lòng kiểm tra và xác nhận thanh toán.
                        </div>
                    @endif

                    @if(in_array($order->TrangThai, ['DaGiao', 'DaHuy'], true))
                        <p class="text-sm text-stone-500 italic">
                            Đơn hàng đã kết thúc xử lý.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI --}}
        <div class="space-y-6">

            {{-- THÔNG TIN KHÁCH HÀNG --}}
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
                <h3 class="font-bold text-stone-800 mb-5">
                    <i class="fas fa-user-circle text-red-900 mr-2"></i>
                    Thông tin khách hàng
                </h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-xs text-stone-400 mb-1">Người nhận</p>
                        <p class="font-semibold text-stone-800">
                            {{ $order->HoTenNguoiNhan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-400 mb-1">Số điện thoại</p>
                        <p class="font-semibold text-stone-800">
                            {{ $order->SDTNguoiNhan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-400 mb-1">Địa chỉ giao hàng</p>
                        <p class="text-stone-700 leading-relaxed">
                            {{ $order->DiaChiNhan }}
                        </p>
                    </div>

                    @if($order->GhiChu)
                        <div>
                            <p class="text-xs text-stone-400 mb-1">Ghi chú</p>
                            <p class="text-stone-700">
                                {{ $order->GhiChu }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- THANH TOÁN --}}
            <div class="bg-stone-900 text-white rounded-2xl shadow-lg p-6">

                <h3 class="font-bold mb-6">
                    <i class="fas fa-credit-card mr-2"></i>
                    Thông tin thanh toán
                </h3>

                <div class="space-y-5">

                    <div>
                        <p class="text-xs text-stone-400 mb-2">
                            Phương thức thanh toán
                        </p>

                        <p class="font-semibold">
                            @if($order->PhuongThucThanhToan === 'vietqr')
                                <i class="fas fa-qrcode mr-2"></i>
                                Chuyển khoản VietQR
                            @else
                                <i class="fas fa-money-bill-wave mr-2"></i>
                                Thanh toán khi nhận hàng (COD)
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-400 mb-2">
                            Tổng tiền
                        </p>
                        <p class="text-2xl font-black">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-400 mb-2">
                            Trạng thái thanh toán
                        </p>

                        @if($order->TrangThaiThanhToan === 'DaThanhToan')
                            <p class="font-bold text-green-400">
                                <i class="fas fa-check-circle mr-2"></i>
                                Đã thanh toán
                            </p>

                            @if($order->NgayThanhToan)
                                <p class="text-xs text-stone-400 mt-2">
                                    Xác nhận lúc:
                                    {{ \Carbon\Carbon::parse($order->NgayThanhToan)->format('d/m/Y H:i') }}
                                </p>
                            @endif

                        @elseif($order->TrangThaiThanhToan === 'ChoXacNhanThanhToan')
                            <p class="font-bold text-blue-400">
                                <i class="fas fa-clock mr-2"></i>
                                Chờ xác nhận thanh toán
                            </p>
                            <p class="text-xs text-stone-400 mt-2">
                                Khách hàng đã thông báo chuyển khoản.
                            </p>

                        @else
                            <p class="font-bold text-amber-400">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Chưa thanh toán
                            </p>
                        @endif
                    </div>

                    {{-- XÁC NHẬN VIETQR --}}
                    @if($canConfirmPayment)
                        <div class="border-t border-stone-700 pt-5">

                            <p class="text-xs text-stone-300 leading-relaxed mb-4">
                                Vui lòng kiểm tra giao dịch trước khi xác nhận.
                                Sau khi xác nhận thanh toán, đơn hàng sẽ tự động
                                chuyển sang trạng thái Đã xác nhận.
                            </p>

                            <form action="{{ route('orders.confirmPayment', $order->MaDonHang) }}"
                                  method="POST"
                                  onsubmit="return confirm('Bạn đã kiểm tra giao dịch và muốn xác nhận thanh toán, đồng thời xác nhận đơn hàng này?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        class="w-full px-4 py-3 bg-green-700 hover:bg-green-800
                                               text-white rounded-xl text-sm font-bold transition">
                                    <i class="fas fa-check-double mr-2"></i>
                                    Xác nhận thanh toán & đơn hàng
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.admin-order-alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';

            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 4000);
    });
});
</script>

@endsection

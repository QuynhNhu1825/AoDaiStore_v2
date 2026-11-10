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

<div class="max-w-7xl mx-auto pb-10 space-y-5 admin-order-show">

    {{-- Thông báo kết quả sẽ hiển thị bằng toast SweetAlert2 --}}

    {{-- QUAY LẠI --}}
    <a href="{{ route('orders.index') }}"
       class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-stone-500 hover:text-red-800 transition">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
    </a>

    {{-- KHUNG CHI TIẾT ĐỒNG BỘ VỚI QUẢN LÝ SẢN PHẨM --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-5 border-b border-red-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-red-900">Chi tiết đơn hàng</h2>
                <p class="text-xs text-stone-400 mt-1">Thông tin khách hàng, sản phẩm và tiến trình xử lý đơn</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$order->TrangThai] ?? 'bg-stone-100 text-stone-600' }}">
                        {{ $statusLabels[$order->TrangThai] ?? $order->TrangThai }}
                    </span>
                    <span class="text-xs text-stone-500">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ $order->NgayDat ? \Carbon\Carbon::parse($order->NgayDat)->format('d/m/Y H:i') : '—' }}
                    </span>
                </div>
            </div>
            <div class="flex items-center flex-wrap gap-2 shrink-0">
                <span class="text-xs font-bold text-red-800 bg-red-50 px-3 py-2 rounded-lg">#{{ $order->MaDonHang }}</span>
                <button type="button" onclick="printSaleReceipt()"
                        class="px-4 py-2.5 bg-stone-100 text-stone-700 rounded-lg text-xs font-bold hover:bg-stone-200 transition print:hidden">
                    <i class="fas fa-print mr-2"></i> In phiếu bán hàng
                </button>
            </div>
        </div>

        <div class="p-4 sm:p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">

        {{-- CỘT TRÁI --}}
        <div class="lg:col-span-2 min-w-0 space-y-5">

            {{-- DANH SÁCH SẢN PHẨM --}}
            <div class="bg-white rounded-xl border border-red-100 overflow-hidden">

                <div class="px-4 sm:px-5 py-4 border-b border-red-100 flex justify-between items-center">
                    <h3 class="font-bold text-red-900">
                        <i class="fas fa-shopping-bag text-red-900 mr-2"></i>
                        Sản phẩm trong đơn
                    </h3>

                    <span class="text-xs text-stone-500">
                        {{ $orderDetails->count() }} mặt hàng
                    </span>
                </div>

                <div class="overflow-x-auto max-w-full">
                    <table class="w-full min-w-[660px] text-left text-xs sm:text-sm">
                        <thead class="bg-red-50 text-red-900 text-[10px] font-bold uppercase">
                            <tr>
                                <th class="px-5 py-4">Sản phẩm</th>
                                <th class="px-4 py-4">Màu / Size</th>
                                <th class="px-4 py-4 text-center">SL</th>
                                <th class="px-4 py-4 text-right">Đơn giá</th>
                                <th class="px-5 py-4 text-right">Thành tiền</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-red-50">
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
                                    <td class="px-4 py-4">
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
                <div class="bg-red-50/50 p-4 sm:p-5 border-t border-red-100">
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
            <div class="bg-white rounded-xl border border-red-100 p-4 sm:p-5">
                <h3 class="font-bold text-red-900 mb-5">
                    <i class="fas fa-tasks text-red-900 mr-2"></i>
                    Xử lý đơn hàng
                </h3>

                <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3">

                    {{-- COD: XÁC NHẬN ĐƠN --}}
                    @if($canConfirmOrder)
                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="status" value="DaXacNhan">

                            <button type="submit"
                                    class="w-full sm:w-auto px-5 py-3 bg-green-700 hover:bg-green-800
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
                                    class="w-full sm:w-auto px-5 py-3 bg-indigo-700 hover:bg-indigo-800
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
                              data-confirm-title="Xác nhận đã giao hàng?"
                               data-confirm-text="Đơn hàng sẽ chuyển sang trạng thái Đã giao. Với COD, hệ thống sẽ ghi nhận đã thanh toán theo nghiệp vụ hiện tại."
                               data-confirm-icon="question">
                            @csrf
                            <input type="hidden" name="status" value="DaGiao">

                            <button type="submit"
                                    class="w-full sm:w-auto px-5 py-3 bg-blue-700 hover:bg-blue-800
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
                              data-confirm-title="Hủy đơn hàng?"
                               data-confirm-text="Bạn có chắc muốn hủy đơn hàng này? Số lượng sản phẩm sẽ được hoàn kho theo xử lý hiện tại."
                               data-confirm-icon="warning">
                            @csrf
                            <input type="hidden" name="status" value="DaHuy">

                            <button type="submit"
                                    class="w-full sm:w-auto px-5 py-3 bg-red-50 hover:bg-red-100
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
        <div class="min-w-0 space-y-5">

            {{-- THÔNG TIN KHÁCH HÀNG --}}
            <div class="bg-white rounded-xl border border-red-100 p-4 sm:p-5">
                <h3 class="font-bold text-red-900 mb-5">
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
            <div class="bg-red-950 text-white rounded-xl shadow-sm p-4 sm:p-5">

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
                                  data-confirm-title="Xác nhận thanh toán VietQR?"
                                   data-confirm-text="Chỉ xác nhận khi đã kiểm tra giao dịch. Đơn hàng sẽ chuyển sang Đã xác nhận."
                                   data-confirm-icon="question">
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
    </div>
</div>

{{-- ================= PHIẾU BÁN HÀNG A4 ================= --}}
{{-- Phiếu bán hàng nội bộ, không thay thế hóa đơn điện tử hợp lệ. --}}
<section id="print-invoice" aria-label="Phiếu bán hàng">
    <div class="invoice-topline"></div>
    <div class="invoice-header">
        <div>
            <div class="invoice-brand">ÁO DÀI HERITAGE</div>
            <div class="invoice-muted">Nét đẹp truyền thống Việt</div>
        </div>
        <div class="invoice-header-right">
            <h1>PHIẾU BÁN HÀNG</h1>
            <div class="invoice-muted">Mã đơn: <strong>#{{ $order->MaDonHang }}</strong></div>
            <div class="invoice-muted">Ngày đặt: {{ $order->NgayDat ? \Carbon\Carbon::parse($order->NgayDat)->format('d/m/Y H:i') : '—' }}</div>
        </div>
    </div>

    <div class="invoice-info-grid">
        <div class="invoice-info-box">
            <h2>THÔNG TIN NGƯỜI NHẬN</h2>
            <p><strong>{{ $order->HoTenNguoiNhan }}</strong></p>
            <p>Điện thoại: {{ $order->SDTNguoiNhan }}</p>
            <p>Địa chỉ: {{ $order->DiaChiNhan ?: '—' }}</p>
            @if($order->GhiChu)
                <p>Ghi chú: {{ $order->GhiChu }}</p>
            @endif
        </div>
        <div class="invoice-info-box">
            <h2>THÔNG TIN ĐƠN HÀNG</h2>
            <p>Trạng thái: <strong>{{ $statusLabels[$order->TrangThai] ?? $order->TrangThai }}</strong></p>
            <p>Thanh toán: {{ $order->PhuongThucThanhToan === 'vietqr' ? 'Chuyển khoản VietQR' : 'COD (khi nhận hàng)' }}</p>
            <p>Trạng thái thanh toán: <strong>{{ $paymentLabels[$order->TrangThaiThanhToan] ?? 'Không xác định' }}</strong></p>
            @if($order->NgayThanhToan)
                <p>Ngày thanh toán: {{ \Carbon\Carbon::parse($order->NgayThanhToan)->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    </div>

    <h2 class="invoice-section-title">CHI TIẾT SẢN PHẨM</h2>
    <table class="invoice-items">
        <thead>
            <tr>
                <th class="invoice-center">STT</th>
                <th>Sản phẩm</th>
                <th class="invoice-center">SL</th>
                <th class="invoice-number">Đơn giá</th>
                <th class="invoice-number">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orderDetails as $item)
                @php
                    $printVariant = $item->productDetail;
                    $printProductName = $item->TenSanPham ?: ($printVariant?->product?->TenSanPham ?? 'Sản phẩm');
                    $printColor = $printVariant?->color?->TenMau;
                    $printSize = $printVariant?->size?->TenSize;
                @endphp
                <tr>
                    <td class="invoice-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $printProductName }}</strong>
                        @if($printColor || $printSize)
                            <div class="invoice-muted invoice-variant">
                                {{ $printColor ? 'Màu: ' . $printColor : '' }}{{ $printColor && $printSize ? ' · ' : '' }}{{ $printSize ? 'Size: ' . $printSize : '' }}
                            </div>
                        @endif
                    </td>
                    <td class="invoice-center">{{ $item->SoLuong }}</td>
                    <td class="invoice-number">{{ number_format($item->DonGia, 0, ',', '.') }} đ</td>
                    <td class="invoice-number"><strong>{{ number_format((float)$item->DonGia * (int)$item->SoLuong, 0, ',', '.') }} đ</strong></td>
                </tr>
            @empty
                <tr><td colspan="5" class="invoice-center">Không có sản phẩm</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="invoice-summary">
        <div class="invoice-summary-row"><span>Tiền hàng</span><span>{{ number_format($order->TienHang, 0, ',', '.') }} đ</span></div>
        <div class="invoice-summary-row"><span>Phí vận chuyển</span><span>{{ number_format($order->PhiVanChuyen, 0, ',', '.') }} đ</span></div>
        <div class="invoice-summary-row invoice-grand-total"><strong>TỔNG THANH TOÁN</strong><strong>{{ number_format($total, 0, ',', '.') }} đ</strong></div>
    </div>

    <div class="invoice-note">Cảm ơn quý khách đã lựa chọn Áo Dài Heritage!</div>
    <div class="invoice-footnote">Phiếu bán hàng được lập từ hệ thống Áo Dài Heritage, không thay thế hóa đơn điện tử theo quy định.</div>
</section>

<style id="sale-receipt-print-style">
#print-invoice { display: none; }
@page { size: A4 portrait; margin: 10mm 12mm; }
@media print {
    body, html { background: white !important; }
    body * { visibility: hidden !important; }
    #print-invoice, #print-invoice * { visibility: visible !important; }
    #print-invoice {
        display: block !important;
        position: static; width: 100%;
        margin: 0; padding: 0; color: #242424;
        font-family: Arial, sans-serif; font-size: 10.5px; line-height: 1.4;
        background: white !important;
    }
    .invoice-topline { height: 5px; background: #831c30 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
    .invoice-header { display: flex; justify-content: space-between; gap: 20px; padding: 12px 0 14px; border-bottom: 1px solid #d9d9d9; }
    .invoice-brand { font-size: 21px; font-weight: 800; color: #831c30; letter-spacing: 1px; }
    .invoice-muted { color: #666; }
    .invoice-header-right { text-align: right; }
    .invoice-header-right h1 { margin: 0 0 5px; font-size: 16px; font-weight: 800; color: #831c30; }
    .invoice-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 14px 0 16px; }
    .invoice-info-box { padding: 10px 12px; border: 1px solid #e5e5e5; border-radius: 5px; overflow-wrap: anywhere; }
    .invoice-info-box h2, .invoice-section-title { margin: 0 0 9px; color: #831c30; font-size: 11px; font-weight: 800; letter-spacing: .6px; }
    .invoice-info-box p { margin: 4px 0; }
    .invoice-section-title { margin-top: 10px; }
    .invoice-items { width: 100%; border-collapse: collapse; table-layout: auto; }
    .invoice-items th { background: #f8eef0 !important; color: #641527; padding: 7px 7px; border-bottom: 1px solid #dfc6cb; text-align: left; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
    .invoice-items td { padding: 7px 7px; border-bottom: 1px solid #e8e8e8; vertical-align: top; }
    .invoice-items tr { break-inside: avoid; page-break-inside: avoid; }
    .invoice-items th:first-child { width: 38px; }
    .invoice-center, .invoice-items th.invoice-center { text-align: center; }
    .invoice-number, .invoice-items th.invoice-number { text-align: right; white-space: nowrap; }
    .invoice-variant { font-size: 10px; margin-top: 3px; }
    .invoice-summary { width: 52%; margin: 12px 0 0 auto; break-inside: avoid; }
    .invoice-summary-row { display: flex; justify-content: space-between; gap: 12px; padding: 7px 0; }
    .invoice-grand-total { margin-top: 7px; border-top: 2px solid #831c30; color: #831c30; font-size: 13px; }
    .invoice-info-grid, .invoice-header, .invoice-summary { break-inside: avoid; page-break-inside: avoid; }
    .invoice-note { text-align: center; font-weight: 700; color: #831c30; margin-top: 20px; }
    .invoice-footnote { text-align: center; font-size: 9px; color: #777; margin-top: 5px; }
}
</style>


<script>
// In trong iframe riêng: loại bỏ hoàn toàn sidebar/header và tránh trang trắng thừa.
function printSaleReceipt() {
    const receipt = document.getElementById('print-invoice');
    const style = document.getElementById('sale-receipt-print-style');
    if (!receipt || !style) return;

    const frame = document.createElement('iframe');
    frame.setAttribute('title', 'In phiếu bán hàng');
    frame.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0;';
    document.body.appendChild(frame);

    const doc = frame.contentDocument;
    doc.open();
    doc.write('<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8"><title>Phiếu bán hàng</title>'
        + '<style>html,body{margin:0;padding:0;background:#fff;}*{box-sizing:border-box;}</style>'
        + style.outerHTML
        + '</head><body>' + receipt.outerHTML + '</body></html>');
    doc.close();

    const cleanup = () => { if (frame.isConnected) frame.remove(); };
    frame.contentWindow.addEventListener('afterprint', cleanup, { once: true });
    setTimeout(() => {
        frame.contentWindow.focus();
        frame.contentWindow.print();
    }, 150);
    // Dự phòng khi trình duyệt không phát sự kiện afterprint.
    setTimeout(cleanup, 60000);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swal === 'undefined') return;

    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        showCloseButton: true,
        customClass: { popup: 'heritage-toast' },
        didOpen: (element) => {
            element.addEventListener('mouseenter', Swal.stopTimer);
            element.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    const successMessage = @json(session('success'));
    const errorMessage = @json(session('error'));
    if (successMessage) toast.fire({ icon: 'success', title: successMessage });
    if (errorMessage) toast.fire({ icon: 'error', title: errorMessage });

    document.querySelectorAll('.admin-order-show form[data-confirm-title]').forEach(function (form) {
        form.addEventListener('submit', async function (event) {
            if (form.dataset.confirmed === '1') return;
            event.preventDefault();
            const result = await Swal.fire({
                title: form.dataset.confirmTitle,
                text: form.dataset.confirmText || '',
                icon: form.dataset.confirmIcon || 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Quay lại',
                confirmButtonColor: '#831c30',
                cancelButtonColor: '#78716c',
                reverseButtons: true,
                focusCancel: true,
                customClass: { popup: 'heritage-confirm', confirmButton: 'heritage-confirm-btn' }
            });
            if (result.isConfirmed) {
                form.dataset.confirmed = '1';
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });
});
</script>
<style>
.heritage-toast { border-radius: 14px !important; font-family: inherit !important; font-size: 13px !important; box-shadow: 0 12px 35px rgba(39,15,22,.13) !important; }
.heritage-confirm { border-radius: 18px !important; font-family: inherit !important; }
.heritage-confirm-btn { border-radius: 10px !important; }
</style>

@endsection

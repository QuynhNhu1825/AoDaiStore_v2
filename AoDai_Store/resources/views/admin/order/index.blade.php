@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="space-y-6">

    {{-- TIÊU ĐỀ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-stone-800">
                Quản lý đơn hàng
            </h2>
            <p class="text-sm text-stone-500 mt-1">
                Theo dõi và xử lý đơn hàng của cửa hàng.
            </p>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="admin-order-alert flex items-start gap-3 p-4
                    bg-green-50 border border-green-200
                    text-green-700 rounded-xl"
             role="alert">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="admin-order-alert flex items-start gap-3 p-4
                    bg-red-50 border border-red-200
                    text-red-700 rounded-xl"
             role="alert">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- TÌM KIẾM VÀ BỘ LỌC --}}
    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-5">
        <form action="{{ route('orders.index') }}"
              method="GET"
              class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

            <div>
                <label class="block text-xs font-bold text-stone-600 mb-2">
                    Số điện thoại
                </label>
                <input
                    type="text"
                    name="search_phone"
                    value="{{ request('search_phone') }}"
                    placeholder="Nhập số điện thoại..."
                    class="w-full px-4 py-3 rounded-xl border border-stone-200
                           text-sm focus:border-red-800 focus:ring-red-800"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-600 mb-2">
                    Trạng thái đơn hàng
                </label>
                <select name="status"
                        class="w-full px-3 py-3 rounded-xl border border-stone-200 text-sm">
                    <option value="">Tất cả trạng thái</option>
                    @foreach([
                        'ChoXacNhan' => 'Chờ xác nhận',
                        'DaXacNhan' => 'Đã xác nhận',
                        'DangGiao' => 'Đang giao',
                        'DaGiao' => 'Đã giao',
                        'DaHuy' => 'Đã hủy'
                    ] as $value => $label)
                        <option value="{{ $value }}"
                            @selected(request('status') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-600 mb-2">
                    Phương thức thanh toán
                </label>
                <select name="payment_method"
                        class="w-full px-3 py-3 rounded-xl border border-stone-200 text-sm">
                    <option value="">Tất cả phương thức</option>
                    <option value="cod" @selected(request('payment_method') === 'cod')>
                        COD
                    </option>
                    <option value="vietqr" @selected(request('payment_method') === 'vietqr')>
                        VietQR
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-600 mb-2">
                    Trạng thái thanh toán
                </label>
                <select name="payment_status"
                        class="w-full px-3 py-3 rounded-xl border border-stone-200 text-sm">
                    <option value="">Tất cả thanh toán</option>
                    <option value="ChuaThanhToan"
                        @selected(request('payment_status') === 'ChuaThanhToan')>
                        Chưa thanh toán
                    </option>
                    <option value="ChoXacNhanThanhToan"
                        @selected(request('payment_status') === 'ChoXacNhanThanhToan')>
                        Chờ xác nhận thanh toán
                    </option>
                    <option value="DaThanhToan"
                        @selected(request('payment_status') === 'DaThanhToan')>
                        Đã thanh toán
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-900 hover:bg-red-800
                               text-white rounded-xl text-sm font-bold transition">
                    <i class="fas fa-search mr-1"></i>
                    Tìm kiếm
                </button>

                <a href="{{ route('orders.index') }}"
                   title="Xóa bộ lọc"
                   class="px-4 py-3 bg-stone-100 hover:bg-stone-200
                          text-stone-600 rounded-xl transition">
                    <i class="fas fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- DANH SÁCH ĐƠN HÀNG --}}
    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-stone-100 flex items-center justify-between">
            <h3 class="font-bold text-stone-800">
                Danh sách đơn hàng
            </h3>

            <span class="text-xs text-stone-500">
                {{ $orders->total() }} đơn hàng
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 text-stone-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-4">Mã đơn</th>
                        <th class="px-5 py-4">Khách hàng</th>
                        <th class="px-5 py-4">Tổng tiền</th>
                        <th class="px-5 py-4">Thanh toán</th>
                        <th class="px-5 py-4">Ngày đặt</th>
                        <th class="px-5 py-4">Trạng thái</th>
                        <th class="px-5 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-stone-100">
                    @forelse($orders as $order)
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
                                'ChoXacNhanThanhToan' => 'Chờ xác nhận',
                                'DaThanhToan' => 'Đã thanh toán',
                            ];

                            $paymentColors = [
                                'ChuaThanhToan' => 'bg-amber-50 text-amber-700',
                                'ChoXacNhanThanhToan' => 'bg-blue-50 text-blue-700',
                                'DaThanhToan' => 'bg-green-50 text-green-700',
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

                        <tr class="hover:bg-stone-50/70 transition">
                            <td class="px-5 py-5 whitespace-nowrap">
                                <span class="font-bold text-red-900">
                                    #{{ $order->MaDonHang }}
                                </span>
                            </td>

                            <td class="px-5 py-5">
                                <p class="font-semibold text-stone-800">
                                    {{ $order->HoTenNguoiNhan }}
                                </p>
                                <p class="text-xs text-stone-500 mt-1">
                                    {{ $order->SDTNguoiNhan }}
                                </p>
                            </td>

                            <td class="px-5 py-5 whitespace-nowrap font-bold text-stone-800">
                                {{ number_format($total, 0, ',', '.') }}đ
                            </td>

                            <td class="px-5 py-5">
                                <div class="space-y-2">
                                    <p class="text-xs font-bold text-stone-700">
                                        {{ $order->PhuongThucThanhToan === 'vietqr'
                                            ? 'VietQR'
                                            : 'COD' }}
                                    </p>

                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold
                                        {{ $paymentColors[$order->TrangThaiThanhToan] ?? 'bg-stone-100 text-stone-600' }}">
                                        {{ $paymentLabels[$order->TrangThaiThanhToan] ?? 'Không xác định' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-5 text-xs text-stone-500 whitespace-nowrap">
                                {{ $order->NgayDat
                                    ? \Carbon\Carbon::parse($order->NgayDat)->format('d/m/Y H:i')
                                    : '—' }}
                            </td>

                            <td class="px-5 py-5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                    {{ $statusColors[$order->TrangThai] ?? 'bg-stone-100 text-stone-600' }}">
                                    {{ $statusLabels[$order->TrangThai] ?? $order->TrangThai }}
                                </span>
                            </td>

                            <td class="px-5 py-5">
                                <div class="flex flex-wrap justify-end gap-2">

                                    {{-- XEM CHI TIẾT --}}
                                    <a href="{{ route('orders.show', $order->MaDonHang) }}"
                                       title="Xem chi tiết"
                                       class="inline-flex items-center justify-center
                                              w-9 h-9 rounded-lg border border-stone-200
                                              text-stone-600 hover:bg-stone-800
                                              hover:text-white transition">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- VIETQR: XÁC NHẬN THANH TOÁN VÀ ĐƠN --}}
                                    @if($canConfirmPayment)
                                        <form action="{{ route('orders.confirmPayment', $order->MaDonHang) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bạn đã kiểm tra giao dịch và muốn xác nhận thanh toán, đồng thời xác nhận đơn hàng #{{ $order->MaDonHang }}?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    title="Xác nhận thanh toán và đơn hàng"
                                                    class="inline-flex items-center gap-2 px-3 h-9
                                                           bg-green-700 hover:bg-green-800
                                                           text-white rounded-lg text-xs font-bold transition">
                                                <i class="fas fa-check-double"></i>
                                                Xác nhận VietQR
                                            </button>
                                        </form>
                                    @endif

                                    {{-- COD: XÁC NHẬN ĐƠN --}}
                                    @if($canConfirmOrder)
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="DaXacNhan">

                                            <button type="submit"
                                                    title="Xác nhận đơn COD"
                                                    class="w-9 h-9 rounded-lg bg-green-50
                                                           text-green-700 hover:bg-green-700
                                                           hover:text-white transition">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- CHUYỂN SANG ĐANG GIAO --}}
                                    @if($order->TrangThai === 'DaXacNhan')
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="DangGiao">

                                            <button type="submit"
                                                    title="Bắt đầu giao hàng"
                                                    class="w-9 h-9 rounded-lg bg-indigo-50
                                                           text-indigo-700 hover:bg-indigo-700
                                                           hover:text-white transition">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- XÁC NHẬN ĐÃ GIAO --}}
                                    @if($order->TrangThai === 'DangGiao')
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                                              method="POST"
                                              onsubmit="return confirm('Xác nhận đơn hàng đã được giao thành công?')">
                                            @csrf
                                            <input type="hidden" name="status" value="DaGiao">

                                            <button type="submit"
                                                    title="Xác nhận đã giao"
                                                    class="w-9 h-9 rounded-lg bg-blue-50
                                                           text-blue-700 hover:bg-blue-700
                                                           hover:text-white transition">
                                                <i class="fas fa-box-open"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- HỦY ĐƠN --}}
                                    @if($canCancel)
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng #{{ $order->MaDonHang }}? Số lượng sản phẩm sẽ được hoàn kho.')">
                                            @csrf
                                            <input type="hidden" name="status" value="DaHuy">

                                            <button type="submit"
                                                    title="Hủy đơn hàng"
                                                    class="w-9 h-9 rounded-lg bg-red-50
                                                           text-red-700 hover:bg-red-700
                                                           hover:text-white transition">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-stone-400">
                                <i class="fas fa-box-open text-3xl mb-3"></i>
                                <p>Không tìm thấy đơn hàng phù hợp.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-stone-100">
                {{ $orders->links() }}
            </div>
        @endif
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

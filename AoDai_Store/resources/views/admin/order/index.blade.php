@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="w-full max-w-full space-y-4 admin-orders-page" id="full-container">

    <style>
        .admin-orders-page input:focus, .admin-orders-page select:focus {
            outline: none; border-color: #831c30; box-shadow: 0 0 0 3px rgba(131,28,48,.09);
        }
        .admin-orders-page tbody tr { transition: background .18s ease; }
        .admin-orders-page button, .admin-orders-page a { transition: background .18s ease, color .18s ease; }
        /* Bảng trên desktop, thẻ đơn hàng trên điện thoại */
        @media (max-width: 767px) {
            .orders-table-wrap { overflow-x: visible; padding: 10px; background: #fafafa; }
            .orders-table { min-width: 0 !important; display: block; width: 100%; }
            .orders-table thead { display: none; }
            .orders-table tbody { display: grid; gap: 12px; }
            .orders-table .order-card {
                display: block; background: white; border: 1px solid #fee2e2;
                border-radius: 14px; padding: 8px 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,.035);
            }
            .orders-table .order-card td {
                display: flex; align-items: flex-start; justify-content: space-between;
                gap: 12px; width: 100%; padding: 10px 0;
                border-bottom: 1px solid #f5f5f4; text-align: right;
                white-space: normal;
            }
            .orders-table .order-card td:last-child { border-bottom: 0; }
            .orders-table .order-card td::before {
                content: attr(data-label); flex: 0 0 38%; text-align: left;
                font-size: 11px; font-weight: 700; color: #78716c;
            }
            .orders-table .order-card td > div { max-width: 62%; }
            .orders-table .order-card td[data-label="Thao tác"] > div {
                max-width: 62%; justify-content: flex-end;
            }
            .orders-table .empty-orders { display: block; }
            .orders-table .empty-orders::before { content: none; }
        }
    </style>

    {{-- THỐNG KÊ ĐƠN HÀNG: 4 THẺ, RESPONSIVE --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-2 sm:gap-3">
        @foreach([
            ['label' => 'Tổng đơn hàng', 'value' => $totalOrders, 'icon' => 'fa-receipt', 'color' => 'red'],
            ['label' => 'Chờ xác nhận', 'value' => $pendingOrders, 'icon' => 'fa-clock', 'color' => 'amber'],
            ['label' => 'Đang giao', 'value' => $shippingOrders, 'icon' => 'fa-truck', 'color' => 'indigo'],
            ['label' => 'Đã giao', 'value' => $deliveredOrders, 'icon' => 'fa-check-circle', 'color' => 'green'],
        ] as $stat)
            @php
                $statColors = [
                    'red' => ['bg-red-50 text-red-800', 'border-red-100'],
                    'amber' => ['bg-amber-50 text-amber-700', 'border-amber-100'],
                    'indigo' => ['bg-indigo-50 text-indigo-700', 'border-indigo-100'],
                    'green' => ['bg-green-50 text-green-700', 'border-green-100'],
                ];
                $palette = $statColors[$stat['color']];
            @endphp
            <div class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border {{ $palette[1] }} flex items-center gap-2 sm:gap-4 min-w-0">
                <div class="shrink-0 w-9 h-9 sm:w-12 sm:h-12 flex items-center justify-center rounded-xl {{ $palette[0] }}">
                    <i class="fas {{ $stat['icon'] }} text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide font-bold leading-tight">{{ $stat['label'] }}</p>
                    <p class="text-lg sm:text-xl font-bold text-red-900 mt-1">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="admin-order-alert flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl" role="alert">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="admin-order-alert flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl" role="alert">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- BỘ LỌC --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">
        <form action="{{ route('orders.index') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[minmax(180px,1.2fr)_minmax(0,3fr)_auto] gap-3 items-stretch xl:items-center">
            <div class="relative min-w-0">
                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <i class="fas fa-search text-[11px]"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       aria-label="Tìm theo họ tên hoặc số điện thoại"
                       placeholder="Họ tên hoặc số điện thoại..."
                       class="w-full pl-9 pr-3 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 md:col-span-2 xl:col-span-1 gap-2 min-w-0">
                <select name="status" aria-label="Trạng thái đơn hàng"
                        class="bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-[11px] focus:ring-0 focus:border-red-700 cursor-pointer w-full min-w-0">
                    <option value="">-- Trạng thái đơn --</option>
                    @foreach([
                        'ChoXacNhan' => 'Chờ xác nhận',
                        'DaXacNhan' => 'Đã xác nhận',
                        'DangGiao' => 'Đang giao',
                        'DaGiao' => 'Đã giao',
                        'DaHuy' => 'Đã hủy'
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="payment_method" aria-label="Phương thức thanh toán"
                        class="bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-[11px] focus:ring-0 focus:border-red-700 cursor-pointer w-full min-w-0">
                    <option value="">-- Phương thức --</option>
                    <option value="cod" @selected(request('payment_method') === 'cod')>COD</option>
                    <option value="vietqr" @selected(request('payment_method') === 'vietqr')>VietQR</option>
                </select>
                <select name="payment_status" aria-label="Trạng thái thanh toán"
                        class="bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-[11px] focus:ring-0 focus:border-red-700 cursor-pointer w-full min-w-0">
                    <option value="">-- Thanh toán --</option>
                    <option value="ChuaThanhToan" @selected(request('payment_status') === 'ChuaThanhToan')>Chưa thanh toán</option>
                    <option value="ChoXacNhanThanhToan" @selected(request('payment_status') === 'ChoXacNhanThanhToan')>Chờ xác nhận</option>
                    <option value="DaThanhToan" @selected(request('payment_status') === 'DaThanhToan')>Đã thanh toán</option>
                </select>
            </div>

            <div class="flex items-center gap-2 md:col-span-2 xl:col-span-1">
                <button type="submit"
                        class="flex-1 xl:flex-none bg-red-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap">
                    <i class="fas fa-filter mr-1"></i> Lọc
                </button>
                <a href="{{ route('orders.index') }}"
                   class="flex-1 xl:flex-none text-center bg-stone-100 text-stone-600 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap">
                    <i class="fas fa-redo mr-1"></i> Đặt lại
                </a>
            </div>
        </form>
    </div>

    {{-- DANH SÁCH ĐƠN HÀNG --}}
    <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">

        <div class="orders-table-wrap overflow-x-auto">
            <table class="orders-table w-full min-w-[940px] text-left text-xs border-collapse">
                <thead class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">
                    <tr>
                        <th class="px-3 py-3">Mã đơn</th>
                        <th class="px-3 py-3">Khách hàng</th>
                        <th class="px-3 py-3">Tổng tiền</th>
                        <th class="px-3 py-3">Thanh toán</th>
                        <th class="px-3 py-3">Ngày đặt</th>
                        <th class="px-3 py-3">Trạng thái</th>
                        <th class="px-3 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-red-50">
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

                        <tr class="order-card hover:bg-rose-50/40 transition">
                            <td data-label="Mã đơn" class="px-3 py-3 whitespace-nowrap">
                                <span class="font-bold text-red-900">
                                    #{{ $order->MaDonHang }}
                                </span>
                            </td>

                            <td data-label="Khách hàng" class="px-3 py-3">
                                <p class="font-semibold text-stone-800">
                                    {{ $order->HoTenNguoiNhan }}
                                </p>
                                <p class="hidden md:block text-xs text-stone-500 mt-1">
                                    {{ $order->SDTNguoiNhan }}
                                </p>
                            </td>

                            <td data-label="Tổng tiền" class="px-3 py-3 whitespace-nowrap font-bold text-stone-800">
                                {{ number_format($total, 0, ',', '.') }}đ
                            </td>

                            <td data-label="Thanh toán" class="px-3 py-3">
                                <div class="space-y-2">
                                    <p class="text-xs font-bold text-stone-700">
                                        {{ $order->PhuongThucThanhToan === 'vietqr' ? 'VietQR' : 'COD' }}
                                    </p>
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold {{ $paymentColors[$order->TrangThaiThanhToan] ?? 'bg-stone-100 text-stone-600' }}">
                                        {{ $paymentLabels[$order->TrangThaiThanhToan] ?? 'Không xác định' }}
                                    </span>
                                </div>
                            </td>

                            <td data-label="Ngày đặt" class="px-3 py-3 text-xs text-stone-500 whitespace-nowrap">
                                {{ $order->NgayDat ? \Carbon\Carbon::parse($order->NgayDat)->format('d/m/Y H:i') : '—' }}
                            </td>

                            <td data-label="Trạng thái" class="px-3 py-3">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $statusColors[$order->TrangThai] ?? 'bg-stone-100 text-stone-600' }}">
                                    {{ $statusLabels[$order->TrangThai] ?? $order->TrangThai }}
                                </span>
                            </td>

                            <td data-label="Thao tác" class="px-3 py-3">
                                <div class="flex flex-wrap justify-end gap-1">

                                    {{-- XEM CHI TIẾT --}}
                                    <a href="{{ route('orders.show', $order->MaDonHang) }}"
                                       title="Xem chi tiết"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- VIETQR: XÁC NHẬN THANH TOÁN VÀ ĐƠN --}}
                                    @if($canConfirmPayment)
                                        <form action="{{ route('orders.confirmPayment', $order->MaDonHang) }}" method="POST"
                                              class="form-confirm"
                                              data-title="Xác nhận thanh toán?"
                                              data-text="Xác nhận giao dịch và chuyển đơn hàng #{{ $order->MaDonHang }} sang trạng thái Đã xác nhận?"
                                              data-icon="question"
                                              data-confirm-text="Xác nhận"
                                              data-confirm-color="#15803d">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Xác nhận thanh toán và đơn hàng"
                                                    class="inline-flex items-center gap-2 px-2 h-8 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold transition">
                                                <i class="fas fa-check-double"></i>
                                                Xác nhận VietQR
                                            </button>
                                        </form>
                                    @endif

                                    {{-- COD: XÁC NHẬN ĐƠN --}}
                                    @if($canConfirmOrder)
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}" method="POST"
                                              class="form-confirm"
                                              data-title="Xác nhận đơn hàng?"
                                              data-text="Bạn muốn chuyển đơn hàng #{{ $order->MaDonHang }} sang trạng thái Đã xác nhận?"
                                              data-icon="question"
                                              data-confirm-text="Xác nhận"
                                              data-confirm-color="#15803d">
                                            @csrf
                                            <input type="hidden" name="status" value="DaXacNhan">
                                            <button type="submit"
                                                    title="Xác nhận đơn COD"
                                                    class="w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-700 hover:text-white transition">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- CHUYỂN SANG ĐANG GIAO --}}
                                    @if($order->TrangThai === 'DaXacNhan')
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}" method="POST"
                                              class="form-confirm"
                                              data-title="Bắt đầu giao hàng?"
                                              data-text="Bạn muốn chuyển đơn #{{ $order->MaDonHang }} cho đơn vị vận chuyển?"
                                              data-icon="info"
                                              data-confirm-text="Tiến hành giao"
                                              data-confirm-color="#4338ca">
                                            @csrf
                                            <input type="hidden" name="status" value="DangGiao">
                                            <button type="submit"
                                                    title="Bắt đầu giao hàng"
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-700 hover:text-white transition">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- XÁC NHẬN ĐÃ GIAO --}}
                                    @if($order->TrangThai === 'DangGiao')
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}" method="POST"
                                              class="form-confirm"
                                              data-title="Xác nhận đã giao?"
                                              data-text="Khách hàng đã nhận được đơn #{{ $order->MaDonHang }} thành công?"
                                              data-icon="success"
                                              data-confirm-text="Đã giao"
                                              data-confirm-color="#1d4ed8">
                                            @csrf
                                            <input type="hidden" name="status" value="DaGiao">
                                            <button type="submit"
                                                    title="Xác nhận đã giao"
                                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-700 hover:text-white transition">
                                                <i class="fas fa-box-open"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- HỦY ĐƠN --}}
                                    @if($canCancel)
                                        <form action="{{ route('orders.confirmstatus', $order->MaDonHang) }}" method="POST"
                                              class="form-confirm"
                                              data-title="Xác nhận hủy đơn?"
                                              data-text="Bạn chắc chắn muốn hủy đơn hàng #{{ $order->MaDonHang }}? Số lượng sản phẩm sẽ được hoàn kho."
                                              data-icon="warning"
                                              data-confirm-text="Hủy đơn"
                                              data-confirm-color="#dc2626">
                                            @csrf
                                            <input type="hidden" name="status" value="DaHuy">
                                            <button type="submit"
                                                    title="Hủy đơn hàng"
                                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-700 hover:bg-red-700 hover:text-white transition">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="empty-orders py-16 text-center text-stone-400">
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
            <div class="p-4 border-t border-stone-100">
                {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Tự động ẩn thông báo thành công/lỗi
    document.querySelectorAll('.admin-order-alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 4000);
    });

    // 2. Xử lý đồng bộ toàn bộ Form xác nhận bằng SweetAlert2
    document.querySelectorAll('.form-confirm').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Chặn hành động submit mặc định

            // Lấy nội dung tùy chỉnh từ data attributes
            const title = this.dataset.title || 'Xác nhận hành động?';
            const text = this.dataset.text || 'Bạn có chắc chắn muốn tiếp tục?';
            const icon = this.dataset.icon || 'warning';
            const confirmText = this.dataset.confirmText || 'Đồng ý';
            const confirmColor = this.dataset.confirmColor || '#3085d6';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#9ca3af',
                confirmButtonText: `<i class="fas fa-check"></i> ${confirmText}`,
                cancelButtonText: 'Hủy bỏ',
                background: '#ffffff',
                borderRadius: '10px'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Tiếp tục submit nếu nhấn Xác nhận
                }
            });
        });
    });
});
</script>
@endsection
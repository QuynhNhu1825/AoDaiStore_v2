@if($order->TrangThaiYeuCauHuy === 'ChoDuyet')
    <div class="notification-message {{ $spacing }}" data-icon="warning">
        <strong>Yêu cầu hủy đơn hàng đang chờ xét duyệt.</strong>
        <p class="mt-1">Đơn hàng vẫn ở trạng thái hiện tại. Cửa hàng sẽ cập nhật kết quả sau khi xét duyệt.</p>
        @if($order->NgayYeuCauHuy)
            <p class="mt-1 text-xs">Gửi lúc {{ \Carbon\Carbon::parse($order->NgayYeuCauHuy)->format('d/m/Y H:i') }}</p>
        @endif
    </div>
@elseif(in_array($order->TrangThaiYeuCauHuy, ['DaDuyet', 'TuChoi'], true))
    @php
        $approved = $order->TrangThaiYeuCauHuy === 'DaDuyet';
        $reviewedAt = $order->NgayXuLyYeuCauHuy
            ? \Carbon\Carbon::parse($order->NgayXuLyYeuCauHuy)->timestamp
            : 0;
        $noticeKey = implode('_', ['heritage_cancel_notice', auth()->id(), $order->MaDonHang, $order->TrangThaiYeuCauHuy, $reviewedAt]);
    @endphp

    <div class="notification-message {{ $spacing }}"
         data-icon="{{ $approved ? 'success' : 'info' }}"
         data-notification-key="{{ $noticeKey }}"
         role="status"
         hidden>
        <strong>Kết quả yêu cầu hủy đơn #{{ $order->MaDonHang }}</strong>
        <p class="mt-1">
            @if($approved)
                Yêu cầu hủy đã được chấp thuận. Đơn hàng đã được hủy.
                @if($order->PhuongThucThanhToan === 'vietqr' && $order->TrangThaiThanhToan === 'DaThanhToan')
                    Cửa hàng sẽ hoàn trả thủ công số tiền đã thanh toán qua VietQR trong vòng 1–2 ngày làm việc.
                    Vui lòng kiểm tra tài khoản ngân hàng của bạn.
                @endif
            @elseif($orderStatus === 'DaGiao')
                Yêu cầu hủy chưa được chấp thuận. Đơn hàng hiện đã được giao.
            @elseif($orderStatus === 'DangGiao')
                Yêu cầu hủy chưa được chấp thuận. Đơn hàng hiện đang được vận chuyển.
            @else
                Yêu cầu hủy chưa được chấp thuận. Đơn hàng tiếp tục được xử lý.
            @endif
        </p>
        <button type="button" class="notification-close" aria-label="Đánh dấu đã xem thông báo hủy đơn">×</button>
    </div>
@endif

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class VietQRController extends Controller
{
    /**
     * Hiển thị trang thanh toán VietQR mô phỏng.
     */
    public function show($id)
    {
        // Chỉ được xem đơn hàng của chính mình.
        $order = Order::where('MaDonHang', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->where('PhuongThucThanhToan', 'vietqr')
            ->firstOrFail();

        // Tổng tiền thanh toán.
        $amount = (int) (
            $order->TienHang + $order->PhiVanChuyen
        );

        // Nội dung chuyển khoản.
        $transferContent = 'AODAI' . $order->MaDonHang;

        $qrData = 'AODAI_STORE'
            . '|ORDER=' . $order->MaDonHang
            . '|AMOUNT=' . $amount
            . '|CONTENT=' . $transferContent
            . '|NOT_REAL_PAYMENT';

        return view('client.checkout.vietqr', compact(
            'order',
            'amount',
            'transferContent',
            'qrData'
        ));
    }
}

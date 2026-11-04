<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class VNPAYController extends Controller
{
    //
    // Ví dụ logic tạo URL thanh toán đơn giản
public function createPayment($order)
{
    $vnp_TmnCode = config('services.vnpay.tmn_code');
    $vnp_HashSecret = config('services.vnpay.hash_secret');
    $vnp_Url = config('services.vnpay.url');
    $vnp_Returnurl = config('services.vnpay.return_url');

    $inputData = [
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $order->TongTien * 100,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => request()->ip(),
        "vnp_Locale" => "vn",
        "vnp_OrderInfo" => "Thanh toan hoa don #" . $order->MaHoaDon,
        "vnp_OrderType" => "billpayment",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $order->MaHoaDon,
    ];

   ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = config('services.vnpay.url') . "?" . $query;
    $vnp_HashSecret = config('services.vnpay.hash_secret');

    // Tính toán hash dựa trên chuỗi đã encode
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

    return redirect()->away($vnp_Url);
}

// public function vnpayReturn(Request $request)
// {
//     $vnp_SecureHash = $request->vnp_SecureHash;
//     $vnp_HashSecret = config('services.vnpay.hash_secret');
//     $inputData = $request->all();
//     unset($inputData['vnp_SecureHash']);
//     unset($inputData['vnp_SecureHashType']);

//     ksort($inputData);
//     $hashData = "";
//     $i = 0;
//     foreach ($inputData as $key => $value) {
//         if ($i == 1) {
//             $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
//         } else {
//             $hashData .= urlencode($key) . "=" . urlencode($value);
//             $i = 1;
//         }
//     }

//     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

//     if ($secureHash == $vnp_SecureHash) {
//         if ($request->vnp_ResponseCode == '00') {
//             // Thanh toán thành công: Cập nhật DB của bạn tại đây
//             // $order = Order::find($request->vnp_TxnRef);
//             // $order->update(['TrangThaiThanhToan' => 'Đã thanh toán']);
            
//             return redirect()->route('home')->with('success', 'Thanh toán thành công!');
//         }
//         return redirect()->route('home')->with('error', 'Thanh toán không thành công.');
//     }
//     return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ.');
// }



public function vnpayReturn(Request $request)
{
    $vnp_HashSecret = config('services.vnpay.hash_secret');
    $vnp_SecureHash = $request->vnp_SecureHash;

    $inputData = [];

    foreach ($request->all() as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }

    unset($inputData['vnp_SecureHash']);
    unset($inputData['vnp_SecureHashType']);

    ksort($inputData);

    $hashData = "";
    foreach ($inputData as $key => $value) {
        $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $hashData = rtrim($hashData, '&');

    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    // ❌ Sai chữ ký
    if ($secureHash !== $vnp_SecureHash) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Sai chữ ký VNPAY');
    }

    // ✅ Thanh toán thành công
    if ($request->vnp_ResponseCode === '00') {
       
       
        Order::where('MaHoaDon', $request->vnp_TxnRef)
            ->update(['TrangThai' => 'ChoXacNhan','NgayThanhToan'=>now()]);
         app(CartController::class)->clearCart();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Thanh toán thành công 🎉');
    }

    // ❌ Thanh toán thất bại
    return redirect()
        ->route('cart.index')
        ->with(
            'error',
            'Thanh toán thất bại (Mã: ' . $request->vnp_ResponseCode . ')'
        );
}

}



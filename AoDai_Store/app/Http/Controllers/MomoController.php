<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MoMoController extends Controller
{
    private function amount(Order $order): int
    {
        return (int) round((float) $order->TienHang + (float) $order->PhiVanChuyen);
    }

    private function ownedOrder($id): Order
    {
        return Order::where('MaDonHang', $id)
            ->where('MaTaiKhoan', Auth::id())
            ->where('PhuongThucThanhToan', Order::MOMO)
            ->firstOrFail();
    }

    public function show($id)
    {
        $order = $this->ownedOrder($id);
        $amount = $this->amount($order);
        return view('client.checkout.momo', compact('order', 'amount'));
    }

    public function pay($id)
    {
        if (config('services.momo.mode') !== 'sandbox') {
            return back()->with('error', 'Chế độ thanh toán MoMo chưa được cấu hình hợp lệ.');
        }

        $partnerCode = config('services.momo.partner_code');
        $accessKey = config('services.momo.access_key');
        $secretKey = config('services.momo.secret_key');
        $endpoint = config('services.momo.endpoint');

        if (!$partnerCode || !$accessKey || !$secretKey || !$endpoint) {
            return back()->with('error', 'Chưa cấu hình đầy đủ khóa MoMo Sandbox.');
        }

        // Lock the order and create exactly one pending attempt per order.
        try {
            $attempt = DB::transaction(function () use ($id) {
                $order = Order::where('MaDonHang', $id)
                    ->where('MaTaiKhoan', Auth::id())
                    ->where('PhuongThucThanhToan', Order::MOMO)
                    ->lockForUpdate()->firstOrFail();

                if (!$order->canPay()) {
                    throw new \RuntimeException('Đơn hàng đã thanh toán, bị hủy hoặc hết hạn thanh toán.');
                }

                $amount = $this->amount($order);
                if ($amount <= 0) {
                    throw new \RuntimeException('Số tiền thanh toán không hợp lệ.');
                }

                if ($order->paymentTransactions()
                    ->whereIn('TrangThaiGiaoDich', ['DangCho', 'CanDoiSoat', 'ThanhCong'])
                    ->exists()) {
                    throw new \RuntimeException('Đơn hàng đã có giao dịch đang xử lý hoặc thành công.');
                }

                $transaction = PaymentTransaction::create([
                    'MaDonHang' => $order->MaDonHang,
                    'PhuongThucThanhToan' => Order::MOMO,
                    'SoTien' => $amount,
                    'TrangThaiGiaoDich' => 'DangCho',
                ]);

                $orderId = 'DH' . $order->MaDonHang . '_GD' . $transaction->MaGiaoDich;
                $requestId = (string) Str::uuid();
                $transaction->update([
                    'MaThanhToan' => $orderId,
                    'MaYeuCauThanhToan' => $requestId,
                ]);

                return compact('orderId', 'requestId', 'amount', 'transaction');
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $orderId = $attempt['orderId'];
        $requestId = $attempt['requestId'];
        $amount = $attempt['amount'];
        $transaction = $attempt['transaction'];
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.ipn');
        $requestType = 'captureWallet';
        $orderInfo = 'Thanh toan don hang ' . $id;
        $extraData = '';

        $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}"
            . "&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}"
            . "&partnerCode={$partnerCode}&redirectUrl={$redirectUrl}"
            . "&requestId={$requestId}&requestType={$requestType}";

        $payload = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'AoDai Store',
            'storeId' => 'AoDaiStore',
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'requestType' => $requestType,
            'autoCapture' => true,
            'extraData' => $extraData,
            'signature' => hash_hmac('sha256', $rawHash, $secretKey),
        ];

        try {
            $response = Http::timeout(30)->acceptJson()->post($endpoint, $payload);
            $result = $response->json();
            Log::info('MoMo API response', [
                'http_status' => $response->status(),
                'resultCode' => is_array($result) ? ($result['resultCode'] ?? null) : null,
                'message' => is_array($result) ? ($result['message'] ?? null) : null,
            ]);

            if ($response->successful() && is_array($result)
                && (int) ($result['resultCode'] ?? -1) === 0
                && !empty($result['payUrl'])) {
                return redirect()->away($result['payUrl']);
            }

            if (is_array($result)
                && array_key_exists('resultCode', $result)
                && (int) $result['resultCode'] !== 0) {
                $transaction->update(['TrangThaiGiaoDich' => 'ThatBai']);
                return back()->with('error', 'MoMo: ' . ($result['message'] ?? 'Yêu cầu bị từ chối.') . ' (mã ' . $result['resultCode'] . ')');
            }
        } catch (Throwable $e) {
            report($e);
            Log::error('MoMo connection error', [
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ]);
        }

        // An ambiguous gateway response must be reconciled, not retried blindly.
        $transaction->update(['TrangThaiGiaoDich' => 'CanDoiSoat']);
        return back()->with('error', 'Chưa xác định kết quả từ MoMo; vui lòng chờ đối soát.');
    }

    /** Browser redirect is informational only, never authoritative. */
    public function return(Request $request)
    {
        return redirect()->route('order.history')->with(
            'success',
            'Đã quay lại từ MoMo. Hệ thống đang xác minh kết quả thanh toán.'
        );
    }

    /** Public MoMo IPN endpoint: verify signature and transaction identity. */
    public function ipn(Request $request)
    {
        if (config('services.momo.mode') !== 'sandbox') {
            return response()->json(['message' => 'Not available'], 404);
        }

        $data = $request->all();
        $secretKey = config('services.momo.secret_key');
        $partnerCode = config('services.momo.partner_code');
        $accessKey = config('services.momo.access_key');

        $required = [
            'partnerCode', 'orderId', 'requestId', 'amount', 'orderInfo',
            'orderType', 'transId', 'resultCode', 'message', 'payType',
            'responseTime', 'extraData', 'signature',
        ];
        foreach ($required as $key) {
            if (!array_key_exists($key, $data) || !is_scalar($data[$key])) {
                return response()->json(['message' => 'Invalid payload'], 400);
            }
        }
        if (!$secretKey || !$partnerCode || !$accessKey
            || !hash_equals((string) $partnerCode, (string) $data['partnerCode'])) {
            return response()->json(['message' => 'Invalid partner'], 401);
        }

        // MoMo IPN signature format for one-time wallet payments.
        $rawHash = 'accessKey=' . $accessKey
            . '&amount=' . $data['amount']
            . '&extraData=' . $data['extraData']
            . '&message=' . $data['message']
            . '&orderId=' . $data['orderId']
            . '&orderInfo=' . $data['orderInfo']
            . '&orderType=' . $data['orderType']
            . '&partnerCode=' . $data['partnerCode']
            . '&payType=' . $data['payType']
            . '&requestId=' . $data['requestId']
            . '&responseTime=' . $data['responseTime']
            . '&resultCode=' . $data['resultCode']
            . '&transId=' . $data['transId'];

        $expected = hash_hmac('sha256', $rawHash, $secretKey);
        if (!hash_equals($expected, (string) $data['signature'])) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        try {
            DB::transaction(function () use ($data) {
                $transaction = PaymentTransaction::where('MaThanhToan', $data['orderId'])
                    ->where('MaYeuCauThanhToan', $data['requestId'])
                    ->first();
                if (!$transaction) {
                    throw new \RuntimeException('Unknown payment transaction');
                }

                $order = Order::where('MaDonHang', $transaction->MaDonHang)
                    ->lockForUpdate()->firstOrFail();
                $transaction->refresh();

                if ($order->PhuongThucThanhToan !== Order::MOMO
                    || $transaction->PhuongThucThanhToan !== Order::MOMO
                    || (int) $data['amount'] !== $this->amount($order)
                    || (int) $data['amount'] !== (int) $transaction->SoTien) {
                    throw new \RuntimeException('Payment mismatch');
                }

                if ($transaction->TrangThaiGiaoDich === 'ThanhCong') {
                    return; // Idempotent callback.
                }

                if ((int) $data['resultCode'] === 0) {
                    if ($order->TrangThai === Order::DA_HUY) {
                        $transaction->update([
                            'TrangThaiGiaoDich' => 'CanDoiSoat',
                            'MaGiaoDichCongThanhToan' => (string) $data['transId'],
                        ]);
                        return; // Never silently revive a cancelled order.
                    }

                    $transaction->update([
                        'TrangThaiGiaoDich' => 'ThanhCong',
                        'MaGiaoDichCongThanhToan' => (string) $data['transId'],
                    ]);
                    $order->update([
                        'TrangThaiThanhToan' => Order::DA_THANH_TOAN,
                        'NgayThanhToan' => now(),
                    ]);
                } elseif (!in_array($transaction->TrangThaiGiaoDich, ['ThanhCong', 'HetHan'], true)) {
                    $transaction->update(['TrangThaiGiaoDich' => 'ThatBai']);
                }
            });
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'Unable to process'], 409);
        }

        return response()->json(['message' => 'Received'], 200);
    }
}

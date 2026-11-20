<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Order;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VietQrPaymentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('donhang', function (Blueprint $table) {
            $table->id('MaDonHang');
            $table->integer('MaTaiKhoan');
            $table->string('PhuongThucThanhToan');
            $table->string('TrangThai');
            $table->string('TrangThaiThanhToan');
            $table->decimal('TienHang');
            $table->decimal('PhiVanChuyen')->default(0);
            $table->dateTime('NgayDat');
            $table->dateTime('NgayCapNhat');
        });
        Schema::create('chitietdonhang', function (Blueprint $table) {
            $table->integer('MaDonHang');
        });
        Schema::create('danhgia', function (Blueprint $table) {
            $table->integer('MaTaiKhoan');
            $table->integer('MaDonHang');
            $table->integer('MaSanPham');
        });
        Schema::create('giohang', function (Blueprint $table) {
            $table->integer('MaTaiKhoan');
            $table->integer('SoLuong');
        });

        $this->actingAs((new Account)->forceFill(['MaTaiKhoan' => 5]));
    }

    public function test_existing_order_can_resume_payment_from_history_and_detail(): void
    {
        $order = $this->createOrder();
        $paymentUrl = route('vietqr.show', $order);

        $this->get(route('order.history'))->assertOk()->assertSee($paymentUrl);
        $this->get(route('order.show', $order))->assertOk()->assertSee($paymentUrl);
        $this->get($paymentUrl)->assertOk()->assertSee('AODAI' . $order->MaDonHang);
        $this->get($paymentUrl)->assertOk()->assertSee('AODAI' . $order->MaDonHang);

        $this->assertDatabaseCount('donhang', 1);
        $this->assertSame('ChuaThanhToan', $order->refresh()->TrangThaiThanhToan);
    }

    public function test_confirmation_hides_the_payment_link_and_can_be_repeated_safely(): void
    {
        $order = $this->createOrder();
        $paymentUrl = route('vietqr.show', $order);

        $this->post(route('vietqr.confirm', $order))->assertRedirect($paymentUrl);
        $this->post(route('vietqr.confirm', $order))->assertRedirect($paymentUrl);
        $this->get($paymentUrl)->assertOk()->assertSee('Chờ xác nhận thanh toán')->assertDontSee('id="vietqr"', false);
        $this->get(route('order.history'))->assertOk()->assertDontSee($paymentUrl);
        $this->get(route('order.show', $order))->assertOk()->assertDontSee($paymentUrl);

        $this->assertSame('ChoXacNhanThanhToan', $order->refresh()->TrangThaiThanhToan);
    }

    public function test_unavailable_orders_do_not_offer_payment(): void
    {
        $canceled = $this->createOrder(['TrangThai' => 'DaHuy']);
        $paid = $this->createOrder(['TrangThaiThanhToan' => 'DaThanhToan']);
        $cod = $this->createOrder(['PhuongThucThanhToan' => 'cod']);
        $otherCustomersOrder = $this->createOrder(['MaTaiKhoan' => 6]);

        $this->get(route('order.history'))->assertOk()->assertDontSee('Tiếp tục thanh toán VietQR');
        $this->post(route('vietqr.confirm', $canceled))->assertRedirect(route('order.show', $canceled));
        $this->post(route('vietqr.confirm', $paid))->assertRedirect(route('vietqr.show', $paid));
        $this->post(route('vietqr.confirm', $cod))->assertNotFound();
        $this->get(route('vietqr.show', $otherCustomersOrder))->assertNotFound();
        $this->post(route('vietqr.confirm', $otherCustomersOrder))->assertNotFound();

        $this->assertSame('ChuaThanhToan', $canceled->refresh()->TrangThaiThanhToan);
        $this->assertSame('DaThanhToan', $paid->refresh()->TrangThaiThanhToan);
    }

    private function createOrder(array $attributes = []): Order
    {
        return Order::create($attributes + [
            'MaTaiKhoan' => 5,
            'PhuongThucThanhToan' => 'vietqr',
            'TrangThai' => 'ChoXacNhan',
            'TrangThaiThanhToan' => 'ChuaThanhToan',
            'TienHang' => 300000,
        ]);
    }
}

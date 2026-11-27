<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminStatisticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('donhang', function (Blueprint $table) {
            $table->id('MaDonHang');
            $table->string('TrangThai');
            $table->decimal('TienHang');
            $table->decimal('PhiVanChuyen')->nullable();
            $table->dateTime('NgayDat', 6);
        });

        Schema::create('chitietdonhang', function (Blueprint $table) {
            $table->integer('MaDonHang');
            $table->integer('MaChiTietSP');
            $table->integer('SoLuong');
        });

        Schema::create('chitietsanpham', function (Blueprint $table) {
            $table->id('MaChiTietSP');
            $table->integer('MaSanPham');
        });

        Schema::create('sanpham', function (Blueprint $table) {
            $table->id('MaSanPham');
            $table->string('TenSanPham');
            $table->boolean('IsDeleted');
        });

        $this->actingAs((new Account)->forceFill(['MaTaiKhoan' => 1, 'Role' => 'Admin']));
    }

    public function test_report_uses_order_totals_and_includes_the_entire_end_date(): void
    {
        DB::table('donhang')->insert([
            ['MaDonHang' => 1, 'TrangThai' => 'DaGiao', 'TienHang' => 500000, 'PhiVanChuyen' => 20000, 'NgayDat' => '2025-06-10 23:59:59.999999'],
            ['MaDonHang' => 2, 'TrangThai' => 'DaGiao', 'TienHang' => 100000, 'PhiVanChuyen' => null, 'NgayDat' => '2025-06-10 00:00:00'],
            ['MaDonHang' => 3, 'TrangThai' => 'DaGiao', 'TienHang' => 900000, 'PhiVanChuyen' => null, 'NgayDat' => '2025-06-11 00:00:00'],
            ['MaDonHang' => 4, 'TrangThai' => 'DaHuy', 'TienHang' => 200000, 'PhiVanChuyen' => null, 'NgayDat' => '2025-06-10 12:00:00'],
        ]);

        DB::table('sanpham')->insert(['MaSanPham' => 1, 'TenSanPham' => 'Áo dài', 'IsDeleted' => true]);
        DB::table('chitietsanpham')->insert([
            ['MaChiTietSP' => 1, 'MaSanPham' => 1],
            ['MaChiTietSP' => 2, 'MaSanPham' => 1],
        ]);
        DB::table('chitietdonhang')->insert([
            ['MaDonHang' => 1, 'MaChiTietSP' => 1, 'SoLuong' => 2],
            ['MaDonHang' => 1, 'MaChiTietSP' => 2, 'SoLuong' => 3],
            ['MaDonHang' => 3, 'MaChiTietSP' => 1, 'SoLuong' => 4],
            ['MaDonHang' => 4, 'MaChiTietSP' => 1, 'SoLuong' => 8],
        ]);

        $this->get(route('statistics.index', ['tu_ngay' => '2025-06-10', 'den_ngay' => '2025-06-10']))
            ->assertOk()
            ->assertViewHas('tongDonHang', 3)
            ->assertViewHas('donDaGiao', 2)
            ->assertViewHas('tongDoanhThu', 620000)
            ->assertViewHas('soSanPhamDaBan', 5)
            ->assertViewHas('donTheoTrangThai', fn ($statuses) => array_sum(array_column($statuses, 'count')) === 3)
            ->assertViewHas('doanhThuTheoNgay', fn ($days) => $days->count() === 1 && (int) $days->sum('tong_tien') === 620000)
            ->assertViewHas('sanPhamBanChay', fn ($products) => $products->count() === 1 && (int) $products->first()->so_luong_ban === 5)
            ->assertSee('620.000 đ');

        $this->get(route('statistics.index'))
            ->assertOk()
            ->assertViewHas('tongDonHang', 4);

        $this->get(route('statistics.index', ['den_ngay' => '2025-06-10']))
            ->assertOk()
            ->assertViewHas('tongDonHang', 3);
    }

    public function test_report_rejects_a_reversed_date_range(): void
    {
        $this->from(route('statistics.index'))
            ->get(route('statistics.index', ['tu_ngay' => '2025-06-11', 'den_ngay' => '2025-06-10']))
            ->assertRedirect(route('statistics.index'))
            ->assertSessionHasErrors('den_ngay');
    }
}

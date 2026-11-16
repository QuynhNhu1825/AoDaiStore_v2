<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'donhang';

    protected $primaryKey = 'MaDonHang';

    public $timestamps = true;

    const CREATED_AT = 'NgayDat';
    const UPDATED_AT = 'NgayCapNhat';

    protected $fillable = [
        'MaTaiKhoan',
        'HoTenNguoiNhan',
        'SDTNguoiNhan',
        'DiaChiNhan',
        'TienHang',
        'PhiVanChuyen',
        'PhuongThucThanhToan',
        'TrangThai',
        'GhiChu',
        'NgayDat',
        'HanThanhToan',
        'NgayCapNhat',
        'TrangThaiThanhToan',
        'NgayThanhToan',
    ];

    protected $casts = [
        'MaDonHang' => 'integer',
        'MaTaiKhoan' => 'integer',
        'TienHang' => 'decimal:2',
        'PhiVanChuyen' => 'decimal:2',
        'NgayDat' => 'datetime',
        'HanThanhToan' => 'datetime',
        'NgayCapNhat' => 'datetime',
        'NgayThanhToan' => 'datetime',
    ];

    public const CHO_XAC_NHAN = 'ChoXacNhan';
    public const DA_XAC_NHAN = 'DaXacNhan';
    public const DANG_GIAO = 'DangGiao';
    public const DA_GIAO = 'DaGiao';
    public const DA_HUY = 'DaHuy';

    public const CHUA_THANH_TOAN = 'ChuaThanhToan';
    public const CHO_XAC_NHAN_THANH_TOAN = 'ChoXacNhanThanhToan';
    public const DA_THANH_TOAN = 'DaThanhToan';

    public const COD = 'cod';
    public const MOMO = 'momo';

    // ==========================================
    // 7. HIEN THI TRANG THAI DON HANG
    // ==========================================

    public function getTrangThaiTextAttribute(): string
    {
        return match ($this->TrangThai) {
            self::CHO_XAC_NHAN => 'Chờ xác nhận',
            self::DA_XAC_NHAN => 'Đã xác nhận',
            self::DANG_GIAO => 'Đang giao',
            self::DA_GIAO => 'Đã giao',
            self::DA_HUY => 'Đã hủy',
            default => $this->TrangThai ?? 'Chưa xác định',
        };
    }

    // ==========================================
    // 8. HIEN THI TRANG THAI THANH TOAN
    // ==========================================

    public function getTrangThaiThanhToanTextAttribute(): string
    {
        return match ($this->TrangThaiThanhToan) {
            self::CHUA_THANH_TOAN => 'Chưa thanh toán',
            self::CHO_XAC_NHAN_THANH_TOAN =>
                'Chờ xác nhận thanh toán',
            self::DA_THANH_TOAN => 'Đã thanh toán',
            default => $this->TrangThaiThanhToan ?? 'Chưa xác định',
        };
    }

    // ==========================================
    // 9. QUAN HE VOI CHI TIET DON HANG
    // ==========================================

    public function orderDetails()
    {
        return $this->hasMany(
            OrderDetail::class,
            'MaDonHang',
            'MaDonHang'
        );
    }

    // ==========================================
    // 10. QUAN HE VOI GIAO DICH THANH TOAN
    // ==========================================

    public function paymentTransactions()
    {
        return $this->hasMany(
            PaymentTransaction::class,
            'MaDonHang',
            'MaDonHang'
        );
    }

    // ==========================================
    // 11. KIEM TRA PHUONG THUC THANH TOAN
    // ==========================================

    public function isMomo(): bool
    {
        return $this->PhuongThucThanhToan === self::MOMO;
    }

    public function isCOD(): bool
    {
        return $this->PhuongThucThanhToan === self::COD;
    }

    // ==========================================
    // 12. KIEM TRA DA THANH TOAN
    // ==========================================

    public function isPaid(): bool
    {
        return $this->TrangThaiThanhToan === self::DA_THANH_TOAN;
    }

    // ==========================================
    // 13. KIEM TRA HET HAN THANH TOAN MOMO
    // ==========================================

    public function isPaymentExpired(): bool
    {
        return $this->isMomo()
            && !$this->isPaid()
            && $this->TrangThai !== self::DA_HUY
            && $this->HanThanhToan !== null
            && now()->greaterThanOrEqualTo(
                $this->HanThanhToan
            );
    }

    // ==========================================
    // 14. LAY SO GIAY CON LAI DE THANH TOAN
    // ==========================================

    public function getRemainingPaymentSecondsAttribute(): int
    {
        if (
            !$this->isMomo()
            || $this->isPaid()
            || $this->TrangThai === self::DA_HUY
            || $this->HanThanhToan === null
        ) {
            return 0;
        }

        return max(
            0,
            (int) now()->diffInSeconds(
                $this->HanThanhToan,
                false
            )
        );
    }

    // ==========================================
    // 15. KIEM TRA DON HANG CO THE THANH TOAN
    // ==========================================

    public function canPay(): bool
    {
        return $this->isMomo()
            && !$this->isPaid()
            && $this->TrangThai !== self::DA_HUY
            && $this->HanThanhToan !== null
            && !$this->isPaymentExpired();
    }

    // ==========================================
    // 16. TINH TONG TIEN DON HANG
    // ==========================================

    public function getTongTienAttribute(): string
    {
        return number_format(
            (float) $this->TienHang
                + (float) $this->PhiVanChuyen,
            2,
            '.',
            ''
        );
    }
}

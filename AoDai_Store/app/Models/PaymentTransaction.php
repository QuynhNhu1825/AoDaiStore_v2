<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'GiaoDichThanhToan';

    protected $primaryKey = 'MaGiaoDich';

    public $timestamps = false;

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'MaDonHang',
        'PhuongThucThanhToan',
        'MaThanhToan',
        'MaYeuCauThanhToan',
        'MaGiaoDichCongThanhToan',
        'SoTien',
        'TrangThaiGiaoDich',
        'NgayTao',
        'NgayCapNhat',
    ];

    protected $casts = [
        'MaGiaoDich' => 'integer',
        'MaDonHang' => 'integer',
        'SoTien' => 'decimal:2',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
    ];

    public const DANG_CHO = 'DangCho';
    public const THANH_CONG = 'ThanhCong';
    public const THAT_BAI = 'ThatBai';
    public const HET_HAN = 'HetHan';
    public const CAN_DOI_SOAT = 'CanDoiSoat';

    public const COD = 'cod';
    public const MOMO = 'momo';

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'MaDonHang',
            'MaDonHang'
        );
    }

    public function isSuccessful(): bool
    {
        return $this->TrangThaiGiaoDich === self::THANH_CONG;
    }

    public function isPending(): bool
    {
        return $this->TrangThaiGiaoDich === self::DANG_CHO;
    }

    public function isFailed(): bool
    {
        return $this->TrangThaiGiaoDich === self::THAT_BAI;
    }

    public function isExpired(): bool
    {
        return $this->TrangThaiGiaoDich === self::HET_HAN;
    }

    public function needsReconciliation(): bool
    {
        return $this->TrangThaiGiaoDich === self::CAN_DOI_SOAT;
    }

    protected static function booted(): void
    {
        static::creating(function ($transaction) {
            $transaction->NgayTao ??= now();
        });

        static::updating(function ($transaction) {
            $transaction->NgayCapNhat = now();
        });
    }
}

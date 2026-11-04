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
        'NgayCapNhat',
    ];

    protected $casts = [
        'TienHang' => 'decimal:2',
        'PhiVanChuyen' => 'decimal:2',
        'NgayDat' => 'datetime',
        'NgayCapNhat' => 'datetime',
    ];

    /**
     * Chuyển trạng thái đơn hàng sang tiếng Việt.
     */
    public function getTrangThaiTextAttribute()
    {
        return match ($this->TrangThai) {
            'ChoXacNhan' => 'Chờ xác nhận',
            'DaXacNhan' => 'Đã xác nhận',
            'DangGiao' => 'Đang giao',
            'DaGiao' => 'Đã giao',
            'DaHuy' => 'Đã hủy',
            default => $this->TrangThai,
        };
    }

    /**
     * Một đơn hàng có nhiều chi tiết đơn hàng.
     */
    public function orderDetails()
    {
        return $this->hasMany(
            OrderDetail::class,
            'MaDonHang',
            'MaDonHang'
        );
    }
}

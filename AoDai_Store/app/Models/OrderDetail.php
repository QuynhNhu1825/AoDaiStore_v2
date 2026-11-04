<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'chitietdonhang';

    protected $primaryKey = 'MaChiTietDH';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'MaChiTietDH',
        'MaDonHang',
        'MaChiTietSP',
        'SoLuong',
        'DonGia',
    ];

    protected $casts = [
        'SoLuong' => 'integer',
        'DonGia' => 'decimal:2',
        'ThanhTien' => 'decimal:2',
    ];

    /**
     * Chi tiết đơn hàng thuộc về một đơn hàng.
     */
    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'MaDonHang',
            'MaDonHang'
        );
    }

    /**
     * Chi tiết đơn hàng thuộc về một chi tiết sản phẩm.
     */
    public function productDetail()
    {
        return $this->belongsTo(
            ProductDetail::class,
            'MaChiTietSP',
            'MaChiTietSP'
        );
    }
}

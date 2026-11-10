<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'chitietdonhang';

    protected $primaryKey = 'MaChiTietDH';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'MaDonHang',
        'MaChiTietSP',
        'SoLuong',
        'DonGia',
        'ThanhTien',
    ];

    protected $casts = [
        'SoLuong' => 'integer',
        'DonGia' => 'decimal:2',
        'ThanhTien' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'MaDonHang',
            'MaDonHang'
        );
    }

    public function productDetail()
    {
        return $this->belongsTo(
            ProductDetail::class,
            'MaChiTietSP',
            'MaChiTietSP'
        );
    }
}
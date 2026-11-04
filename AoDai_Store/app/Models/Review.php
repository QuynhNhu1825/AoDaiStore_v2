<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'danhgia';

    protected $primaryKey = 'MaDanhGia';

    public $timestamps = false;

    protected $fillable = [
        'MaTaiKhoan',
        'MaSanPham',
        'MaDonHang',
        'SoSao',
        'NoiDung',
        'TrangThai',
        'NgayDanhGia',
    ];

    protected $casts = [
        'SoSao' => 'integer',
        'TrangThai' => 'boolean',
        'NgayDanhGia' => 'datetime',
    ];

    /**
     * Tài khoản đã gửi đánh giá.
     */
    public function account()
    {
        return $this->belongsTo(
            Account::class,
            'MaTaiKhoan',
            'MaTaiKhoan'
        );
    }

    /**
     * Sản phẩm được đánh giá.
     */
    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'MaSanPham',
            'MaSanPham'
        );
    }

    /**
     * Đơn hàng liên quan đến đánh giá.
     */
    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'MaDonHang',
            'MaDonHang'
        );
    }
}
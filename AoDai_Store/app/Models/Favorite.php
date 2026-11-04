<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'yeuthich';

    protected $primaryKey = 'MaYeuThich';

    public $timestamps = false;

    protected $fillable = [
        'MaTaiKhoan',
        'MaSanPham',
        'NgayThem',
    ];

    protected $casts = [
        'NgayThem' => 'datetime',
    ];

    /**
     * Tài khoản đã yêu thích sản phẩm.
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
     * Sản phẩm được yêu thích.
     */
    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'MaSanPham',
            'MaSanPham'
        );
    }
}
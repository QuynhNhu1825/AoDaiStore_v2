<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'giohang';

    protected $primaryKey = 'MaGioHang';

    public $timestamps = false;

    protected $fillable = [
        'MaTaiKhoan',
        'MaChiTietSP',
        'SoLuong',
        'NgayThem',
    ];

    protected $casts = [
        'SoLuong' => 'integer',
        'NgayThem' => 'datetime',
    ];

    /**
     * Một mục giỏ hàng thuộc về một biến thể/chi tiết sản phẩm.
     */
    public function productDetail()
    {
        return $this->belongsTo(
            ProductDetail::class,
            'MaChiTietSP',
            'MaChiTietSP'
        );
    }

    /**
     * Một mục giỏ hàng thuộc về một tài khoản.
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
     * Lấy tổng số lượng sản phẩm trong giỏ hàng.
     */
    public static function getCartQuantity()
    {
        if (Auth::check()) {
            return self::where(
                'MaTaiKhoan',
                Auth::id()
            )->sum('SoLuong');
        }

        return 0;
    }
}
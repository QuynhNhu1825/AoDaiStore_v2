<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'yeuthich';

    protected $primaryKey = 'MaYeuThich';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'MaTaiKhoan',
        'MaSanPham',
        'NgayThem'
    ];

    protected $casts = [
        'NgayThem' => 'datetime'
    ];

    // Yêu thích thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'MaSanPham',
            'MaSanPham'
        );
    }
}
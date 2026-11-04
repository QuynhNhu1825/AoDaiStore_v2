<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'size';

    protected $primaryKey = 'MaSize';

    public $timestamps = false;

    protected $fillable = [
        'TenSize',
        'NguoiTao',
        'NgayTao',
        'NguoiCapNhat',
        'NgayCapNhat',
        'NguoiXoa',
        'NgayXoa',
        'IsDeleted',
    ];

    protected $casts = [
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
        'NgayXoa' => 'datetime',
        'IsDeleted' => 'boolean',
    ];

    /**
     * Quan hệ nhiều-nhiều với sản phẩm thông qua bảng trung gian sanpham_size.
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'sanpham_size',
            'MaSize',
            'MaSanPham'
        )->withPivot('SoLuong');
    }
}
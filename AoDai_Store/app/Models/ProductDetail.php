<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'chitietsanpham';

    protected $primaryKey = 'MaChiTietSP';

    public $timestamps = false;

    protected $fillable = [
        'MaSanPham',
        'MaMau',
        'MaSize',
        'Gia',
        'SoLuong',
        'NguoiTao',
        'NgayTao',
        'NguoiCapNhat',
        'NgayCapNhat',
        'NguoiXoa',
        'NgayXoa',
        'IsDeleted',
    ];

    protected $casts = [
        'Gia' => 'decimal:2',
        'SoLuong' => 'integer',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
        'NgayXoa' => 'datetime',
        'IsDeleted' => 'boolean',
    ];

    /**
     * Chi tiết sản phẩm thuộc một sản phẩm.
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
     * Chi tiết sản phẩm có một màu sắc.
     */
    public function color()
    {
        return $this->belongsTo(
            Color::class,
            'MaMau',
            'MaMau'
        );
    }

    /**
     * Chi tiết sản phẩm có một size.
     */
    public function size()
    {
        return $this->belongsTo(
            Size::class,
            'MaSize',
            'MaSize'
        );
    }
}
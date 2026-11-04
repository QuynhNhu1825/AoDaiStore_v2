<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'sanpham';

    protected $primaryKey = 'MaSanPham';

    public $timestamps = false;

    protected $fillable = [
        'MaChatLieu',
        'TenSanPham',
        'MoTa',
        'HinhAnh',
        'TrangThai',
        'NguoiTao',
        'NgayTao',
        'NguoiCapNhat',
        'NgayCapNhat',
        'NguoiXoa',
        'NgayXoa',
        'IsDeleted',
    ];

    protected $casts = [
        'TrangThai' => 'boolean',
        'IsDeleted' => 'boolean',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
        'NgayXoa' => 'datetime',
    ];

    /**
     * Quan hệ với chất liệu
     */
    public function chatlieu()
    {
        return $this->belongsTo(
            Material::class,
            'MaChatLieu',
            'MaChatLieu'
        );
    }

    /**
     * Quan hệ với hình ảnh sản phẩm
     */
    public function hinhanhsanpham()
    {
        return $this->hasMany(
            ProductImage::class,
            'MaSanPham',
            'MaSanPham'
        );
    }
    public function favorites()
    {
        return $this->hasMany(
            Favorite::class,
            'MaSanPham',
            'MaSanPham'
        );
    }
    /**
     * Quan hệ với các chi tiết sản phẩm
     */
    public function chiTietSanPham()
    {
        return $this->hasMany(
            ProductDetail::class,
            'MaSanPham',
            'MaSanPham'
        );
    }

    /**
     * Lấy các sản phẩm liên quan theo chất liệu
     */
    public function getRelatedProducts($limit = 4)
    {
        return self::where('MaSanPham', '<>', $this->MaSanPham)
            ->where('MaChatLieu', $this->MaChatLieu)
            ->where('TrangThai', 1)
            ->where('IsDeleted', 0)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
        public function danhMucs()
    {
        return $this->belongsToMany(
            Category::class,
            'sanpham_danhmuc',
            'MaSanPham',
            'MaDanhMuc'
        );
    }
}
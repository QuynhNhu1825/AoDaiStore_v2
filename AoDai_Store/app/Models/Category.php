<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'danhmucsp';

    protected $primaryKey = 'MaDanhMuc';

    public $timestamps = false;

    protected $fillable = [
        'TenDanhMuc',
        'MoTa',
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
 * Một danh mục có thể chứa nhiều sản phẩm.
 */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'sanpham_danhmuc',
            'MaDanhMuc',
            'MaSanPham'
        );
    }
}

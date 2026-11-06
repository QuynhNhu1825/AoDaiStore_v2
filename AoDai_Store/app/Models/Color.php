<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'mausac';

    protected $primaryKey = 'MaMau';

    public $timestamps = false;

    protected $fillable = [
        'TenMau',
        'MaHex',
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

    // Một màu có thể được sử dụng trong nhiều chi tiết sản phẩm
    public function productDetail()
    {
        return $this->hasMany(
            ProductDetail::class,
            'MaMau',
            'MaMau'
        );
    }
}

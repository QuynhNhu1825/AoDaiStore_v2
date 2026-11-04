<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'chatlieu';

    protected $primaryKey = 'MaChatLieu';

    public $timestamps = false;

    protected $fillable = [
        'TenChatLieu',
        'XuatXu',
        'HuongDanBaoQuan',
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
     * Một chất liệu có nhiều sản phẩm.
     */
    public function products()
    {
        return $this->hasMany(
            Product::class,
            'MaChatLieu',
            'MaChatLieu'
        );
    }
}

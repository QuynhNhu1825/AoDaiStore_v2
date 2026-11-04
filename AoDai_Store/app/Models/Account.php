<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $table = 'taikhoan';

    protected $primaryKey = 'MaTaiKhoan';

    public $timestamps = false;

    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'HoTen',
        'Email',
        'SDT',
        'DiaChi',
        'Role',
        'TrangThai',
        'NguoiTao',
        'NgayTao',
        'NguoiCapNhat',
        'NgayCapNhat',
        'NguoiXoa',
        'NgayXoa',
        'IsDeleted',
    ];

    protected $hidden = [
        'MatKhau',
    ];

    protected $casts = [
        'TrangThai' => 'boolean',
        'IsDeleted' => 'boolean',
        'NgayTao' => 'datetime',
        'NgayCapNhat' => 'datetime',
        'NgayXoa' => 'datetime',
    ];

    /**
     * Lấy mật khẩu dùng cho quá trình xác thực của Laravel.
     */
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    /**
     * Tên cột chứa mật khẩu đã mã hóa.
     */
    public function getAuthPasswordName()
    {
        return 'MatKhau';
    }
    public function favorites()
{
    return $this->hasMany(
        Favorite::class,
        'MaTaiKhoan',
        'MaTaiKhoan'
    );
}
}
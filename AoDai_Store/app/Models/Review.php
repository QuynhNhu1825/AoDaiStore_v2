<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    use HasFactory;

    protected $table = 'danhgia';

    protected $primaryKey = 'MaDanhGia';

    public $timestamps = false;

    protected $fillable = [
        'MaTaiKhoan',
        'MaSanPham',
        'MaDonHang',
        'SoSao',
        'NoiDung',
        'TrangThai',
        'NgayDanhGia',
    ];

    protected $casts = [
        'SoSao' => 'integer',
        'TrangThai' => 'boolean',
        'NgayDanhGia' => 'datetime',
    ];

    public static function imageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $filename = basename(str_replace('\\', '/', $path));

        return self::imageFile($filename) !== null
            ? route('rating.image', [], false) . '?file=' . rawurlencode($filename)
            : null;
    }

    public static function imageFile(string $filename): ?string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($filename !== basename(str_replace('\\', '/', $filename))
            || preg_match('/[:\x00-\x1f\x7f]/', $filename)
            || !in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'], true)
        ) {
            return null;
        }

        $uploadedPath = Storage::disk('public')->path('ratings/' . $filename);
        if (is_file($uploadedPath)) {
            return $uploadedPath;
        }

        $legacyPath = public_path('img/ratings/' . $filename);

        return is_file($legacyPath) ? $legacyPath : null;
    }

    /**
     * Tài khoản đã gửi đánh giá.
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
     * Sản phẩm được đánh giá.
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
     * Đơn hàng liên quan đến đánh giá.
     */
    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'MaDonHang',
            'MaDonHang'
        );
    }
}

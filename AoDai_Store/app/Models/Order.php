<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Order extends Model
{
    use HasFactory;

    protected $table = 'donhang';

    protected $primaryKey = 'MaDonHang';

    public $timestamps = true;

    const CREATED_AT = 'NgayDat';
    const UPDATED_AT = 'NgayCapNhat';

    public const STATUS_LABELS = [
        'ChoXacNhan' => 'Chờ xác nhận',
        'DaXacNhan' => 'Đã xác nhận',
        'DangGiao' => 'Đang giao',
        'DaGiao' => 'Đã giao',
        'DaHuy' => 'Đã hủy',
    ];

    protected $fillable = [
        'MaTaiKhoan',
        'HoTenNguoiNhan',
        'SDTNguoiNhan',
        'DiaChiNhan',
        'TienHang',
        'PhiVanChuyen',
        'PhuongThucThanhToan',
        'TrangThai',
        'GhiChu',
        'NgayDat',
        'NgayCapNhat',
        'TrangThaiThanhToan',
        'NgayThanhToan',
        'TrangThaiYeuCauHuy',
        'NgayYeuCauHuy',
        'NgayXuLyYeuCauHuy',
        'NguoiXuLyYeuCauHuy',
    ];

    protected $casts = [
        'TienHang' => 'decimal:2',
        'PhiVanChuyen' => 'decimal:2',
        'NgayDat' => 'datetime',
        'NgayCapNhat' => 'datetime',
        'NgayThanhToan' => 'datetime',
        'NgayYeuCauHuy' => 'datetime',
        'NgayXuLyYeuCauHuy' => 'datetime',
        'NguoiXuLyYeuCauHuy' => 'integer',
    ];

    /**
     * Chuyển trạng thái đơn hàng sang tiếng Việt.
     */
    public function getTrangThaiTextAttribute()
    {
        return self::STATUS_LABELS[$this->TrangThai] ?? match ($this->TrangThai) {
            'ChuaThanhToan' => 'Chưa thanh toán',
            'ChoXacNhanThanhToan' => 'Chờ xác nhận thanh toán',
            'DaThanhToan' => 'Đã thanh toán',
            default => $this->TrangThai,
        };
    }

    public function scopeStatusCounts(Builder $query): Collection
    {
        return $query->selectRaw('TrangThai, COUNT(*) as total')
            ->groupBy('TrangThai')
            ->pluck('total', 'TrangThai')
            ->map(fn ($total) => (int) $total);
    }

    /**
     * Hiển thị trạng thái yêu cầu hủy.
     */
    public function getTrangThaiYeuCauHuyTextAttribute()
    {
        return match ($this->TrangThaiYeuCauHuy ?? 'KhongCo') {
            'KhongCo' => 'Chưa yêu cầu hủy',
            'ChoDuyet' => 'Đang chờ duyệt hủy',
            'DaDuyet' => 'Đã chấp nhận hủy',
            'TuChoi' => 'Đã từ chối hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Kiểm tra khách hàng có thể hủy đơn.
     */
    public function canCancel()
    {
        // Đơn chờ xác nhận được hủy trực tiếp
        if ($this->TrangThai === 'ChoXacNhan') {
            return true;
        }

        // Đơn đã xác nhận được gửi yêu cầu hủy
        if ($this->TrangThai === 'DaXacNhan') {
            return ($this->TrangThaiYeuCauHuy ?? 'KhongCo')
                === 'KhongCo';
        }

        return false;
    }

    /**
     * Kiểm tra yêu cầu hủy đang chờ Admin duyệt.
     */
    public function hasPendingCancellation()
    {
        return $this->TrangThai === 'DaXacNhan'
            && $this->TrangThaiYeuCauHuy === 'ChoDuyet';
    }

    public function canPayWithVietQr(): bool
    {
        return $this->PhuongThucThanhToan === 'vietqr'
            && $this->TrangThai === 'ChoXacNhan'
            && $this->TrangThaiThanhToan === 'ChuaThanhToan';
    }

    /**
     * Một đơn hàng có nhiều chi tiết đơn hàng.
     */
    public function orderDetails()
    {
        return $this->hasMany(
            OrderDetail::class,
            'MaDonHang',
            'MaDonHang'
        );
    }
}

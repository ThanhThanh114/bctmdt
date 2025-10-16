<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatVe extends Model
{
    use HasFactory;

    protected $table = 'dat_ve';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'chuyen_xe_id',
        'ma_ve',
        'so_ghe',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_dat' => 'datetime'
    ];

    // Accessor to get status in English
    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        return match ($this->trang_thai) {
            'Đã thanh toán' => 'confirmed',
            'Đã đặt' => 'pending',
            'Đã hủy' => 'cancelled',
            default => 'pending'
        };
    }

    // Scope for easier status queries
    public function scopeWhereStatus($query, $status)
    {
        $vietnameseStatus = match ($status) {
            'confirmed' => 'Đã thanh toán',
            'pending' => 'Đã đặt',
            'cancelled' => 'Đã hủy',
            default => $status
        };

        return $query->where('trang_thai', $vietnameseStatus);
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function chuyenXe()
    {
        return $this->belongsTo(ChuyenXe::class, 'chuyen_xe_id', 'id');
    }

    public function khuyenMais()
    {
        return $this->belongsToMany(KhuyenMai::class, 've_khuyenmai', 'dat_ve_id', 'ma_km');
    }
}

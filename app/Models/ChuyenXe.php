<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuyenXe extends Model
{
    use HasFactory;

    protected $table = 'chuyen_xe';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ma_xe',
        'ten_xe',
        'ma_nha_xe',
        'ten_tai_xe',
        'sdt_tai_xe',
        'ma_tram_di',
        'ma_tram_den',
        'ngay_di',
        'gio_di',
        'gio_den',
        'loai_xe',
        'so_cho',
        'so_ve',
        'gia_ve',
        'loai_chuyen'
    ];

    protected $casts = [
        'ngay_di' => 'date',
        'gio_di' => 'datetime:H:i:s',
        'gia_ve' => 'decimal:2'
    ];

    // Relationships
    public function nhaXe()
    {
        return $this->belongsTo(NhaXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function tramDi()
    {
        return $this->belongsTo(TramXe::class, 'ma_tram_di', 'ma_tram_xe');
    }

    public function tramDen()
    {
        return $this->belongsTo(TramXe::class, 'ma_tram_den', 'ma_tram_xe');
    }

    public function datVe()
    {
        return $this->hasMany(DatVe::class, 'chuyen_xe_id', 'id');
    }

    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'chuyen_xe_id', 'id');
    }

    public function tuyenPhoBien()
    {
        return $this->hasMany(TuyenPhoBien::class, 'ma_xe', 'id');
    }

    // Scopes
    public function scopeByRoute($query, $start, $end)
    {
        return $query->whereHas('tramDi', function ($q) use ($start) {
            $q->where('ten_tram', 'like', "%{$start}%");
        })->whereHas('tramDen', function ($q) use ($end) {
            $q->where('ten_tram', 'like', "%{$end}%");
        });
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('ngay_di', $date);
    }

    public function scopeByBusType($query, $busType)
    {
        if (!empty($busType) && $busType !== 'Tất cả') {
            return $query->where('loai_xe', $busType);
        }
        return $query;
    }

    public function scopeByTripType($query, $tripType)
    {
        if ($tripType === 'oneway') {
            return $query->where('loai_chuyen', 'Một chiều');
        } elseif ($tripType === 'round') {
            return $query->where('loai_chuyen', 'Khứ hồi');
        }
        return $query;
    }
}

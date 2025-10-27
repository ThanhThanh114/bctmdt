<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai';
    protected $primaryKey = 'ma_km';
    public $timestamps = false;

    protected $fillable = [
        'ten_km',
        'ma_code',
        'giam_gia',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ma_nha_xe'
    ];

    protected $casts = [
        'giam_gia' => 'decimal:2',
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now());
    }

    // Relationships
    public function nhaXe()
    {
        return $this->belongsTo(NhaXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function veKhuyenMai()
    {
        return $this->hasMany(VeKhuyenMai::class, 'ma_km', 'ma_km');
    }

    public function datVes()
    {
        return $this->belongsToMany(DatVe::class, 've_khuyenmai', 'ma_km', 'dat_ve_id');
    }
}

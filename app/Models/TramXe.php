<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TramXe extends Model
{
    use HasFactory;

    protected $table = 'tram_xe';
    protected $primaryKey = 'ma_tram_xe';
    public $timestamps = false;

    protected $fillable = [
        'ten_tram',
        'dia_chi',
        'dia_chi_tram',
        'tinh_thanh',
        'ma_nha_xe',
        'latitude',
        'longitude'
    ];

    // Relationships
    public function nhaXe()
    {
        return $this->belongsTo(NhaXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function chuyenXeDi()
    {
        return $this->hasMany(ChuyenXe::class, 'ma_tram_di', 'ma_tram_xe');
    }

    public function chuyenXeDen()
    {
        return $this->hasMany(ChuyenXe::class, 'ma_tram_den', 'ma_tram_xe');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhan_vien';
    protected $primaryKey = 'ma_nv';
    public $timestamps = false;

    protected $fillable = [
        'ten_nv',
        'chuc_vu',
        'so_dien_thoai',
        'email',
        'ngay_sinh',
        'gioi_tinh',
        'cccd',
        'dia_chi',
        'ma_nha_xe'
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
    ];

    // Relationships
    public function nhaXe()
    {
        return $this->belongsTo(NhaXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }
}
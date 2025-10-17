<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaXe extends Model
{
    use HasFactory;

    protected $table = 'nha_xe';
    protected $primaryKey = 'ma_nha_xe';
    public $timestamps = false;

    protected $fillable = [
        'ten_nha_xe',
        'dia_chi',
        'so_dien_thoai',
        'email'
    ];

    // Relationships
    public function chuyenXe()
    {
        return $this->hasMany(ChuyenXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function nhanVien()
    {
        return $this->hasMany(NhanVien::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function tinTuc()
    {
        return $this->hasMany(TinTuc::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function tramXe()
    {
        return $this->hasMany(TramXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'ma_nha_xe', 'ma_nha_xe');
    }
}

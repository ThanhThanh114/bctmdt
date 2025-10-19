<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinTuc extends Model
{
    use HasFactory;

    protected $table = 'tin_tuc';
    protected $primaryKey = 'ma_tin';
    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'hinh_anh',
        'user_id',
        'ma_nha_xe',
        'ngay_dang'
    ];

    protected $casts = [
        'ngay_dang' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function nhaXe()
    {
        return $this->belongsTo(NhaXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }
}
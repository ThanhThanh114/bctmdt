<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuyenPhoBien extends Model
{
    use HasFactory;

    protected $table = 'tuyenphobien';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ma_xe',
        'imgtpb',
        'soluongdatdi'
    ];

    protected $casts = [
        'soluongdatdi' => 'integer'
    ];

    // Relationships
    public function chuyenXe()
    {
        return $this->belongsTo(ChuyenXe::class, 'ma_xe', 'id');
    }
}
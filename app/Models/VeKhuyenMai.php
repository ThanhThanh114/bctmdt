<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeKhuyenMai extends Model
{
    use HasFactory;

    protected $table = 've_khuyenmai';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dat_ve_id',
        'ma_km'
    ];

    // Relationships
    public function datVe()
    {
        return $this->belongsTo(DatVe::class, 'dat_ve_id', 'id');
    }

    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'ma_km', 'ma_km');
    }
}

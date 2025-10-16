<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ProfanityFilter;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luan';
    protected $primaryKey = 'ma_bl';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'user_id',
        'chuyen_xe_id',
        'noi_dung',
        'noi_dung_tl',
        'so_sao',
        'trang_thai',
        'ngay_duyet',
        'ly_do_tu_choi',
        'nv_id',
        'ngay_bl',
        'ngay_tl',
        'ngay_tao'
    ];

    protected $casts = [
        'ngay_bl' => 'datetime',
        'ngay_tl' => 'datetime',
        'ngay_duyet' => 'datetime',
        'so_sao' => 'integer'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-moderation: Reviews with 1-2 stars require approval
        static::creating(function ($binhLuan) {
            // Only apply to parent comments (not replies)
            if (is_null($binhLuan->parent_id) && !is_null($binhLuan->so_sao) && $binhLuan->so_sao > 0) {
                // Auto-moderate low ratings (1-2 stars)
                if ($binhLuan->so_sao <= 2) {
                    $binhLuan->trang_thai = 'cho_duyet';
                }
            }

            // Filter profanity in content
            if (!empty($binhLuan->noi_dung)) {
                $binhLuan->noi_dung = ProfanityFilter::filter($binhLuan->noi_dung);
            }
        });

        // Also filter on update
        static::updating(function ($binhLuan) {
            if (!empty($binhLuan->noi_dung) && $binhLuan->isDirty('noi_dung')) {
                $binhLuan->noi_dung = ProfanityFilter::filter($binhLuan->noi_dung);
            }
        });
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

    public function parent()
    {
        return $this->belongsTo(BinhLuan::class, 'parent_id', 'ma_bl');
    }

    public function replies()
    {
        return $this->hasMany(BinhLuan::class, 'parent_id', 'ma_bl');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    public function scopeApproved($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }

    public function scopeRejected($query)
    {
        return $query->where('trang_thai', 'tu_choi');
    }
}

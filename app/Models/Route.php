<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'origin',
        'destination',
        'distance',
        'duration_minutes',
        'created_by'
    ];

    protected $casts = [
        'distance' => 'decimal:2'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stations()
    {
        return $this->belongsToMany(Station::class, 'route_stations')->withPivot('order')->orderBy('pivot_order');
    }
}

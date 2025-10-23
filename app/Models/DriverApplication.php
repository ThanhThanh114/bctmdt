<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'bus_owner_id',
        'license_number',
        'license_image',
        'status',
        'notes',
        'application_date',
        'approval_date'
    ];

    protected $casts = [
        'application_date' => 'datetime',
        'approval_date' => 'datetime'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function busOwner()
    {
        return $this->belongsTo(User::class, 'bus_owner_id');
    }
}

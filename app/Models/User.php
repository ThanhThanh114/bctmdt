<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'fullname',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'otp_code',
        'otp_expires_at',
        'reset_token',
        'reset_token_expires_at',
        'role',
        'ma_nha_xe'
    ];

    // Disable timestamps because they don't exist in database
    public $timestamps = false;

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'reset_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_verified' => 'boolean',
        'otp_expires_at' => 'datetime',
        'reset_token_expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function datVe()
    {
        return $this->hasMany(DatVe::class, 'user_id', 'id');
    }

    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'user_id', 'id');
    }

    public function tinTuc()
    {
        return $this->hasMany(TinTuc::class, 'user_id', 'id');
    }

    public function nhaXe()
    {
        return $this->belongsTo(NhaXe::class, 'ma_nha_xe', 'ma_nha_xe');
    }

    public function upgradeRequests()
    {
        return $this->hasMany(UpgradeRequest::class, 'user_id');
    }

    public function approvedRequests()
    {
        return $this->hasMany(UpgradeRequest::class, 'approved_by');
    }

    // Helper methods
    public function isUser()
    {
        return strtolower($this->role) === 'user';
    }

    public function isBusOwner()
    {
        return strtolower($this->role) === 'bus_owner';
    }

    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    public function canUpgrade()
    {
        return $this->isUser() && !$this->hasPendingUpgradeRequest();
    }

    public function hasPendingUpgradeRequest()
    {
        return $this->upgradeRequests()
            ->whereIn('status', ['pending', 'payment_pending', 'paid'])
            ->exists();
    }

    public function getActiveUpgradeRequest()
    {
        return $this->upgradeRequests()
            ->whereIn('status', ['pending', 'payment_pending', 'paid'])
            ->latest()
            ->first();
    }
}

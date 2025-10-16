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
        'role'
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
}

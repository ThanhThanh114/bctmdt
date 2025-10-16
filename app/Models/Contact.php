<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'branch',
        'fullname',
        'email',
        'phone',
        'subject',
        'message'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
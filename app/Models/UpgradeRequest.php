<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type',
        'amount',
        'status',
        'reason',
        'business_info',
        'admin_note',
        'approved_by',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'business_info' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaymentPending($query)
    {
        return $query->where('status', 'payment_pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaymentPending()
    {
        return $this->status === 'payment_pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'payment_pending']);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'payment_pending' => 'badge-info',
            'paid' => 'badge-primary',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'cancelled' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'payment_pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }
}

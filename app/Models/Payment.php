<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'upgrade_request_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'bank_name',
        'account_number',
        'account_name',
        'qr_code_url',
        'payment_proof',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function upgradeRequest()
    {
        return $this->belongsTo(UpgradeRequest::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'completed' => 'badge-success',
            'failed' => 'badge-danger',
            'refunded' => 'badge-info',
            default => 'badge-secondary'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Chờ thanh toán',
            'completed' => 'Đã thanh toán',
            'failed' => 'Thất bại',
            'refunded' => 'Đã hoàn tiền',
            default => 'Không xác định'
        };
    }

    public function getPaymentMethodLabel()
    {
        return match($this->payment_method) {
            'bank_transfer' => 'Chuyển khoản',
            'qr_code' => 'QR Code',
            'cash' => 'Tiền mặt',
            default => 'Không xác định'
        };
    }
}

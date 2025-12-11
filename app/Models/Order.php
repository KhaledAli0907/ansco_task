<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subscription_id',
        'user_id',
        'paymob_order_id',
        'payment_token',
        'payment_url',
        'amount_cents',
        'paid_amount_cents',
        'currency',
        'payment_status',
        'payment_method',
        'merchant_order_id',
        'transaction_id',
        'integration_id',
        'is_successful',
        'transaction_response_code',
        'transaction_message',
        'source_data_type',
        'source_data_pan',
        'is_refunded',
        'is_voided',
        'refunded_amount_cents',
        'hmac',
        'shipping_data',
        'paymob_order_response',
        'paymob_callback_response',
        'notes',
    ];

    protected $casts = [
        'shipping_data' => 'array',
        'paymob_order_response' => 'array',
        'paymob_callback_response' => 'array',
        'is_successful' => 'boolean',
        'is_refunded' => 'boolean',
        'is_voided' => 'boolean',
        'amount_cents' => 'integer',
        'paid_amount_cents' => 'integer',
        'refunded_amount_cents' => 'integer',
        'paymob_order_id' => 'integer',
        'transaction_id' => 'integer',
        'integration_id' => 'integer',
    ];

    /**
     * Get the subscription that owns the order.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscribtion::class, 'subscription_id');
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope a query to only include failed orders.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Check if order is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' && $this->is_successful;
    }

    /**
     * Check if order is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if order is refunded.
     */
    public function isRefunded(): bool
    {
        return $this->is_refunded || $this->payment_status === 'refunded';
    }

    /**
     * Check if order is voided.
     */
    public function isVoided(): bool
    {
        return $this->is_voided;
    }

    /**
     * Get the user subscription created from this order.
     */
    public function userSubscription()
    {
        return $this->hasOne(UserSubscription::class);
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getSubscriptionId(): string
    {
        return $this->subscription_id;
    }
}

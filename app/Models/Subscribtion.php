<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscribtion extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'duration',
        'duration_type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeDeleted($query)
    {
        return $query->where('status', 'deleted');
    }

    /**
     * Get the orders for the subscription.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'subscription_id');
    }

    /**
     * Get the user subscriptions for this subscription plan.
     */
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'subscription_id');
    }
}

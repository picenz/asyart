<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'email',
        'first_name',
        'last_name',
        'address',
        'apartment',
        'city',
        'postal_code',
        'province',
        'phone',
        'delivery_method',
        'total_price',
        'status',
        'courier',
        'tracking_number',
        'shipped_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
{
    return $this->hasOne(Payment::class, 'order_id');
}
}

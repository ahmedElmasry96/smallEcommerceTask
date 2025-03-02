<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_price', 'tax_value', 'discount_value', 'payment_status', 'payment_method'];

    // payment methods
    const COD = 1;

    // payment status
    const Paid = 1;
    const unPaid = 2;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}

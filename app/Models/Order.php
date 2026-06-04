<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'invoice_number', 'status', 'subtotal', 'shipping_cost', 'discount', 'total', 'coupon_code', 'address', 'city', 'phone', 'notes', 'shipping_courier', 'payment_method', 'payment_status'];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->invoice_number = 'INV-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function trackings()
    {
        return $this->hasMany(OrderTracking::class)->oldest();
    }
}

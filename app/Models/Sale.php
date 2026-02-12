<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'purchase_id',
        'customer_id',
        'sold_at',
        'sold_price',
        'payment_method',
        'channel',
        'notes',
        'total_items',
        'grand_total',
    ];

    // (Opcional) relaciones si ya las usas
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}

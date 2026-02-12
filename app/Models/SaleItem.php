<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'name',
        'qty',
        'price',
        'type',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

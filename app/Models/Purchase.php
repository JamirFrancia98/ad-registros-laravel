<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    protected $table = 'purchases';

    protected $fillable = [
        'purchase_date',
        'supplier_id',
        'iphone_model_id',
        'storage_option_id',
        'color_id',
        'imei1',
        'imei2',
        'serial',
        'imei_photo_path',
        'phone_photo_path',
        'purchase_price',
        'sale_price',
        'markup',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function iphoneModel()
    {
        return $this->belongsTo(IphoneModel::class, 'iphone_model_id');
    }

    public function storageOption()
    {
        return $this->belongsTo(StorageOption::class, 'storage_option_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function sale()
    {
        return $this->hasOne(Sale::class);
    }
}
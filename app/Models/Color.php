<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'colors';

    protected $fillable = ['iphone_model_id', 'name'];

    public function iphoneModel()
    {
        return $this->belongsTo(IphoneModel::class, 'iphone_model_id');
    }
}
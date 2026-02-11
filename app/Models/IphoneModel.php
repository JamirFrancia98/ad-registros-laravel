<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IphoneModel extends Model
{
    protected $table = 'iphone_models';

    protected $fillable = ['name'];

    public function colors()
    {
        return $this->hasMany(Color::class, 'iphone_model_id');
    }
}
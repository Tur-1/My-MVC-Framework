<?php

namespace App\Models;

use TurFramework\Database\Model;

class Brand extends Model
{

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}

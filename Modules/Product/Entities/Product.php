<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'image_url',
        'product_name',
        'categories',
    ];
}

<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'product_code',
        'unit',
        'cost_price',
        'sell_price',
        'category_id',
        'sub_category_id',
        'description',
        'image',
        'status',
    ];
}

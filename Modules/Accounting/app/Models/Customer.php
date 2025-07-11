<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Accounting\Database\Factories\CustomerFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'first_name',
        'last_name',
        'opening_balance',
        'opening_balance_as_of',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'fax',
        'website',
        'notes',
        'customer_type',
        'status',
        'user_id',
        'profile_image',
    ];
}

<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Container extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'container_number',
        'container_type',
        'shipping_line',
        'port_of_loading',
        'port_of_discharge',
        'estimated_departure',
        'estimated_arrival',
        'actual_arrival',
        'remarks',
        'status',
        'lc_number',
        'bank_name',
        'attachment',
    ];

    protected $casts = [
        'estimated_departure' => 'datetime',
        'estimated_arrival' => 'datetime',
        'actual_arrival' => 'datetime',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalInvoicedAmountAttribute()
    {
        return $this->invoices->sum('total_amount');
    }
}

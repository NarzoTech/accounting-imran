<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'investor_id',
        'amount',
        'investment_date',
        'expected_profit',
        'total_repaid',
        'remarks',
    ];
    protected $casts = [
        'investment_date' => 'date',
    ];


    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}

<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Accounting\Database\Factories\RepaymentFactory;

class Repayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'investment_id',
        'investor_id',
        'amount',
        'repayment_date',
        'notes',
    ];

    protected $casts = [
        'repayment_date' => 'date',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }
}

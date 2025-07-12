<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }


    public function getInvestmentsSumAmountAttribute()
    {
        return $this->investments()->sum('amount');
    }

    public function getTotalRepaidAttribute()
    {
        return $this->repayments()->sum('amount');
    }

    public function getRepaymentsSumAmountAttribute()
    {
        return $this->repayments()->sum('amount');
    }
}

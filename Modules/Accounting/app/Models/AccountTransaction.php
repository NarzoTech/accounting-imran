<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'reference',
        'note',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

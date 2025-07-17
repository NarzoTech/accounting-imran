<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'type',
        'related_id',
        'related_type',
        'amount',
        'method',
        'note',
        'date'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function related()
    {
        return $this->morphTo(__FUNCTION__, 'related_type', 'related_id');
    }
}

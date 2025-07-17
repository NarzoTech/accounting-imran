<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'amount',
        'account_id',
        'container_id',
        'payment_method',
        'reference',
        'attachment',
        'note',
        'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'related');
    }
}

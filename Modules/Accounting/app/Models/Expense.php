<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
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

    /**
     * Get the account that owns the expense.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the container that the expense belongs to.
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }
}

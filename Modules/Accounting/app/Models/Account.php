<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'provider',
        'balance',
        'note'

    ];

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    public function outgoingTransfers()
    {
        return $this->hasMany(AccountTransfer::class, 'from_account_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(AccountTransfer::class, 'to_account_id');
    }
}

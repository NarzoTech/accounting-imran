<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * We remove 'balance' as it will be a calculated attribute.
     */
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'provider',
        'note'
    ];

    /**
     * Get the computed balance of the account.
     * This sums all related transactions where amount is positive (income)
     * and subtracts where amount is negative (expense/outgoing).
     * Assuming positive amounts in AccountTransaction are income/deposits
     * and negative amounts are expenses/withdrawals.
     * Or, more accurately, sum based on transaction type if amount is always positive.
     */
    public function getBalanceAttribute($value)
    {
        // Re-calculate balance based on transactions if they are the source of truth.
        // Sum deposits, invoice_payments, advance_payments, transfer_in
        $incomes = $this->transactions()
            ->whereIn('type', ['deposit', 'invoice_payment', 'advance_payment', 'transfer_in'])
            ->sum('amount');

        // Sum expenses, transfer_out
        $expenses = $this->transactions()
            ->whereIn('type', ['expense', 'transfer_out'])
            ->sum('amount');

        $discounts = $this->transactions()
            ->where('type', 'discount')
            ->sum('amount');
        return ($incomes + $discounts) - ($expenses);
    }


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

    public function payments()
    {

        return $this->hasMany(InvoicePayment::class);
    }
}

<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'invoice_id',
        'account_id',
        'customer_id',
        'amount',
        'payment_type',
        'method',
        'group_id',
        'note',
    ];

    /**
     * Get the invoice that the payment belongs to.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the account used for the payment.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the customer associated with this payment (through the invoice).
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Accounting\Database\Factories\CustomerFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'first_name',
        'last_name',
        'opening_balance',
        'opening_balance_as_of',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'fax',
        'website',
        'notes',
        'customer_type',
        'status',
        'user_id',
        'profile_image',
    ];

    protected $appends = ['current_balance'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoicePayments()
    {
        return $this->hasManyThrough(InvoicePayment::class, Invoice::class);
    }

    public function getTotalInvoicedAmountAttribute()
    {
        return $this->invoices()->sum('total_amount');
    }

    public function getTotalInvoicePaidAmountAttribute()
    {
        return $this->invoicePayments()->where('payment_type', 'invoice_payment')->sum('amount');
    }

    /**
     * Calculate the total advance amount paid by this customer.
     */
    public function getTotalAdvancePaidAmountAttribute()
    {
        return $this->invoicePayments()->where('payment_type', 'advance')->sum('amount');
    }

    /**
     * Calculate the current balance of the customer (positive for due, negative for advance/credit).
     */
    public function getCurrentBalanceAttribute()
    {
        $totalInvoiced = $this->total_invoiced_amount;
        $totalPaid = $this->total_invoice_paid_amount;
        $totalAdvance = $this->total_advance_paid_amount;

        // Balance = (Opening Balance + Total Invoiced) - (Total Invoice Payments + Total Advance Payments)
        // A positive result means the customer owes money (due).
        // A negative result means the customer has a credit/advance.
        return ($this->opening_balance + $totalInvoiced) - ($totalPaid + $totalAdvance);
    }

    /**
     * Get the current due amount for the customer.
     */
    public function getDueAmountAttribute()
    {
        $balance = $this->current_balance;
        return $balance > 0 ? $balance : 0;
    }

    /**
     * Get the current advance amount for the customer.
     */
    public function getAdvanceAmountAttribute()
    {
        $balance = $this->current_balance;
        return $balance < 0 ? abs($balance) : 0;
    }
}

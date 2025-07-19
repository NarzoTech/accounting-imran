<?php

namespace Modules\Accounting\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'po_so_number',
        'invoice_date',
        'payment_date',
        'container_id',
        'discount_percentage',
        'delivery_charge',
        'subtotal',
        'total_amount',
        'notes_terms',
        'invoice_footer'
    ];

    protected $appends = ['amount_paid', 'amount_due', 'payment_status'];
    protected $casts = [
        'invoice_date' => 'date',
        'payment_date' => 'date',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * Calculate the total amount paid for this invoice.
     */
    public function getAmountPaidAttribute()
    {
        return $this->payments()->where('payment_type', 'invoice_payment')->sum('amount');
    }

    /**
     * Calculate the remaining due amount for this invoice.
     */
    public function getAmountDueAttribute()
    {
        return $this->total_amount - $this->amount_paid;
    }

    /**
     * Get the payment status of the invoice.
     *
     * @return string 'Unpaid', 'Partially Paid', 'Paid'
     */
    public function getPaymentStatusAttribute()
    {
        if ($this->amount_paid >= $this->total_amount) {
            return 'Paid';
        } elseif ($this->amount_paid > 0 && $this->amount_paid < $this->total_amount) {
            return 'Partially Paid';
        }
        return 'Unpaid';
    }

    /**
     * Check if the invoice is overdue.
     * An invoice is overdue if its payment_date is in the past AND it's not fully paid.
     */
    public function getIsOverdueAttribute()
    {
        return $this->payment_date < Carbon::today() && $this->payment_status !== 'Paid';
    }
}

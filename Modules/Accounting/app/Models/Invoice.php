<?php

namespace Modules\Accounting\Models;

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
        return $this->hasMany(Payment::class);
    }
}

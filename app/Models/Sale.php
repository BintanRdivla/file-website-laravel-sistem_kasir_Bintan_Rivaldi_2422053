<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 'customer_name', 'customer_phone', 
        'subtotal', 'discount', 'tax', 'total_amount', 
        'payment_method', 'paid_amount', 'change_amount', 'user_id'
    ];

    public function items() {
        return $this->hasMany(SaleItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }
}
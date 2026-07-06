<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'po_number', 'distributor_id', 'purchase_date', 
        'subtotal', 'discount', 'tax', 'total_amount', 
        'status', 'payment_status', 'notes', 'user_id'
    ];

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }

    public function items() {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
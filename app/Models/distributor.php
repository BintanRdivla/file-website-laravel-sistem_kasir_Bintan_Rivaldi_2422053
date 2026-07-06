<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 🚨 PENTING UNTUK SOFT DELETE

class Distributor extends Model
{
    use SoftDeletes; // Aktifkan fitur soft delete sesuai checklist 

    protected $fillable = ['name', 'phone', 'address', 'credit_limit'];

    // Relasi ke Transaksi Pembelian (Untuk Purchase History nanti) 
    public function purchases()
    {
        // Asumsi nama model transaksi pembelian nanti adalah Purchase
        return $this->hasMany(Purchase::class); 
    }
}
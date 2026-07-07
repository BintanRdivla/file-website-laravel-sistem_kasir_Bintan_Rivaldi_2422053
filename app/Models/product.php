<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // PERBAIKAN: Tambahkan 'stock' ke dalam array $fillable di bawah ini
    protected $fillable = [
        'code', 
        'name', 
        'category_id', 
        'purchase_price', 
        'selling_price', 
        'stock', // <-- Tambahkan ini untuk membuka proteksi kolom stok
        'min_stock', 
        'max_stock'
    ];

    public function icon()
    {
        return $this->belongsTo(Icon::class, 'icon_id');
    }

    // Hubungan ke Kategori (Many-to-One)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
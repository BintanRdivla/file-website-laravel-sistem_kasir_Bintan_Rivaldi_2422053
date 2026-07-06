<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('code')->unique(); // Barcode / Kode Produk
        $table->string('name');
        $table->decimal('purchase_price', 12, 2); // Harga Beli (Modal)
        $table->decimal('selling_price', 12, 2);  // Harga Jual
        $table->integer('stock')->default(0);
        $table->integer('min_stock')->default(5); // Alert stok minimum
        $table->integer('max_stock')->default(100);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

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
    Schema::create('stock_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity'); // Jumlah perubahan (bisa positif/negatif)
        $table->enum('type', ['add', 'reduce', 'damaged']); // Sesuai spesifikasi dokumen 
        $table->string('reason')->nullable(); // Alasan perubahan (misal: "Salah input", "Tikus", "Exfired")
        $table->foreignId('user_id')->constrained(); // Mencatat siapa yang mengubah (Audit Trail) 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};

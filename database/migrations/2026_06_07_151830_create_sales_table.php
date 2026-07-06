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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique(); // Nomor struk otomatis (ex: INV-20260607-0001)
        $table->string('customer_name')->nullable()->default('General Customer');
        $table->string('customer_phone')->nullable();
        $table->decimal('subtotal', 15, 2);
        $table->decimal('discount', 15, 2)->default(0);
        $table->decimal('tax', 15, 2)->default(0);
        $table->decimal('total_amount', 15, 2);
        $table->enum('payment_method', ['Cash', 'Debit', 'Credit', 'Transfer Bank'])->default('Cash'); // Sesuai dokumen
        $table->decimal('paid_amount', 15, 2); // Uang yang dibayarkan
        $table->decimal('change_amount', 15, 2)->default(0); // Uang kembalian
        $table->foreignId('user_id')->constrained(); // Kasir yang bertugas (Audit Trail)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

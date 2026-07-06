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
    Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->string('po_number')->unique(); // Nomor nota PO otomatis (ex: PO-20260607-0001)
        $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
        $table->date('purchase_date');
        $table->decimal('subtotal', 15, 2)->default(0);
        $table->decimal('discount', 15, 2)->default(0);
        $table->decimal('tax', 15, 2)->default(0);
        $table->decimal('total_amount', 15, 2)->default(0);
        $table->enum('status', ['Draft', 'Confirmed', 'Received', 'Completed'])->default('Draft'); // Sesuai dokumen
        $table->enum('payment_status', ['Unpaid', 'Paid'])->default('Unpaid');
        $table->string('notes')->nullable();
        $table->foreignId('user_id')->constrained(); // Audit Trail (siapa pembuat PO)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

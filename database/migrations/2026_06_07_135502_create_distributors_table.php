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
    Schema::create('distributors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone');
        $table->text('address');
        $table->decimal('credit_limit', 12, 2)->default(0); // Pelacakan batas utang
        $table->softDeletes(); // Fitur Soft Delete sesuai checklist
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};

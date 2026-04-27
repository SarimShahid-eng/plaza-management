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
        Schema::create('unit_assesment_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unit_payment_id')->constrained('unit_assesment_payments');
            $table->foreignId('assessment_id');
            $table->foreignId('plaza_id');
            $table->foreignId('unit_id');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_type', ['App', 'Cash', 'BankTransfer', 'Cheque', 'Card']);
            $table->string('payment_month', 7);
            $table->foreignId('recorded_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_assesment_payment_histories');
    }
};

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('monthly_due_id');

            $table->foreignId('plaza_id');
            $table->foreignId('unit_id');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_type', ["App","Cash","BankTransfer","Cheque","Card"]);
            $table->string('payment_month', 7);
            // $table->enum('status', ["PENDING_APPROVAL","APPROVED","REJECTED"])->default('PENDING_APPROVAL');
            $table->foreignId('recorded_by');
            // $table->boolean('is_late')->default(false);
            // $table->string('reference_number')->nullable();
            // $table->foreignId('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

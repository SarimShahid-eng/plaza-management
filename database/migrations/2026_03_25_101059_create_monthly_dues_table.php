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
        Schema::create('monthly_dues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('plaza_id')->constrained('plazas')->onDelete('cascade');

            $table->string('month'); // "2024-03"

            $table->decimal('monthly_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);

            $table->enum('status', ['UNPAID', 'PAID', 'PARTIAL', 'OVERDUE'])->default('UNPAID');

            $table->date('due_date');
            $table->timestamp('payment_date')->nullable();

            $table->timestamps();

            $table->index(['unit_id', 'month']);
            $table->index(['plaza_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_dues');
    }
};

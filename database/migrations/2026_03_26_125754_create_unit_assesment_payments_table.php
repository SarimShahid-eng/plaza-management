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
        Schema::create('unit_assesment_payments', function (Blueprint $table) {
            $table->id();

            // Foreign keys
             $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('plaza_id')->constrained('plazas')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('special_assessments')->onDelete('cascade');

            // Amount details
            $table->decimal('assessed_amount', 15, 2)->comment('Amount that was assessed to this unit');
            $table->decimal('paid_amount', 15, 2)->default(0)->comment('Amount paid so far');
            $table->decimal('remaining_amount', 15, 2)->comment('Amount still owed');

            // Status tracking
            $table->enum('status', ['UNPAID', 'PAID', 'PARTIAL', 'OVERDUE'])
                ->default('UNPAID')
                ->comment('UNPAID=no payment, PAID=fully paid, PARTIAL=partial payment, OVERDUE=late');
            $table->date('payment_date')->nullable()->comment('When it was paid');

            // Indexes
            $table->index('unit_id');
            $table->index('plaza_id');
            $table->index('assessment_id');
            $table->index('status');
            // $table->index('due_date');

            // Composite index for common queries
            $table->index(['plaza_id', 'status']);
            $table->index(['unit_id', 'status']);
            $table->index(['assessment_id', 'status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_assesment_payments');
    }
};

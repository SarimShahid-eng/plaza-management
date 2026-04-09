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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plaza_id');
            //denotes who owns it currently
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('unit_number');
            $table->integer('floor')->nullable();
            $table->enum('status', ['Occupied', 'Vacant'])->default('Vacant');
            $table->decimal('due', 15, 2)->default(0);
            $table->decimal('monthly_dues_amount', 15, 2);
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};

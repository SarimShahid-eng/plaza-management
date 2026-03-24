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
        Schema::create('plaza_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plaza_id')->unique();
            $table->decimal('maintenance_approval_threshold', 15, 2);
            $table->decimal('monthly_dues_amount', 15, 2);
            $table->decimal('late_fee_percentage', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plaza_settings');
    }
};

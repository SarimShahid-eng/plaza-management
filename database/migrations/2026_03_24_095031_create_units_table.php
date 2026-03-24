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
            $table->string('unit_number');
            $table->integer('floor')->nullable();
            $table->enum('status', ["Paid","Pending","Vacant"])->default('Vacant');
            $table->enum('unit_type', ["1bhk","2bhk","3bhk","studio","penthouse","other"]);
            $table->string('resident_name')->nullable();
            $table->string('resident_phone')->nullable();
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

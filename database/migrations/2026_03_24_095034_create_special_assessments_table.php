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
        Schema::create('special_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plaza_id');
            $table->string('title');
            $table->text('reason');
            $table->decimal('required_amount', 15, 2);
            $table->decimal('shortfall', 15, 2);
            $table->integer('occupied_units');
            $table->decimal('per_unit_amount', 15, 2);
            $table->enum('status', ["PENDING","APPROVED","REJECTED","APPROVED_AND_IMPLEMENTED"]);
            $table->foreignId('created_by');
            $table->foreignId('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_assessments');
    }
};

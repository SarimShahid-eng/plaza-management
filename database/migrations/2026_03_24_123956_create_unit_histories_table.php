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
        Schema::create('unit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('unit_id');
            $table->foreignId('plaza_id');
            $table->integer('changed_by');
            $table->date('move_in')->nullable();
            $table->date('move_out')->nullable();
            $table->decimal('due_at_checkout',15,2)->nullable();
            // $table->enum('status', ['Paid', 'Pending', 'Vacant'])->default('Vacant');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_histories');
    }
};

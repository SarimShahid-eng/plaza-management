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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id');
            $table->string('subject');
            $table->enum('category', ["Plumbing","Electrical","Cleaning","Noise","Security","Safety","Other"]);
            $table->text('description');
            $table->enum('status', ["Pending","InProgress","Resolved"])->default('Pending');
            $table->enum('priority', ["Low","Medium","High","Urgent"])->default('Low');
            $table->foreignId('assigned_to')->nullable();
            $table->foreignId('created_by');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

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
        Schema::create('maintenance_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plaza_id');
            $table->string('title');
            $table->enum('category', ["Plumbing","Electrical","Cleaning","Generator","Repair","Security","Painting","HVAC","Other"]);
            $table->decimal('cost', 15, 2);
            // $table->enum('status', ["IMPLEMENTED","PENDING_APPROVAL","APPROVED","REJECTED"]);
            $table->foreignId('created_by');
            $table->foreignId('linked_assessment_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_posts');
    }
};

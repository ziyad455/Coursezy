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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // enrollment, review, payment, course_view, course_click
            $table->text('description');
            $table->longText('data')->nullable(); // Store additional data as JSON
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who performed the action
            $table->foreignId('coach_id')->nullable()->constrained('users')->onDelete('cascade'); // Coach related to this activity
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('cascade'); // For enrollments
            $table->decimal('amount', 10, 2)->nullable(); // For payments
            $table->integer('rating')->nullable(); // For reviews
            $table->boolean('is_read')->default(false); // To track if coach has seen this
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['coach_id', 'created_at']);
            $table->index(['type', 'coach_id']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

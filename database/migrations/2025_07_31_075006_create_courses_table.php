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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->decimal('price', 8, 2);
        $table->string('thumbnail')->nullable();
        $table->foreignId('coach_id')->constrained('users');
        $table->foreignId('category_id')->constrained();
        $table->enum('status', ['published', 'draft'])->default('draft');
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

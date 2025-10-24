<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('copy_id')->constrained('copies')->cascadeOnDelete();
            $table->dateTime('borrowed_at')->default(now());
            $table->dateTime('due_at')->nullable()->index();
            $table->dateTime('returned_at')->nullable()->index();
            $table->enum('status', ['borrowed','returned','overdue','lost'])->default('borrowed')->index();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('loans');
    }
};
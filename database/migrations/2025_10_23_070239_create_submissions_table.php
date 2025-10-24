<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('text_answer')->nullable();
            $table->enum('status', ['draft','submitted','graded'])->default('submitted')->index();
            $table->dateTime('submitted_at')->nullable();
            $table->integer('score')->nullable();
            $table->text('grade_feedback')->nullable();
            $table->unsignedBigInteger('graded_by')->nullable();
            $table->timestamps();

            $table->foreign('graded_by')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('submissions');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('barcode')->nullable()->index();
            $table->enum('copy_type', ['physical','digital'])->default('physical');
            $table->enum('status', ['available','borrowed','reserved','lost'])->default('available')->index();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('copies');
    }
};

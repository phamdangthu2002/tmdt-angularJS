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
        Schema::create('anhsanphams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanpham_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->integer('order')->nullable(); // Sắp xếp thứ tự ảnh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anhsanphams');
    }
};

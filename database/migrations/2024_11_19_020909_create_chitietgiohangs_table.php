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
        Schema::create('chitietgiohangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giohang_id')->constrained()->onDelete('cascade');
            $table->foreignId('sanpham_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->string('ram');
            $table->string('rom');
            $table->string('color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

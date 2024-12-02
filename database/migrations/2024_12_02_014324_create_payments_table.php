<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donhang_id')->constrained('donhangs')->onDelete('cascade');
            $table->enum('phuongthuc', ['Visa', 'MasterCard', 'Momo', 'COD']);
            $table->enum('trangthai', ['pending', 'completed', 'failed'])->default('pending');
            $table->decimal('tong', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

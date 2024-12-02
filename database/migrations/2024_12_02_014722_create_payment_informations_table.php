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
        Schema::create('payment_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('phuongthuc', ['Visa', 'MasterCard', 'Momo', 'COD', 'Paypal']);
            $table->enum('trangthai', ['pending', 'completed', 'failed', 'canceled'])->default('pending');
            $table->string('transaction_id')->unique()->nullable();
            $table->decimal('tong', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_informations');
    }
};

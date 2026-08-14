<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_gateway')->default('qris_simulated'); // qris_simulated, cash, midtrans, etc.
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('PENDING'); // PENDING, PAID, FAILED
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

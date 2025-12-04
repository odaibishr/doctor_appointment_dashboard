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
        Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
    $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete(); 
    // $table->foreignId('payment_gateway_detail_id')->nullable()->constrained('payment_gateway_details')->nullOnDelete(); 
    $table->decimal('amount', 10, 2)->default(0);
    $table->string('status')->default('pending'); // pending, paid, failed
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

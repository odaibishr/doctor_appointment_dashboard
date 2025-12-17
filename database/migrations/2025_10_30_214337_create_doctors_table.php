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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('aboutus')->nullable();
            $table->foreignId(column: 'specialty_id')->constrained('specialties')->nullOnDelete();      
            $table->foreignId('hospital_id')
                ->nullable()
                ->constrained('hospitals')
                ->nullOnDelete();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->integer('experience')->default(0);

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_top_doctor')->default(false);

            $table->date('birthday')->nullable();

            $table->longText('services')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_waitlists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();
            
            $table->date('preferred_date')->nullable();
            
            $table->foreignId('preferred_schedule_id')
                ->nullable()
                ->constrained('doctor_schedules')
                ->nullOnDelete();
            
            $table->enum('status', ['waiting', 'notified', 'booked', 'expired', 'cancelled'])
                ->default('waiting');
            
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('position')->default(0);
            
            $table->timestamps();
            
            $table->unique(['user_id', 'doctor_id', 'status'], 'unique_active_waitlist');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_waitlists');
    }
};

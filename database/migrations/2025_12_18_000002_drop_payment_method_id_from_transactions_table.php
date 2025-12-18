<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('transactions', 'payment_method_id')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'payment_method_id')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table
                ->foreignId('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->nullOnDelete()
                ->after('user_id');
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('transactions', 'payment_gateway_detail_id')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table
                ->foreignId('payment_gateway_detail_id')
                ->nullable()
                ->constrained('payment_gateway_details')
                ->nullOnDelete()
                ->after('payment_method_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('transactions', 'payment_gateway_detail_id')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_gateway_detail_id');
        });
    }
};


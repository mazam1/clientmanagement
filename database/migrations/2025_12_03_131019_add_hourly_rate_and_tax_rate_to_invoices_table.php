<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->default(0)->after('session_ids');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('hourly_rate');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate', 'tax_rate']);
        });
    }
};

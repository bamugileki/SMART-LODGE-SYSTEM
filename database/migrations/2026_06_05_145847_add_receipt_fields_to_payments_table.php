<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->unique()->after('transaction_id');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('processed_by');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['receipt_number', 'verified_at']);
            $table->dropForeign(['verified_by']);
            $table->dropColumn('verified_by');
        });
    }
};

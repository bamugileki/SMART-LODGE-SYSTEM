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
        Schema::table('users', function (Blueprint $table) {
            $table->string('national_id')->nullable()->after('phone');
            $table->text('address')->nullable()->after('national_id');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('address');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('country')->nullable()->after('date_of_birth');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['national_id', 'address', 'gender', 'date_of_birth', 'country', 'status']);
        });
    }
};

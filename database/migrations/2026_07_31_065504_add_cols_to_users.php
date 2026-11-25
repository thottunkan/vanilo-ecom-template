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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();

            // Profile
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();

            // Preferences
            $table->string('language', 10)->default('en');
            $table->string('currency', 10)->default('INR');

            // Login Information
            $table->string('last_login_ip', 45)->nullable(); // Supports IPv4 & IPv6
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('gender');
            $table->dropColumn('avatar');
            $table->dropColumn('bio');
            $table->dropColumn('language');
            $table->dropColumn('currency');
            $table->dropColumn('last_login_ip');
        });
    }
};
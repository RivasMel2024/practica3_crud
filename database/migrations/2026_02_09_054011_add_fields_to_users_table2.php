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
            $table->string('dui', 10)->unique()->after('username');
            $table->string('phone', 15)->nullable()->after('dui');
            $table->date('birthdate')->after('phone');
            $table->date('hiring_date')->after('password');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dui', 'phone', 'birthdate', 'hiring_date']);
            $table->dropSoftDeletes();
        });
    }
};

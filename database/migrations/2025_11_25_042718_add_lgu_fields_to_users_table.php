<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('municipality')->nullable()->after('role');   // e.g. 'Santa Fe', 'Bantayan'
            $table->string('lgu_logo')->nullable()->after('municipality'); // storage path to logo
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['municipality', 'lgu_logo']);
        });
    }
};

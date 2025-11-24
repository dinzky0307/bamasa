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
       Schema::table('businesses', function (Blueprint $table) {
        // Thumbnail image path (nullable)
        $table->string('thumbnail')->nullable()->after('max_price');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
        $table->dropColumn('thumbnail');
    });
    }
};

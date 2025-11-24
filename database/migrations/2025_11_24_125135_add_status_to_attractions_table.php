<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            // published / draft
            $table->string('status')->default('published')->after('thumbnail');
            // If you don't have 'thumbnail' yet, you can just use ->after('name') or remove ->after()
        });
    }

    public function down(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};



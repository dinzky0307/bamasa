<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();      // beach, church, landmark, etc.
            $table->text('description')->nullable();
            $table->string('municipality')->nullable();  // Bantayan, Santa Fe, Madridejos
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('opening_hours')->nullable(); // "8AM–5PM"
            $table->string('entrance_fee')->nullable();  // "₱50", "Free"
            $table->string('thumbnail')->nullable();     // image path
            $table->string('status')->default('published'); // published / draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');

            // These MUST exist because your seeder uses them
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id');

            // Make rating nullable for now so factory/seeder won't crash
            $table->unsignedTinyInteger('rating')->nullable(); // 1–5
            $table->text('comment')->nullable();

            $table->timestamps();

            // We removed foreign keys elsewhere to avoid FK errors,
            // so do NOT add ->foreign() constraints here for now.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

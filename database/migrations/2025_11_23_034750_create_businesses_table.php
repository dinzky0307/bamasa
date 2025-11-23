<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // ensure InnoDB

            $table->bigIncrements('id'); // BIGINT UNSIGNED

            // Match users.id exactly: BIGINT UNSIGNED
            $table->unsignedBigInteger('user_id');

            $table->string('name');
            $table->string('category'); // resort, hotel, etc.
            $table->text('description')->nullable();

            $table->string('address')->nullable();
            $table->string('municipality')->nullable(); // Bantayan, Santa Fe, Madridejos

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook_page')->nullable();

            $table->string('status')->default('pending'); // pending, approved, rejected

            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();

            $table->timestamps();

            // Foreign key (defined manually to avoid magic issues)
           // $table->foreign('user_id')
             //     ->references('id')
               //   ->on('users')
                 // ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};

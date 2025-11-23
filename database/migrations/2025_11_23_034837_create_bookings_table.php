<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');

            // IMPORTANT: these two must exist because your seeder uses them
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id');

            // Make these nullable for now so seeding won't fail
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();

            $table->unsignedInteger('guests')->default(1);
            $table->string('status')->default('pending'); // pending, approved, etc.
            $table->text('notes')->nullable();
            $table->text('owner_reply')->nullable();

            $table->timestamps();

            // We removed foreign keys earlier to avoid FK errors,
            // so do NOT add ->foreign() here for now.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

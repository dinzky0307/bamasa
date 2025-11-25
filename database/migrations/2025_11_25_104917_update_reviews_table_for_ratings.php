<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // If these columns already exist, you can comment out as needed.

            if (!Schema::hasColumn('reviews', 'business_id')) {
                $table->foreignId('business_id')
                    ->after('id')
                    ->constrained()
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('reviews', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('business_id')
                    ->constrained()
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->unsignedTinyInteger('rating')
                    ->after('user_id'); // 1–5
            }

            if (!Schema::hasColumn('reviews', 'comment')) {
                $table->text('comment')->nullable()->after('rating');
            }

            // One review per user per business
            $table->unique(['business_id', 'user_id'], 'reviews_business_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_business_user_unique');
            // You can also drop the added columns if you want:
            // $table->dropColumn(['business_id', 'user_id', 'rating', 'comment']);
        });
    }
};



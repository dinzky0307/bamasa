<?php

public function up(): void
{
    Schema::create('tour_packages', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->string('municipality')->nullable();
        $table->text('description')->nullable();
        $table->integer('duration_hours')->nullable(); // e.g. 4, 8
        $table->decimal('price_per_person', 10, 2)->nullable();
        $table->text('includes')->nullable(); // bullet list text
        $table->string('thumbnail')->nullable();
        $table->enum('status', ['draft', 'published'])->default('published');
        $table->timestamps();
    });
}


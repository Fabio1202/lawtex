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
        Schema::create('law_books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short');
            $table->timestamps();
        });

        Schema::create('laws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('law_book_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            //URL to the law
            $table->string('url');
            // Belong to one project
            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laws');
    }
};

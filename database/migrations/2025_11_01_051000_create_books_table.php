<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_id', 8);
            $table->string('title');
            $table->string('cover_path')->nullable();
            $table->string('author') -> nullable();
            $table->integer('category_id') -> nullable();
            $table->integer('published_year')->nullable();
            $table->string('description')->nullable();
            $table->integer('total_copies');
            $table->integer('available_copies');
            $table->enum('language',['english','khmer']);
            $table->enum('source',['donated', 'purchased', 'sponsored', 'other']);
            $table->integer('added_by');
            $table->string('snapshot_added_by');
            $table->integer('updated_by')->nullable();
            $table->string('snapshot_updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

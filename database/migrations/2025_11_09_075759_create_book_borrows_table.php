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
        Schema::create('book_borrows', function (Blueprint $table) {
            $table->id();

            $table->integer('member_id');
            $table->integer('book_id');

            $table->date('borrowed_date');
            $table->date('due_date');
            $table->date('returned_date')->nullable();

            // 🟩 Condition of the physical copy when returning it
            $table->string('condition_before', 50)->default('As New');  // optional but useful
            $table->string('condition_after', 50)->nullable();           // assigned on return

            $table->string('status', 50)->default('borrowed');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_borrows');
    }
};

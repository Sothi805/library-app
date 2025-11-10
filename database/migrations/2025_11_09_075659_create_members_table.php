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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['male', 'female']);
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->datetime('inactive_since')->nullable();
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
        Schema::dropIfExists('members');
    }
};

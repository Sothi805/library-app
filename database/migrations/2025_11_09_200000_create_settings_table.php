<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('type')->default('string');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default borrow duration setting
        DB::table('settings')->insert([
            'key' => 'borrow_duration_days',
            'value' => '14', // Default to 14 days
            'type' => 'integer',
            'description' => 'Number of days a book can be borrowed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

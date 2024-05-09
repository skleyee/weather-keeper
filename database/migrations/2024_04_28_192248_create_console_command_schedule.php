<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('console_command_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tg_user_id');
            $table->string('schedule')->nullable();
            $table->string('push_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('console_command_schedule');
    }
};

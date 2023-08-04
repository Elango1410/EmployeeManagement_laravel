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
        //
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->char('token',20);
            $table->enum('type',['free','premium']);
            $table->string('title');
            $table->text('description');
            $table->tinyInteger('status');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('notifications');
    }
};

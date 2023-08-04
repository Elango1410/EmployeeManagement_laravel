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
        Schema::create('users_notification', function (Blueprint $table) {
            $table->id();
            $table->string('token',45);
            $table->char('user_token',20);
            $table->char('notification_token',20);
            $table->enum('status',['pending','completed']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('users_notification');
    }
};

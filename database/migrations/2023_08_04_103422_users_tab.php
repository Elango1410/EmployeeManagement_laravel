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
        Schema::create('users_tab', function (Blueprint $table) {
            $table->id();
            $table->string('token',20);
            $table->string('name',30);
            $table->string('mobile_number',30);
            $table->string('email',100);
            $table->string('image',255);
            $table->enum('type',['free','normal']);
            $table->enum('device_type',['web','andriod','ios']);
            $table->string('device_toke',20);
            $table->enum('status',['0','1','2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('users_tab');
    }
};

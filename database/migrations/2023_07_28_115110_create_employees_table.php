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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('token')->index();
            $table->string('name');
            $table->string('email');
            $table->string('department');
            $table->index('department');
            $table->foreign('department')->references('token')->on('departments');
            // $table->string('skill');
            // $table->index('skill');
            // $table->foreign('skill')->references('token')->on('skills');
            $table->string('contact_no');
            $table->date('dob');
            $table->string('blood_group');
            $table->string('address');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

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
        Schema::create('membership_plans',function(Blueprint $table){
            $table->id();
            $table->string('plan_id');
            $table->string('plan_name');
            $table->string('plan_duration');
            $table->string('plan_amount');
            $table->text('benefits');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('membership_plans');
    }
};

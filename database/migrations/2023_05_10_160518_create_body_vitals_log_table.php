<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_vitals_log', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('user_profile_uuid')->unique();
            $table->decimal('body_weight', 10,2)->nullable();
            $table->decimal('body_height', 10,2)->nullable();
            $table->decimal('blood_pressure', 10,2)->nullable();
            $table->decimal('pulse_rate', 10,2)->nullable();
            $table->decimal('respiratory_rate', 10,2)->nullable();
            $table->decimal('temprature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_vitals_log');
    }
};

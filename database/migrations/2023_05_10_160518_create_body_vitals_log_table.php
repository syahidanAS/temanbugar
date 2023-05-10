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
            $table->float('body_weight')->nullable();
            $table->float('body_height')->nullable();
            $table->float('blood_pressure')->nullable();
            $table->float('pulse_rate')->nullable();
            $table->float('respiratory_rate')->nullable();
            $table->float('temprature')->nullable();
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

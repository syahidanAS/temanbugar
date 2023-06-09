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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('meal_name');
            $table->decimal('calorie', 10,2);
            $table->decimal('fat', 10,2);
            $table->decimal('carbohydrate', 10,2);
            $table->decimal('proteins', 10,2);
            $table->string('images')->nullable();
            $table->uuid('created_by');
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
        Schema::dropIfExists('meals');
    }
};

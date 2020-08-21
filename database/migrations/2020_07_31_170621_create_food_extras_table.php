<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_extras', function (Blueprint $table) {
            $table->bigInteger('food_id')->unsigned();
            $table->bigInteger('extra_id')->unsigned();
            $table->primary([
                'food_id',
                'extra_id'
            ]);

            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
                ->onDelete('cascade');
            $table->foreign('extra_id')
                ->references('id')
                ->on('extras')
                ->onDelete('cascade');
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
        Schema::dropIfExists('food_extras');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillDetailExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_detail_extras', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_detail_id')->unsigned();
            $table->bigInteger('extra_id')->unsigned();
            $table->integer('price');
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
        Schema::dropIfExists('bill_detail_extras');
    }
}
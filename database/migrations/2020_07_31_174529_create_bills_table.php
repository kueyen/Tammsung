<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('table_id')->unsigned();
            $table->integer('price_sum')->nullable();
            $table->integer('discount')->nullable();
            $table->boolean('status')->default(1);

            $table->bigInteger('coupon_id')->nullable()->unsigned();

            $table->foreign('table_id')
                ->references('id')
                ->on('tables')
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
        Schema::dropIfExists('bills');
    }
}
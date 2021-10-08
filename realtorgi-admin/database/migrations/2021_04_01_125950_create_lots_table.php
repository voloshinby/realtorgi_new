<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('lot_number')->nullable();
            $table->integer('auction_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->text('info')->nullable();
            $table->integer('price_start')->nullable()->unsigned();
            $table->integer('price_min')->nullable()->unsigned();
            $table->integer('price_step')->nullable()->unsigned();
            $table->integer('step')->unsigned()->nullable();
            $table->integer('deposit')->nullable()->unsigned();
            $table->text('images')->nullable();
            $table->text('files')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('status')->nullable()->default('Черновик');
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('lots');
    }
}

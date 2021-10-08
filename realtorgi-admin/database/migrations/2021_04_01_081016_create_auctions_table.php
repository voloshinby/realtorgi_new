<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('auction_number')->nullable();
            $table->text('organizer_requisites')->nullable();
            $table->integer('seller_id')->unsigned()->nullable();
            $table->integer('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->string('seller_custom')->nullable();
            $table->string('seller_phone')->nullable();
            $table->text('requisites')->nullable();
            $table->string('anticrisis_manager')->nullable();
            $table->string('anticrisis_manager_phone')->nullable();
            $table->text('how_to_view_the_property')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('compensation')->nullable();
            $table->integer('step')->unsigned()->nullable();
            $table->text('additional_info')->nullable();
            $table->string('starts_at')->nullable();
            $table->string('ends_at')->nullable();
            $table->string('start_selling')->nullable();
            $table->string('end_selling')->nullable();
            $table->string('images')->nullable();
            $table->string('files')->nullable();
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
        Schema::dropIfExists('auctions');
    }
}

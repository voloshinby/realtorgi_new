<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAuctionConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_confirms', function (Blueprint $table) {
            $table->boolean('isUserRequestedToDelete')->after('confirmed_user')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_confirms', function (Blueprint $table) {
            $table->dropColumn('isUserRequestedToDelete');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('type_user')->nullable();
            $table->string('full_name')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport_issuer')->nullable();
            $table->timestamp('passportDate')->nullable();
            $table->string('passport_personal')->nullable();
            $table->string('registration_address')->nullable();
            $table->string('residence_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('inn')->nullable();
            $table->string('short_name')->nullable();
            $table->timestamp('egr_date')->nullable();
            $table->string('manager_data')->nullable();
            $table->string('place_of_residence')->nullable();
            $table->string('bank_details')->nullable();
            $table->string('legal_entity')->nullable();
            $table->smallInteger('active')->default(0);
            $table->smallInteger('confirmed')->nullable();
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
        Schema::dropIfExists('user_notifications');
    }
}

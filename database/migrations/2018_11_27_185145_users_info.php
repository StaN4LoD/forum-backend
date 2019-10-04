<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('avatar')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('about')->nullable();
            $table->string('job')->nullable();
            $table->string('location')->nullable();
            $table->integer('rating')->default(0);
            $table->timestamp('email_verified')->nullable();
            $table->timestamps();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

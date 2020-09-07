<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likees', function (Blueprint $table) {
            $table->id();

            $table->text('nickName')->nullable();
            $table->text('userName')->nullable();
            $table->text('birthday')->nullable();
            $table->text('countryCode')->nullable();
            $table->text('bio')->nullable();
            $table->text('gender')->nullable();
            $table->text('age')->nullable();
            $table->text('fansCount')->nullable();
            $table->text('followCount')->nullable();
            $table->text('likeCount')->nullable();
            $table->text('videoNums')->nullable();
            $table->text('image')->nullable();

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
        Schema::dropIfExists('likees');
    }
}

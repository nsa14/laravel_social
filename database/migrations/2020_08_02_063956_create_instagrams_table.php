<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstagramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagrams', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->text('username');
            $table->text('follower_count');
            $table->text('edge_follow');
            $table->text('biography');
            $table->text('is_business_account');
            $table->text('business_category_name');
            $table->text('is_private');
            $table->text('is_verified');
            $table->text('profile_pic_url');
            $table->text('edge_owner_to_timeline_media');
            $table->text('description');
            $table->text('type');
            $table->text('status');

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
        Schema::dropIfExists('instagrams');
    }
}

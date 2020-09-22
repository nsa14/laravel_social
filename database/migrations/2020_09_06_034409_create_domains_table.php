<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();

            $table->text('url');
            $table->text('dot');
            $table->text('globalrank')->nullable();
            $table->text('localrank')->nullable();

            $table->text('title')->nullable();
            $table->text('howis')->nullable();
            $table->text('expertion_date')->nullable();
            $table->boolean('redirect')->nullable();
            $table->text('redirect_to')->nullable();
            $table->text('status_code')->nullable();
            $table->text('description')->nullable();

            $table->text('domainAuthority')->nullable();
            $table->text('externalEquityLinks')->nullable();
            $table->text('prettyExternalEquityLinks')->nullable();
            $table->text('pageAuthority')->nullable();

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
        Schema::dropIfExists('domains');
    }
}

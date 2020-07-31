<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createactiontables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //create galleryfile table
        Schema::create('actiontype', function(Blueprint $table){
            //$table->id();
            $table->increments('id');
            $table->string('name');
        });

        //create eventactionusers table
        Schema::create('eventactionuser', function(Blueprint $table){
            //$table->id();
            $table->unsignedInteger('eventid');
            $table->unsignedInteger('userid');
            $table->unsignedInteger('actiontypeid');

            $table->unique(['eventid', 'userid', 'actiontypeid']);
            $table->foreign('userid')->references('id')->on('users');
            $table->foreign('eventid')->references('id')->on('event');
            $table->foreign('actiontypeid')->references('id')->on('actiontype');
        });

        //create eventactionusers table
        Schema::create('organizationactionuser', function(Blueprint $table){
            //$table->id();
            $table->unsignedInteger('organizationid');
            $table->unsignedInteger('userid');
            $table->unsignedInteger('actiontypeid');

            $table->unique(['organizationid', 'userid', 'actiontypeid']);
            $table->foreign('userid')->references('id')->on('users');
            $table->foreign('organizationid')->references('id')->on('organization');
            $table->foreign('actiontypeid')->references('id')->on('actiontype');
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
        Schema::dropIfExists('actiontype');
        Schema::dropIfExists('organizationactionuser');
        Schema::dropIfExists('eventactionuser');
    }
}

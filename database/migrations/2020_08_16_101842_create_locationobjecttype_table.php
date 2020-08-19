<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationobjecttypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

//create locationobjecttype table; eg location events, each location donations
        Schema::create('locationobjecttype', function(Blueprint $table){
            $table->unsignedInteger('locationid');
            $table->unsignedInteger('objecttypeid');
            $table->unsignedInteger('objectid');

            $table->unique(['locationid','objecttypeid','objectid']);
            $table->foreign('locationid')->references('id')->on('location');
            $table->foreign('objecttypeid')->references('id')->on('objecttype');
        });


//create sublocationobjecttype table; eg sublocation events, each sublocation donations
        Schema::create('sublocationobjecttype', function(Blueprint $table){
            $table->unsignedInteger('sublocationid');
            $table->unsignedInteger('objecttypeid');
            $table->unsignedInteger('objectid');

            $table->unique(['sublocationid','objecttypeid','objectid']);
            $table->foreign('sublocationid')->references('id')->on('sublocation');
            $table->foreign('objecttypeid')->references('id')->on('objecttype');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locationobjecttype');
    }
}

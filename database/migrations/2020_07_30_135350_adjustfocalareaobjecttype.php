<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Adjustfocalareaobjecttype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('focalareaobjecttype',function(Blueprint $table){
            $table->unsignedInteger('focalareaid')->nullable()->change();
            $table->unsignedInteger('objecttypeid')->nullable()->change();


        });
        Schema::table('clusterobjecttype',function(Blueprint $table){
            $table->unsignedInteger('clusterid')->nullable()->change();
            $table->unsignedInteger('objecttypeid')->nullable()->change();
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

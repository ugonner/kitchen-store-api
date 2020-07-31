<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dropdeletecascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users',function(Blueprint $table){
            $table->unsignedInteger('roleid')->nullable()->change();
            $table->unsignedInteger('positionid')->nullable()->change();
            $table->unsignedInteger('locationid')->nullable()->change();
            $table->unsignedInteger('sublocationid')->nullable()->change();

        /*    $table->dropForeign('users_roleid_foreign');
            $table->dropForeign('users_locationid_foreign');
            $table->dropForeign('users_sublocationid_foreign');
            $table->dropForeign('users_positionid_foreign');

            $table->foreign('roleid')->references('id')->on('role');
            $table->foreign('locationid')->references('id')->on('location');
            $table->foreign('sublocationid')->references('id')->on('sublocation');
            $table->foreign('positionid')->references('id')->on('positionid');
        */
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

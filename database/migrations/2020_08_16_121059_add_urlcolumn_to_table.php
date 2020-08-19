<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlcolumnToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table', function (Blueprint $table) {
            //

            Schema::table('event',function(Blueprint $table){
                $table->string('contact')->nullable();
                $table->string('contacturl')->nullable();
            });
            Schema::table('facility',function(Blueprint $table){
                $table->string('contact')->nullable();
                $table->string('contacturl')->nullable();
            });
            Schema::table('organization',function(Blueprint $table){
                $table->string('contact')->nullable();
                $table->string('contacturl')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //

            Schema::table('event',function(Blueprint $table){
                $table->dropColumn('contact');
                $table->dropColumn('contacturl');
            });
            Schema::table('facility',function(Blueprint $table){
                $table->dropColumn('contact');
                $table->dropColumn('contacturl');
            });
            Schema::table('organization',function(Blueprint $table){
                $table->dropColumn('contact');
                $table->dropColumn('contacturl');
            });
        });
    }
}

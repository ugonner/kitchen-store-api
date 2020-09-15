<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoreColumnOnCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->string('orderamount');
            $table->string('orderdate');
            $table->string('ordertime');
            $table->string('ordernote');
            $table->string('orderref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('orderamount');
            $table->dropColumn('orderdate');
            $table->dropColumn('ordertime');
            $table->dropColumn('ordernote');
            $table->dropColumn('orderref');
        });
    }
}

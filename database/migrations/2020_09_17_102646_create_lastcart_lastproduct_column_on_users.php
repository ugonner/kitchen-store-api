<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLastcartLastproductColumnOnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('lastcartscount');
            $table->unsignedInteger('lastproductscount');
            $table->unsignedInteger('lastfacilityscount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lastcartscount');
            $table->dropColumn('lastproductscount');
            $table->dropColumn('lastfacilityscount');
        });
    }
}

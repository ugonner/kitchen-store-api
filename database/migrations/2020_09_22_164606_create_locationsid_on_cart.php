<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsidOnCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->unsignedInteger('orderlocationid');
            $table->unsignedInteger('ordersublocationid');
            //$table->timestamps();
        });

        Schema::table('cartitem', function (Blueprint $table) {
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            //$table->timestamps();
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
            $table->dropColumn('orderlocationid');
            $table->dropColumn('ordersublocationid');
            //$table->timestamps();
        });
        Schema::table('cartitem', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            //$table->timestamps();
        });
    }
}

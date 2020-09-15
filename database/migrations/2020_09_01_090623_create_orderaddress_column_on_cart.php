<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderaddressColumnOnCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carttype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
        });
        Schema::table('cart', function (Blueprint $table) {
            $table->unsignedInteger('carttypeid');
            $table->string('orderaddress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('carttypeid');
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('ordertype');
            $table->dropColumn('orderaddress');
        });
    }
}

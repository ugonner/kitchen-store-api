<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->timestamps();
            $table->string('title');
            $table->longText('detail');
            $table->string('imageurl');
            $table->string('discountrate');
            $table->string('dateofpublication')->default(NOW());
            $table->enum('published',["Y","N"])->default("N");


            $table->unsignedInteger('userid');
            $table->unsignedInteger('categoryid')->default(1);
            $table->unsignedInteger('productcategoryid')->default(1);
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price')->default(1);
            $table->increments('id');
            $table->unsignedInteger('totalsales')->default(0);


            $table->unsignedInteger('no_of_views')->default(0);
            $table->unsignedInteger('no_of_comments')->default(0);
            $table->unsignedInteger('no_of_follows')->default(0);

        });

        Schema::create('cart', function (Blueprint $table) {
            $table->timestamps();
            $table->string('timeoforder');
            $table->string('status');
            $table->enum('paid',["Y","N"])->default("N");
            $table->string('statusnote');


            $table->unsignedInteger('userid');
            $table->increments('id');

        });

        Schema::create('cartitem', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedInteger('cartid');
            $table->unsignedInteger('productid');
            $table->increments('id');

        });

        Schema::create('productcategory', function (Blueprint $table) {

            $table->string('name');
            $table->increments('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}

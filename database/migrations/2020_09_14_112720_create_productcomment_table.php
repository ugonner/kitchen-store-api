<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductcommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productcomment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('detail');
            $table->string('imageurl');
            $table->string('dateofpublication');
            $table->unsignedInteger('userid');
            $table->unsignedInteger('no_of_views');
            $table->unsignedInteger('no_of_follows');
            $table->unsignedInteger('no_of_comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productcomment');
    }
}

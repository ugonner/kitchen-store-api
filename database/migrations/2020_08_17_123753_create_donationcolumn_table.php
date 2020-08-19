<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationcolumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation', function (Blueprint $table) {
            $table->string('redeemed_date');
            $table->string('redeemed_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {

        Schema::table('donation', function (Blueprint $table) {
            $table->dropColumn('redeemed_date');
            $table->dropColumn('redeemed_amount');
        });
    }
}

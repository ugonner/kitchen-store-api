<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatetablesWithForeignkeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        //create LOCATION table
        Schema::create('location', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->increments('id');
        });


        //create objecttype table
        Schema::create('objecttype', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->text('description');
        });


        //create position table
        Schema::create('position', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });



        //create file table
        Schema::create('role', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create general category table
        Schema::create('category', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('parentcategoryid');
            $table->unsignedInteger('objecttypeid');

            $table->index('objecttypeid','category_objecttypeid');
            $table->increments('id');

        });


        //create cluster table
        Schema::create('cluster', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });


        //create clusterobjecttype table; eg cluster events, each cluster donations
        Schema::create('clusterobjecttype', function(Blueprint $table){
            $table->unsignedInteger('clusterid');
            $table->unsignedInteger('objecttypeid');
            $table->unsignedInteger('objectid');

            $table->unique(['clusterid','objecttypeid','objectid']);
        });


        //create user event category table; eg focalarea users, each cluster users
        Schema::create('eventcategory', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create user facility category table; eg focalarea users, each cluster users
        Schema::create('facilitycategory', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create user FACILITY certification table; eg focalarea users, each cluster users
        Schema::create('facilitycertificationlevel', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create user facility category table; eg focalarea users, each cluster users
        Schema::create('organizationcategory', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create user FACILITY certification table; eg focalarea users, each cluster users
        Schema::create('organizationcertificationlevel', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create advert table
        Schema::create('focalarea', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->increments('id');
        });

        //create sublocation table
        Schema::create('sublocation', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('locationid');

            $table->increments('id');
        });




        Schema::create('users', function (Blueprint $table) {

            //$table->id();
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();


            $table->string('mobile');
            $table->date('dateofregistration');
            $table->string('imageurl');
            $table->text('about');
            $table->text('rolenote');

            //ids
            $table->unsignedInteger('roleid');
            $table->unsignedInteger('positionid');
            $table->unsignedInteger('locationid');
            $table->unsignedInteger('sublocationid');

            //for notifications
            $table->unsignedInteger('lasteventcount');
            $table->unsignedInteger('lastorganizationcount');
            $table->unsignedInteger('lastarticlecount');
            $table->unsignedInteger('lastadvertcount');
            $table->unsignedInteger('lastdonationcount');

        });



        //create grouptype users eg cluster users table; eg focalarea users, each cluster users
        Schema::create('grouptypeuser', function(Blueprint $table){
            $table->unsignedInteger('userid');
            $table->unsignedInteger('grouptypeid');
            $table->unique(['userid','grouptypeid']);

        });


        //create advert table
        Schema::create('advert', function(Blueprint $table){
            //$table->id();
            $table->string('title');
            $table->text('adverturl');
            $table->longText('detail');
            $table->string('imageurl');
            $table->string('dateofpublication')->default(NOW());
            $table->enum('published',["Y","N"])->default("N");
            $table->enum('paid',["Y","N"])->default("N");


            $table->unsignedInteger('userid');
            $table->unsignedInteger('categoryid')->default(1);
            $table->unsignedInteger('placementid');
            $table->unsignedInteger('focalareaid');
            $table->unsignedInteger('clusterid');


            $table->unsignedInteger('no_of_views')->default(0);
            $table->increments('id');

        });


        //create article
        Schema::create('article', function(Blueprint $table){
            //$table->id();
            $table->string('title');
            $table->longText('detail');
            $table->string('imageurl');
            $table->string('dateofpublication')->default(NOW());
            $table->enum('published',["Y","N"])->default("N");


            $table->unsignedInteger('userid');
            $table->unsignedInteger('categoryid')->default(1);
            $table->increments('id');


            $table->unsignedInteger('no_of_views')->default(0);
            $table->unsignedInteger('no_of_comments')->default(0);
            $table->unsignedInteger('no_of_follows')->default(0);
        });


        Schema::create('event', function(Blueprint $table){
            //$table->id();
            $table->string('title');
            $table->longText('detail');
            $table->string('imageurl');
            $table->string('venue');
            $table->string('dateofevent');
            $table->string('timeofevent');
            $table->string('organizer');
            $table->string('fee');
            $table->string('frequency');
            $table->enum('published',["Y","N"])->default("N");


            //foreign keys ; location and sublocations are in many-to-many reference table
            $table->unsignedInteger('userid');
            $table->unsignedInteger('categoryid');
            $table->unsignedInteger('eventcategoryid');


            $table->unsignedInteger('no_of_views')->default(0);
            $table->unsignedInteger('no_of_comments')->default(0);
            $table->unsignedInteger('no_of_follows')->default(0);
            $table->increments('id');

        });

        //create objecttype (event) users ie attendees table; eg user cluster, each user focalarea
        Schema::create('eventuser', function(Blueprint $table){
            $table->unsignedInteger('userid');
            $table->unsignedInteger('eventid');

            $table->unique(['userid','eventid']);
        });

        //create facilities table
        Schema::create('facility', function(Blueprint $table){
            //$table->id();
            $table->string('title');
            $table->text('address');
            $table->longText('detail');
            $table->string('imageurl');
            $table->string('dateofpublication')->default(NOW());
            $table->enum('published',["Y","N"])->default("N");


            $table->unsignedInteger('userid');
            $table->unsignedInteger('categoryid')->default(1);
            $table->unsignedInteger('facilitycategoryid')->default(1);
            $table->unsignedInteger('facilitycertificationlevelid')->default(1);
            $table->unsignedInteger('locationid');
            $table->unsignedInteger('sublocationid');


            $table->unsignedInteger('no_of_views')->default(0);
            $table->unsignedInteger('no_of_comments')->default(0);
            $table->unsignedInteger('no_of_follows')->default(0);
            $table->increments('id');

        });


        //create object's comments eg event comments or article's comments
        Schema::create('comment', function(Blueprint $table){
            //$table->id();
            $table->longtext('detail');
            $table->string('imageurl');
            $table->string('dateofpublication')->default(NOW());


            $table->unsignedInteger('userid');
            $table->unsignedInteger('objecttypeid');
            $table->unsignedInteger('objectid');


            $table->unsignedInteger('no_of_views')->default(0);
            $table->unsignedInteger('no_of_follows')->default(0);
            $table->increments('id');
        });


        //create donation table
        Schema::create('donation', function(Blueprint $table){
            //$table->id();
            $table->string('amount');
            $table->text('description');
            $table->string('dateofdonation');
            $table->enum('redeemed',["Y","N"])->default("N");
            $table->unsignedInteger('focalareaid');
            $table->unsignedInteger('userid');
            $table->increments('id');


        });


        //create event location ie areas of coverage
        Schema::create('eventlocation', function(Blueprint $table){
            $table->unsignedInteger('eventid');
            $table->unsignedInteger('locationid');

            $table->unique(['eventid','locationid']);
        });

        //create event sublocation ie areas of coverage
        Schema::create('eventsublocation', function(Blueprint $table){
            $table->unsignedInteger('eventid');
            $table->unsignedInteger('sublocationid');

            $table->unique(['eventid','sublocationid']);
        });


        //create file table
        Schema::create('file', function(Blueprint $table){
            //$table->id();
            $table->string('fileurl');
            $table->text('description');
            $table->string('filetype');
            $table->unsignedInteger('objecttypeid');
            $table->unsignedInteger('objectid');

            $table->increments('id');
        });

        //create focalareaobjecttype table; eg focalarea events, each focalarea donations
        Schema::create('focalareaobjecttype', function(Blueprint $table){
            $table->unsignedInteger('focalareaid');
            $table->unsignedInteger('objecttypeid');
            $table->unsignedInteger('objectid');

            $table->unique(['focalareaid','objecttypeid','objectid']);
        });

        //create galleryfile table
        Schema::create('galleryfile', function(Blueprint $table){
            //$table->id();
            $table->string('fileurl');
            $table->string('description');
            $table->unsignedInteger('userid');
            $table->enum('published',['Y','N'])->default("Y");
            $table->date('dateofpublication');

            $table->increments('id');
        });


        //create facilities table
        Schema::create('organization', function(Blueprint $table){
            //$table->id();
            $table->string('name');
            $table->string('founder');
            $table->text('address');
            $table->longText('detail');
            $table->string('imageurl');
            $table->string('dateofformation');
            $table->string('dateofpublication')->default(NOW());
            $table->enum('published',["Y","N"])->default("N");


            $table->unsignedInteger('userid');
            $table->unsignedInteger('categoryid')->default(1);
            $table->unsignedInteger('organizationcategoryid')->default(1);
            $table->unsignedInteger('organizationcertificationlevelid')->default(1);
            $table->unsignedInteger('focalareaid');


            $table->unsignedInteger('no_of_views')->default(0);
            $table->unsignedInteger('no_of_comments')->default(0);
            $table->unsignedInteger('no_of_follows')->default(0);
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
        Schema::dropIfExists('advert');
        Schema::dropIfExists('article');
        Schema::dropIfExists('category');
        Schema::dropIfExists('cluster');
        Schema::dropIfExists('clusterobjecttype');
        Schema::dropIfExists('donation');
        Schema::dropIfExists('event');
        Schema::dropIfExists('eventcategory');
        Schema::dropIfExists('eventlocation');
        Schema::dropIfExists('eventsublocation');
        Schema::dropIfExists('eventuser');
        Schema::dropIfExists('facility');
        Schema::dropIfExists('facilitycategory');
        Schema::dropIfExists('facilitycertificationlevel');
        Schema::dropIfExists('file');
        Schema::dropIfExists('focalarea');
        Schema::dropIfExists('focalareaobjecttype');
        Schema::dropIfExists('galleryfile');
        Schema::dropIfExists('location');
        Schema::dropIfExists('objecttype');
        Schema::dropIfExists('organization');
        Schema::dropIfExists('organizationcertificationlevel');
        Schema::dropIfExists('organizationcategory');
        Schema::dropIfExists('position');
        Schema::dropIfExists('role');
        Schema::dropIfExists('sublocation');
        Schema::dropIfExists('grouptypeuser');
        Schema::dropIfExists('users');
    }
}

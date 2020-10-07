<?php

namespace App\Http\Controllers\seeders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeederController extends Controller
{
    //
    protected function seedTables(){


        //add cartcategory
        DB::table('carttype')->insert([
            ['name'=> 'Instant Pay'],
            ['name'=> 'Pay On  Delivery']
        ]);

        //add cartcategory
        DB::table('cartcategory')->insert([
            ['name'=> 'Door Delivery'],
            ['name'=> 'Sit-In'],
            ['name'=> 'OutDoor Service'],
        ]);



        //sublocation
        DB::table('productcategory')->insert([
            ['name'=> 'Bar'],
            ['name'=> 'Pastries and Ice-Cream'],
            ['name'=> 'Pasta'],
            ['name'=> 'cereal'],
            ['name'=> 'Liqour and Drinks'],
            ['name'=> 'Barbeque'],
            ['name'=> 'Beverages'],
            ['name'=> 'Local Dishes'],
            ['name'=> 'Continental Dishes']
        ]);

        //sublocation
        DB::table('category')->insert([
            ['name'=> 'Appetizer', 'objecttypeid'=>8, 'parentcategoryid'=>0],
            ['name'=> 'Main Course', 'objecttypeid'=>2, 'parentcategoryid'=>0],
            ['name'=> 'Desert', 'objecttypeid'=>1, 'parentcategoryid'=>0]
        ]);

        DB::table('objecttype')->insert([
            ['name'=> 'Advert'],
            ['name'=> 'Article'],
            ['name'=> 'Donation'],
            ['name'=> 'Event'],
            ['name'=> 'Facility'],
            ['name'=> 'Organization'],
            ['name'=> 'Product'],
            ['name'=> 'Users']
        ]);

        //sublocation
        DB::table('placement')->insert([
            ['name'=> 'Front Page Top'],
            ['name'=> 'Front Page Side'],
            ['name'=> 'Pages Top'],
            ['name'=> 'Pages Side']
        ]);



        DB::table('cluster')->insert([
            ['name'=> 'Passers By'],
            ['name'=> 'Enthusiasts'],
            ['name'=> 'Professionals']
        ]);

        DB::table('eventcategory')->insert([
            ['name'=> 'Religious'],
            ['name'=> 'Entertainment'],
            ['name'=> 'Traditional'],
            ['name'=> 'Exhibition'],
        ]);

        DB::table('facilitycategory')->insert([
            ['name'=> 'Food'],
            ['name'=> 'Tourism'],
            ['name'=> 'Recreation']
        ]);

        DB::table('focalarea')->insert([
            ['name'=> 'Diaspora'],
            ['name'=> 'Culture'],
            ['name'=> 'Tourism']
        ]);

        DB::table('facilitycertificationlevel')->insert([
            ['name'=> 'None'],
            ['name'=> 'Banned'],
            ['name'=> 'Bronze'],
            ['name'=> 'Bronze'],
            ['name'=> 'Silver'],
            ['name'=> 'Gold'],
        ]);

        DB::table('organizationcategory')->insert([
            ['name'=> 'hospitality'],
            ['name'=> 'Education'],
            ['name'=> 'Health']
        ]);

        DB::table('Organizationcertificationlevel')->insert([
            ['name'=> 'None'],
            ['name'=> 'Banned'],
            ['name'=> 'bronze'],
            ['name'=> 'Silver'],
            ['name'=> 'Gold'],
        ]);

        DB::table('position')->insert([
            ['name'=> 'Admin'],
            ['name'=> 'Editor'],
            ['name'=> 'Technical Personnel']
        ]);

        DB::table('role')->insert([
            ['name'=> 'Members'],
            ['name'=> 'Staff'],
            ['name'=> 'Volunteers'],
            ['name'=> 'Appointee']
        ]);

/*
        DB::table('location')->insert([
            ['name'=> 'Abia'],
            ['name'=> 'Adamawa'],
            ['name'=> 'Akwa-ibom'],
            ['name'=> 'Anambra'],
            ['name'=> 'Bauchi'],
        ]);

        DB::table('sublocation')->insert([
            ['name'=> 'Aguata','locationid'=>4],
            ['name'=> 'Awka-north','locationid'=>4],
            ['name'=> 'Awka-south','locationid'=>4],
            ['name'=> 'Ayamelum','locationid'=>4]
        ]);*/

    }

}

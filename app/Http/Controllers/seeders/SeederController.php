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
            ['name'=> 'Pay On  Delivery'],
            ['name'=> 'Subscription'],
        ]);

        //add cartcategory
        DB::table('cartcategory')->insert([
            ['name'=> 'Door Delivery'],
            ['name'=> 'Sit-In'],
            ['name'=> 'OutDoor Service'],
        ]);

        //sublocation
        DB::table('productcategory')->insert([
            ['name'=> 'Disries'],
            ['name'=> 'Milk shakes'],
            ['name'=> 'Coffee'],
        ]);


        //sublocation
        DB::table('category')->insert([
            ['name'=> 'Appetizer', 'objecttypeid'=>8, 'parentcategoryid'=>0],
            ['name'=> 'Main Course', 'objecttypeid'=>2, 'parentcategoryid'=>0],
            ['name'=> 'Desert', 'objecttypeid'=>1, 'parentcategoryid'=>0],
            ['name'=> 'Bar', 'objecttypeid'=>1, 'parentcategoryid'=>0],
            ['name'=> 'Pastries and Ice-Cream', 'objecttypeid'=>8, 'parentcategoryid'=>1],
            ['name'=> 'Pasta', 'objecttypeid'=>8, 'parentcategoryid'=>2],
            ['name'=> 'cereal', 'objecttypeid'=>8, 'parentcategoryid'=>2],
            ['name'=> 'Liqour and Drinks', 'objecttypeid'=>8, 'parentcategoryid'=>4],
            ['name'=> 'Barbeque', 'objecttypeid'=>8, 'parentcategoryid'=>0],
            ['name'=> 'Beverages', 'objecttypeid'=>8, 'parentcategoryid'=>3],
            ['name'=> 'Local Dishes', 'objecttypeid'=>8, 'parentcategoryid'=>2],
            ['name'=> 'Continental Dishes', 'objecttypeid'=>8, 'parentcategoryid'=>2]
        ]);
        exit;
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
            ['name'=> 'Ndi ara'],
            ['name'=> 'Ogaranya'],
            ['name'=> 'Men']
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

        DB::table('location')->insert([
            ['name'=> 'Abia'],
            ['name'=> 'Adamawa'],
            ['name'=> 'Akwa-ibom'],
            ['name'=> 'Anambra'],
            ['name'=> 'Bauchi'],
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
            ['name'=> 'Director'],
            ['name'=> 'Perm. Sec'],
            ['name'=> 'Enginneers']
        ]);

        DB::table('role')->insert([
            ['name'=> 'Members'],
            ['name'=> 'Staff'],
            ['name'=> 'Volunteers'],
            ['name'=> 'Appointee']
        ]);

        DB::table('sublocation')->insert([
            ['name'=> 'Aguata','locationid'=>4],
            ['name'=> 'Awka-north','locationid'=>4],
            ['name'=> 'Awka-south','locationid'=>4],
            ['name'=> 'Ayamelum','locationid'=>4]
        ]);

    }

}

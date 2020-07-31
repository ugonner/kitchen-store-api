<?php

namespace App\Http\Controllers\seeders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeederController extends Controller
{
    //
    protected function seedTables(){
        /*DB::table('objecttype')->insert([
            ['name'=> 'Advert'],
            ['name'=> 'Article'],
            ['name'=> 'Donation'],
            ['name'=> 'Event'],
            ['name'=> 'Facility'],
            ['name'=> 'Organization'],
            ['name'=> 'Users']
        ]);*/

        //sublocation
        DB::table('category')->insert([
            ['name'=> 'politics', 'objecttypeid'=>1, 'parentcategoryid'=>0],
            ['name'=> 'food', 'objecttypeid'=>2, 'parentcategoryid'=>0],
            ['name'=> 'Election', 'objecttypeid'=>1, 'parentcategoryid'=>1],
            ['name'=> 'cereal', 'objecttypeid'=>2, 'parentcategoryid'=>2],
            ['name'=> 'Hotels', 'objecttypeid'=>3, 'parentcategoryid'=>0]
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

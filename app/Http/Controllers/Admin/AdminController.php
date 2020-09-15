<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use \Illuminate\Database\Query;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //

//protected $users;
    protected $categories;
    protected $main_categories;
    protected $clusters;
    protected $focalareas;
    protected $locations;
    protected $positions;
    protected $roles;
    protected $sublocations;
    protected $eventcategories;
    protected $organizationcategories;
    protected $facilitycategories;
    protected $productcategories;
    protected $cartcategories;
    protected $carttypes;

    public function getMenuItems(Request $request){
        $this->main_categories = DB::table('category')->where('parentcategoryid','=',0)->get();
        $this->eventcategories = DB::table('eventcategory')->get();
        $this->organizationcategories = DB::table('organizationcategory')->get();
        $this->facilitycategories = DB::table('facilitycategory')->get();
        $this->productcategories = DB::table('productcategory')->get();
        $this->cartcategories = DB::table('cartcategory')->get();
        $this->carttypes = DB::table('carttype')->get();
        $this->clusters = DB::table('cluster')->get();
        $this->focalareas = DB::table('focalarea')->get();
        $this->locations = DB::table('location')->get();
        $this->roles = DB::table('role')->get();
        $this->positions = DB::table('position')->get();
        $this->sublocations = DB::table('sublocation')->get();

        if($allcategories = DB::table('category')->get()){
            $this->categories = $allcategories->map(function($category) {
                $categories = DB::table('category')->get();
                //$category->parentcategory = [["name"=>'No Parent',"description"=>'This is a parent category']];
                $category->parentcategory = $category;
                for($cc=0; $cc<count($categories); $cc++){
                    if($category->parentcategoryid == $categories[$cc]->id){
                        //echo('met at '.$categories[$cc]->name );
                        $category->parentcategory = $categories[$cc];

                        //echo $category->parentcategory->name;
                    }

                }
                return $category;
            });
        }

        $menuitemsArray = [
            //'categories'=> $this->categories,
            'main_categories'=> $this->main_categories,
            'clusters'=> $this->clusters,
            'focalareas'=> $this->focalareas,
            'locations'=> $this->locations,
            'positions'=> $this->positions,
            'roles'=> $this->roles,
            'sublocations'=> $this->sublocations,
            "eventcategories"=>$this->eventcategories,
            "organizationcategories"=>$this->organizationcategories,
            "facilitycategories"=>$this->facilitycategories,
            "productcategories"=>$this->productcategories,
            "cartcategories"=>$this->cartcategories,
            "carttypes"=>$this->carttypes

        ];

        if($request->wantsJson()){

            return response()->json([
                   "success"=>true,
                    "menuitems"=>$menuitemsArray,
                    "message"=>"menu items fetched successfully"
                ]);
        }
        return redirect()->route('adminpanel')->with('output','please use view composer variables');
    }

}

<?php

namespace App\Http\ViewComposers;

use App\models\location;
use App\User;
use App\models\cluster;
use App\models\focalarea;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ViewComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
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

    public function getAllObjects(){
        $this->main_categories = DB::table('category')->where('parentcategoryid','=',0)->get();
        $this->eventcategories = DB::table('eventcategory')->get();
        $this->organizationcategories = DB::table('organizationcategory')->get();
        $this->facilitycategories = DB::table('facilitycategory')->get();
        $this->clusters = cluster::all();
        $this->focalareas = focalarea::all();
        $this->locations = location::all();
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
    }

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        //$this->users = $users;
        $this->getAllObjects();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(array(
            'categories'=> $this->categories,
            'main_categories'=> $this->main_categories,
            'clusters'=> $this->clusters,
            'focalareas'=> $this->focalareas,
            'locations'=> $this->locations,
            'positions'=> $this->positions,
            'roles'=> $this->roles,
            'sublocations'=> $this->sublocations,
            "eventcategories"=>$this->eventcategories,
            "organizationcategories"=>$this->organizationcategories,
            "facilitycategories"=>$this->facilitycategories
        ));
        //$view->with('categories', $this->users->count());
    }
}
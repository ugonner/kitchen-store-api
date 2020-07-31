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
    protected $clusters;
    protected $focalareas;
    protected $locations;
    protected $positions;
    protected $roles;
    protected $sublocations;

    public function getAllObjects(){
        $this->categories = DB::table('category')->where('parentcategoryid','%ne',0)->get();
        $this->clusters = cluster::all();
        $this->focalareas = focalarea::all();
        $this->locations = location::all();
        $this->roles = DB::table('role')->get();
        $this->positions = DB::table('position')->get();
        $this->sublocations = DB::table('sublocation')->get();

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
            'clusters'=> $this->clusters,
            'focalareas'=> $this->focalareas,
            'locations'=> $this->locations,
            'positions'=> $this->positions,
            'roles'=> $this->roles,
            'sublocations'=> $this->sublocations
        ));
        //$view->with('categories', $this->users->count());
    }
}
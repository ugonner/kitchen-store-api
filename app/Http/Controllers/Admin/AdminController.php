<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use \Illuminate\Database\Query;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //



    protected function getIndexProducts(Request $request){

        $limit = 4;

        //get top four product categories and most viewed products
        $productcategory_1 = $this->getProductsByProperty('product.productcategoryid','=',1,$limit,'product.id','DESC');
        $productcategory_2 = $this->getProductsByProperty('product.productcategoryid','=',2,$limit,'product.id','DESC');
        $productcategory_3 = $this->getProductsByProperty('product.productcategoryid','=',3,$limit,'product.id','DESC');
        $productcategory_4 = $this->getProductsByProperty('product.productcategoryid','=',4,$limit,'product.id','DESC');


        //get four main categories
        $category_1 = $this->getProductsByProperty('product.categoryid','=',1,$limit,'product.id','DESC');
        $category_2 = $this->getProductsByProperty('product.categoryid','=',2,$limit,'product.id','DESC');
        $category_3 = $this->getProductsByProperty('product.categoryid','=',3,$limit,'product.id','DESC');
        $category_4 = $this->getProductsByProperty('product.categoryid','=',4,$limit,'product.id','DESC');

        //get most viewed
        $most_viewed = $this->getProductsByProperty('product.id','>',0,$limit,'product.no_of_views','DESC');

        if($request->wantsJson()){
            return response()->json([
                "productcategory_1"=>$productcategory_1,
                "productcategory_2"=>$productcategory_2,
                "productcategory_3"=>$productcategory_3,
                "productcategory_4" => $productcategory_4,
                "category_1" => $category_1,
                "category_2" => $category_2,
                "category_3" => $category_3,
                "category_4" => $category_4,
                "most_viewed" => $most_viewed,
                "success" => true,
                "message" => 'index products fetched'
            ]);
        }
        return true;
    }


    protected function getIndexProductsByUnion(Request $request,$limit = 4){

        $productfieldarray = [
            'product.id as id', 'product.title','product.imageurl as imageurl',
            'product.updated_at as dateofpublication','product.discountrate','quantity','price','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'productcategory.id as productcategoryid','productcategory.name as productcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];


        $productsQuery = DB::table('product')
            ->join('productcategory','product.productcategoryid','=','productcategory.id')
            ->join('category','product.categoryid','=','category.id')
            ->join('users','product.userid','=','users.id');

        $productcategory_1query = $productsQuery->where('product.productcategoryid','=',1)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);
        
        $productcategory_2query = $productsQuery->where('product.productcategoryid','=',2)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);
        
        $productcategory_3query = $productsQuery->where('product.productcategoryid','=',3)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);
        
        $productcategory_4query = $productsQuery->where('product.productcategoryid','=',4)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);


        $category_1query = $productsQuery->where('product.categoryid','=',1)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);

        $category_2query = $productsQuery->where('product.categoryid','=',2)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);

        $category_3query = $productsQuery->where('product.categoryid','=',3)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);

        $category_4query = $productsQuery->where('product.categoryid','=',4)
            ->select($productfieldarray)->orderBy('product.id','DESC')->linit($limit);


        //return $products;

    }


    protected function getProductsByProperty($pty,$operator, $val, $limit, $orderBy,$orderFlow){


        $productfieldarray = [
            'product.id as id', 'product.title','product.imageurl as imageurl',
            'product.updated_at as dateofpublication','product.discountrate','quantity','price','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'productcategory.id as productcategoryid','productcategory.name as productcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];


        $products = DB::table('product')
            ->join('productcategory','product.productcategoryid','=','productcategory.id')
            ->join('category','product.categoryid','=','category.id')
            ->join('users','product.userid','=','users.id')
            ->where($pty,$operator,$val)
            ->select($productfieldarray)->orderBy($orderBy,$orderFlow)->limit($limit)->get();

        return $products;

    }

//protected $users;
    protected $categories;
    protected $main_categories;
    protected $allcategories;
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

    public function getObjectDifferenceCounts(){
        $userid = Auth::id();

        //get total number of objects
        $cartscount = DB::table('cart')->count();

        //get last admin's object count
        $lastcartscount = DB::table('users')->where(["id"=>$userid])->select('lastcartscount')->get()[0];

        //get the diff ie new object inserted;
        $orderdiff = $cartscount - $lastcartscount->lastcartscount;


        //for product
        $productscount = DB::table('product')->count();
        $lastproductscount = DB::table('users')->where(["id"=>$userid])->select('lastproductscount')->get()[0];
        $productdiff = $productscount - $lastproductscount->lastproductscount;


        //for user
        $userscount = DB::table('user')->count();
        $lastuserscount = DB::table('users')->where(["id"=>$userid])->select('lastuserscount')->get()[0];
        $userdiff = $userscount - $lastuserscount->lastuserscount;
        //echo($orderdiff);

    }

    public function getMenuItems(Request $request){
        $this->allcategories = DB::table('category')->get();
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
            'maincategories'=> $this->main_categories,
            'allcategories'=> $this->allcategories,
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

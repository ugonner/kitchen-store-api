<?php

namespace App\Http\Controllers;

use \Illuminate\Database\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

use App\Cart;
use App\Traits\ManyToManyHandler;
class CartController extends Controller
{
    //Use the trait for inserting cart into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Carts, articles , events, facilities etc
    use ManyToManyHandler;
    //


    protected $ObjectTypeId = 7;
    protected  $ObjectId = 7;
    public function __construct(){

    }


    protected function getAdminCarts(){
        $cartfieldsarray = array('cart.id','cart.name','cart.imageurl', 'rolenote', 'role.name as rolename','position.name as positionname',
            'location.name as locationname','sublocation.name as sublocationname');

        $cart = DB::table('cart')
            ->join('role', 'cart.roleid', '=', 'role.id')
            ->join('position', 'cart.positionid', '=', 'position.id')
            ->join('location', 'cart.locationid', '=', 'location.id')
            ->join('sublocation', 'cart.sublocationid', '=', 'sublocation.id')
            ->select($cartfieldsarray)->orderBy('cart.id','DESC')->distinct()->paginate(10,$cartfieldsarray);


        /*echo(count($cart));
       return true;*/
        return view('admin.cart.cartpanel',array("AdminCarts"=>$cart));
    }

    protected function getCart(Request $request){
        $cartid = $request->input('cartid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');

        $cart = Cart::where('id','=',$cartid)->select('*')->get();
        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$cartid, 'clusterobjecttype.objecttypeid'=> $this->ObjectId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $cartid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectId])
            ->select($focalareafieldsarray)->get();

        return view('admin.cart.updatecart',["cart"=>$cart, "cart_clusters"=>$clusters, "cart_focalareas"=>$focalareas]);
    }




    public  function registerCart(Request $request){
        $requestArray = $request->only([
            'ordertime',
            'orderdate',
            'ordernote',
            'orderamount',
            'orderaddress'

        ]);
        $validationArray = array(
            'ordertime'=> 'string',
            "orderdate"=>"string",
            "ordernote"=> "string",
            "orderamount"=> 'string',
            "orderaddress"=> 'string'
        );


        $validator = Validator::make($requestArray,$validationArray);

        if ($validator->fails()) {

            if($request->wantsJson())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }else{
                return back()->withErrors($validator);
            }

        }

        $authenticatedUserId = 1;
        if($request->has('password')){
            if(Auth::attempt($request->only(array('email','password')))){
                $authenticatedUserId = Auth::id();
            }else{
                return response()->json(['results'=>false, 'message'=>'not logged in member']);
            }
        }

        //$imagepath = $request->file('imageurl')->store('/api/public/images/cart/');

        $insertionArray_cart = array(
            "ordertime"=>$request->input('ordertime',date("Y-M-D")),
            "orderdate"=>$request->input('orderdate',date("H:i:s")),
            "orderamount"=>$request->input('orderamount',''),
            "ordernote"=>$request->input('ordernote','N/A'),
            "orderaddress"=>$request->input('orderaddress','N/A'),
            "orderref"=>$request->input('orderref','N/A'),
            "userid"=>$request->input('userid',$authenticatedUserId),
            "paid"=>$request->input('paid','N'),
            "statusnote"=>$request->input('statusnote','not supplied yet')
        );

        $cartid = DB::table('cart')->insertGetId($insertionArray_cart);

        $message = "Cart added successfully";
        if($request->wantsJson()){
            return response()->json(["results"=>true,"cartid"=>$cartid,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
    }


    protected function editCart(Request $request){
        $requestArray = $request->only([
            'ordertime',
            'orderdate',
            'ordernote',
            'orderamount',
            'orderaddress'

        ]);
        $validationArray = array(
            'ordertime'=> 'string',
            "orderdate"=>"string",
            "ordernote"=> "string",
            "orderamount"=> 'string',
            "orderaddress"=> 'string'
        );


        $validator = Validator::make($requestArray,$validationArray);

        if ($validator->fails()) {

            if($request->wantsJson())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }else{
                return back()->withErrors($validator);
            }

        }

        $authenticatedUserId = 1;
        if($request->has('password')){
            if(Auth::attempt($request->only(array('email','password')))){
                $authenticatedUserId = Auth::id();
            }else{
                return response()->json(['results'=>false, 'message'=>'not logged in member']);
            }
        }

        //$imagepath = $request->file('imageurl')->store('/api/public/images/cart/');

        $insertionArray_cart = array(
            "ordertime"=>$request->input('ordertime',date("Y-M-D")),
            "orderdate"=>$request->input('orderdate',date("H:i:s")),
            "orderamount"=>$request->input('orderamount',''),
            "ordernote"=>$request->input('ordernote','N/A'),
            "orderaddress"=>$request->input('orderaddress','N/A'),
            "orderref"=>$request->input('orderref','N/A'),
            "userid"=>$request->input('userid',$authenticatedUserId),
            "paid"=>$request->input('paid','N'),
            "statusnote"=>$request->input('statusnote','not supplied yet')
        );

        $cartid = DB::table('cart')->where('id','=',$request->input('cartid'))->update($insertionArray_cart);
        $message = "Profile Edited";
        if($request->wantsJson()){
            return response()->json(["results"=>true,"cartid"=>$cartid,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
        //return back()->with("output",$message);
    }


    protected function removeCartFromGroups(Request $request){
        $cartid = $request->input('cartid');

        //removing cart to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            if($this->removeIdFromMultipleGroups($request,$cartid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$cartid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        $message = 'No Change Effected';
        return back()->with("output",$message);
    }


    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function createRoleOrPosition(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //create role;
        $name = $request->input('name');
        $description = $request->input('description');

        //form field 'r' or 'p' helps to determine table name for roles and position
        //else specify a tablename form field
        $form_tablename = ($request->has('tablename')?$request->input('tablename'): '');
        $tablename = (($request->has('p'))? 'position': ($request->has('r')? 'role': $form_tablename));

        if(DB::table($tablename)->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //editing new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function editRoleOrPosition(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //;
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');

        //form field 'r' or 'p' helps to determine table name for roles and position
        //else specify a tablename form field
        $form_tablename = ($request->has('tablename')?$request->input('tablename'): '');
        $tablename = (($request->has('p'))? 'position': ($request->has('r')? 'role': $form_tablename));

        if($update = DB::table($tablename)->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

    //deleting new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function deleteRolesOrPosition(Request $request){

        //;
        $id = $request->input('id');

        //form field 'r' or 'p' helps to determine table name for roles and position
        //else specify a tablename form field
        $form_tablename = ($request->has('tablename')?$request->input('tablename'): '');
        $tablename = (($request->has('p'))? 'position': ($request->has('r')? 'role': $form_tablename));

        if(DB::table($tablename)->where(["id"=>$id])->delete()){
            $message = $tablename. ' successfully deleted';
        }else{
            $message = $tablename.' not deleted';
        }
        return back()->with("output",$message);
    }





}

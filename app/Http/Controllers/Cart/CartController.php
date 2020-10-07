<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Database\Query;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Hash;
use App\Traits\UserTrait;
//use App\Cart;
use App\Traits\ManyToManyHandler;
//use PHPUnit\Framework\Exception;

class CartController extends Controller
{

    use UserTrait;




    protected function getCarts(Request $request){

        $cartfieldarray = [
            'cart.id as id', 'cart.orderdate','cart.ordertime', 'cart.paid','ordernote','statusnote','status',
            'cart.updated_at as dateofpublication','orderamount','orderaddress', 'orderlocationid','ordersublocationid','carttypeid','carttype.name as carttypename',
            'cartcategory.id as cartcategoryid','cartcategory.name as cartcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $carts = DB::table('cart')
            ->join('cartcategory','cart.cartcategoryid','=','cartcategory.id')
            ->join('carttype','cart.carttypeid','=','carttype.id')
            ->join('users','cart.userid','=','users.id')
            ->select($cartfieldarray)->orderBy('cart.id','DESC')->distinct()->paginate(10,$cartfieldarray);

        $carttypes = DB::table('carttype')->get();

        //update admin order count;
        if(Auth::check()){

            $userid = Auth::id();

            $ordersCount = DB::table('cart')->count();
            $updateArray = ["lastcartscount"=>$ordersCount];

            DB::table('users')->where(["users.id"=>$userid])->update($updateArray);

        }


        //echo(json_encode($carts)); exit;
        if($request->wantsJson()){

            return response()->json(["carts"=>$carts,"success"=>true,"message"=>'orders fetched']);

        }

        return view('admin.cart.cartpanel',['carts'=>$carts,"carttypes"=>$carttypes]);

    }




    //get carts by where clauses
    protected function getCartsByProperty(Request $request){

        $pty = ((($request->route('pty')))? $request->route('pty'): $request->input('pty'));
        $val = ((($request->route('val')))? $request->route('val'): $request->input('val'));
        //$val = $request->input('val');

        $ptyArray = [
            "ci"=>'cart.id',"codate"=>'cart.orderdate',"cotime"=>'cart.ordertime',"cp"=>'cart.paid',
            "coref"=>'cart.orderref',"cctype"=>'cart.carttypeid',"cc"=>'cart.cartcategoryid',"ui"=>'users.id'
        ];
        if(!empty($ptyArray[$pty])){

            $ptyString = $ptyArray[$pty];

        }else{
            if($request->wantsJson()){

                return response()->json(["success"=>false, "message"=>'Invalid request, Please be warned']);
            }
            return back()->with('output','Malicious request');
        }
        $cartfieldarray = [
            'cart.id as id', 'cart.orderdate','cart.ordertime', 'cart.paid','ordernote','statusnote','status',
            'cart.updated_at as dateofpublication','orderamount','carttypeid','carttype.name as carttypename',
            'cartcategory.id as cartcategoryid','cartcategory.name as cartcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];

        $carts = DB::table('cart')
            ->join('cartcategory','cart.cartcategoryid','=','cartcategory.id')
            ->join('carttype','cart.carttypeid','=','carttype.id')
            ->join('users','cart.userid','=','users.id')
            ->where($ptyString,'=',$val)
            ->select($cartfieldarray)->orderBy('cart.id','DESC')->distinct()->paginate(10,$cartfieldarray);

        //get cartpanels
        $carttypes = DB::table('carttype')->get();
        //echo(json_encode($carts)); exit;
        if($request->wantsJson()){
            return response()->json(["carts"=>$carts,"success"=>true,"message"=>'orders fetched']);
        }
        return view('admin.cart.cartpanel',['carts'=>$carts,"carttypes"=>$carttypes]);
    }

    protected function getCartForEdit(Request $request){
        //$cartid = $request->input('cartid');

        $cartid = (($request->route('cartid'))?$request->route('cartid'): $request->input('cartid'));
        //echo($cartid); exit;
        //$cartstr = ['cart.id as id', 'cart.orderdate','cart.ordertime','cart.paid','cart.statusnote',
        //    'cart.updated_at as dateofpublication','orderamount','carttypeid'];
        $cartfieldarray = [
            'cart.id as id', 'cart.orderdate','cart.ordertime', 'cart.paid','orderaddress','ordernote','statusnote',
            'cart.status as status',
            'cart.updated_at as dateofpublication','orderamount','carttypeid','carttype.name as carttypename',
            'cartcategory.id as cartcategoryid','cartcategory.name as cartcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];

        /*$cartfieldarray = [
            'cart.*',
            'cartcategory.id as cartcategoryid','cartcategory.name as cartcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];*/

        $itemsArray = [
            'product.id as productid','title','imageurl','discountrate'
            ,'product.price','no_of_views'
        ];

        $cart = DB::table('cart')
            ->join('cartcategory','cart.cartcategoryid','=','cartcategory.id')
            ->join('carttype','cart.carttypeid','=','carttype.id')
            ->join('users','cart.userid','=','users.id')
            ->where('cart.id','=',$cartid)->select($cartfieldarray)->orderBy('cart.id','DESC')->distinct()->get();
        //->select($cartfieldarray)->orderBy('cart.id','DESC')->distinct()->paginate(10,$cartfieldarray);

        $items = DB::table('cartitem')->join('product','productid','=','product.id')
            ->join('cart','cartid','=','cart.id')
            ->where('cartid','=',$cartid)
            ->select($itemsArray)->orderBy('cart.id','DESC')->distinct()->paginate(10,$itemsArray);

        $carttypes = DB::table('carttype')->get();
        //echo(json_encode(["id"=>$cartid,"cart"=>$cart,"items"=>$items])); exit;

        //echo(json_encode($carttypes)); exit;
        if($request->wantsJson()){
            return response()->json(["cart"=>$cart,"items"=>$items,"success"=>true,"message"=>'orders fetched']);
        }
        return view('admin.cart.updatecart',['cart'=>$cart[0], "carttypes"=>$carttypes]);
    }


    protected function getCart(Request $request){
        $cartid = ($request->input('id')?$request->input('id'): $request->route('id'));
        //$cartstr = ['cart.id as id', 'cart.orderdate','cart.ordertime','cart.paid','cart.statusnote',
        //    'cart.updated_at as dateofpublication','orderamount','carttypeid'];
        $cartfieldarray = [
            'cart.*',
            'cartcategory.id as cartcategoryid','cartcategory.name as cartcategoryname',
            'carttype.id as cartttypeid','carttype.name as carttypename',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];

        $itemsArray = [
            'product.*', 'category.name as categoryname','productcategory.name as productcategoryname',
            "cartitem.price as cartitemprice","cartitem.quantity as cartitemquantity"
        ];

        $cart = DB::table('cart')
            ->join('cartcategory','cart.cartcategoryid','=','cartcategory.id')
            ->join('carttype','cart.carttypeid','=','carttype.id')
            ->join('users','cart.userid','=','users.id')
            ->where('cart.id','=',$cartid)
            ->select($cartfieldarray)->orderBy('cart.id','DESC')->get();

        $items = DB::table('cartitem')->join('product','productid','=','product.id')
            ->join('category','product.categoryid','=','category.id')
            ->join('productcategory','product.productcategoryid','=','productcategory.id')
            ->join('cart','cartid','=','cart.id')
            ->where('cartid','=',$cartid)
            ->select($itemsArray)->orderBy('product.title','DESC')->get();

        $carttypes = DB::table('carttype')->get();
        //echo(json_encode(["id"=>$cartid,"cart"=>$cart,"items"=>$items])); exit;

        if($request->wantsJson()){
            return response()->json(["cart"=>$cart[0],"cartitems"=>$items,"carttypes"=>$carttypes,"success"=>true,"message"=>'orders fetched']);
        }
        return view('admin.cart.updatecart',['cart'=>$cart[0], "carttypes"=>$carttypes]);
    }


    public  function registerCart(Request $request){

        $authenticatedUserId = 1;
        $authenticatedUser = null;
        //return response()->json(['password'=>$request->input('password'),'email'=>$request->input('email'),"USER"=>$authenticatedUser,'userID'=>$authenticatedUserId ]);
        if($request->has('logFromApp')){
            //try login then, else check if email exists else register new user;

            if(Auth::attempt(["email"=>$request->input('email'),"password"=>$request->input('password'),"id"=>$request->input('id')])){
                $authenticatedUserId = Auth::id();
                $authenticatedUser = Auth::user();

                //$authenticatedUser->password = $request->input('password');
                //return response()->json(["USER"=>$authenticatedUser,'userID'=>$authenticatedUserId ]);

            }else{

                $email = DB::table('users')->where("email","=",$request->input('email'))->get();
                //return response()->json(['email'=>$email]);
                if(!empty($email[0])){
                    if($request->wantsJson()){
                        return response()->json([
                            "success"=>false,
                            "message"=>'Wrong Password and Email Pair'
                        ]);
                    }else{
                        return back()->with(['output'=>'Wrong email and password pair', 'message'=>'not logged in member']);
                    }
                }else{
                    //register new user from UserTrait
                    $user = $this->registerUserGetUser($request);
                    $authenticatedUserId = $user->id;
                }
            }
        }elseif(Auth::check()){
            $authenticatedUserId = Auth::id();
        }else{
            return redirect()->route('login');
        }


        $requestArray = $request->only([
            'ordertime',
            'orderdate',
            'ordernote',
            'orderamount',
            'orderaddress',
            'cartcategoryid'

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


        //$imagepath = $request->file('imageurl')->store('/api/public/images/cart/');

        //try{

        $insertionArray_cart = array(
            "ordertime"=>$request->input('ordertime',date("Y-M-D")),
            "orderdate"=>$request->input('orderdate',date("H:i:s")),
            "orderamount"=>$request->input('orderamount','N/A'),
            "ordernote"=>$request->input('ordernote','N/A'),
            "orderaddress"=>$request->input('orderaddress','N/A'),
            "orderlocationid"=>$request->input('orderlocationid','38'),
            "ordersublocationid"=>$request->input('ordersublocationid','768'),
            "orderref"=>$request->input('orderref','N/A'),
            "userid"=>$request->input('userid',$authenticatedUserId),
            "statusnote"=>$request->input('statusnote','not supplied yet'),
            "cartcategoryid"=>$request->input('cartcategoryid',1),
            "carttypeid"=>$request->input('carttypeid',1)
        );

        //IF INSTANT PAY MARK AS PAID ELSE MARK AS UNPAID
        if($request->input('carttypeid')==1){
            $insertionArray_cart["paid"] = 'Y';
        }else{
            $insertionArray_cart["paid"] = "N";
        }

        if($cartid = DB::table('cart')->insertGetId($insertionArray_cart)){
            if($request->has('cartproducts')){
                $cartitemarray = array();
                $cartproducts = $request->input('cartproducts');
                for($i=0; $i<count($cartproducts); $i++){
                    $cartitemarray[] = array("cartid"=>$cartid, "productid"=>$cartproducts[$i]["id"], "price"=>$cartproducts[$i]["price"], "quantity"=>$cartproducts[$i]["quantity"]);
                }
                DB::table('cartitem')->insert($cartitemarray);
            }
        }

        $insertionArray_cart["id"] = $cartid;
        $cartObj = $insertionArray_cart;
        //$cart = DB::table('cart')->where('id','=',$cartid)->get();
        //$cartObj = (is_array($cart)? $cart[0]: $cart);


        $message = "Cart added successfully";
        if($request->wantsJson()){
            $userObject = ((!empty($user)?$user: $authenticatedUser));
            return response()->json(["success"=>true,"cart"=>$cartObj,"user"=>$userObject,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
        //}catch (Exception $e){
        //  if($request->wantsJson()){
        //    return response()->json(["success"=>false,"message"=>$e->getMessage()]);
        //  }else{
        //      return back()->with("output",$e->getMessage());
        //  }
        //}
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
                return response()->json(['success'=>false, 'message'=>'not logged in member']);
            }
        }elseif(Auth::check()){
            $authenticatedUserId = Auth::id();
        }else{
            return redirect()->route('login');
        }

        //$imagepath = $request->file('imageurl')->store('/api/public/images/cart/');

        $insertionArray_cart = array(
            "ordertime"=>$request->input('ordertime',date("Y-M-D")),
            "orderdate"=>$request->input('orderdate',date("H:i:s")),
            "orderamount"=>$request->input('orderamount',''),
            "ordernote"=>$request->input('ordernote','N/A'),
            "orderaddress"=>$request->input('orderaddress','N/A'),
            "orderref"=>$request->input('orderref','N/A'),
            //"userid"=>$request->input('userid',$authenticatedUserId),
            "paid"=>$request->input('paid','N'),
            "statusnote"=>$request->input('statusnote','not supplied yet'),
            "status"=>$request->input('status','N\A'),
            "cartcategoryid"=>$request->input('cartcategoryid',1),
            "carttypeid"=>$request->input('carttypeid',1)
        );

        $cart_id = ($request->input('id'));
        $cartid = DB::table('cart')->where('id','=',$cart_id)->update($insertionArray_cart);
        $message = "Profile Edited";
        if($request->wantsJson()){
            return response()->json(["success"=>true,"cartid"=>$cartid,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
        //return back()->with("output",$message);
    }

    public function createCategory(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

//create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];

        if(DB::table('cartcategory')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

//inserting new data for any table with id,name and description cols
//role and position tables fall within this category
    public function createCarttype(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

//create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];

        if(DB::table('carttype')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

//editing new data for any table with id,name and description cols
//role and position tables fall within this category
    public function editCategory(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

//;
        $id = $request->input('id');
        $name = $validatedData['name'];
        $description = $validatedData['description'];


        if($update = DB::table('cartcategory')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

//editing new data for any table with id,name and description cols
//role and position tables fall within this category
    public function editCarttype(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

//;
        $id = $request->input('id');
        $name = $validatedData['name'];
        $description = $validatedData['description'];


        if($update = DB::table('carttype')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }



}

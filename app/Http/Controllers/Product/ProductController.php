<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\models\product;
use App\models\focalarea;
use App\models\location;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use App\Traits\UserTrait;
use Illuminate\Queue\NullQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Exception;
class ProductController extends Controller
{
    //Use the trait for inserting product into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, products , products, facilities etc

    //use ManyToManyHandler;
    use UserTrait;

    protected $ObjectTypeId = 8;

    protected function getProducts(Request $request){
        $productfieldarray = [
            'product.id as id', 'product.title','product.imageurl as imageurl',
            'product.updated_at as dateofpublication','product.discountrate','quantity','price','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'productcategory.id as productcategoryid','productcategory.name as productcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];

        $products = DB::table('product')->join('category','product.categoryid','=','category.id')
            ->join('productcategory','product.productcategoryid','=','productcategory.id')
            ->join('users','product.userid','=','users.id')
            ->select($productfieldarray)->orderBy('product.id','DESC')->distinct()->paginate(10,$productfieldarray);

        if($request->wantsJson()){
            return response()->json(["products"=>$products]);
        }
        return view('admin.product.productpanel',['Adminproducts'=>$products]);
    }



    protected function getProductsByProperty(Request $request){


        $pty = ((($request->route('pty')))? $request->route('pty'): $request->input('pty'));
        $val = ((($request->route('val')))? $request->route('val'): $request->input('val'));
        //$val = $request->input('val');

        $ptyArray = [
            "pi"=>'product.id',"nov"=>'product.no_of_views',"ppr"=>'product.price',"pc"=>'product.categoryid',"ppc"=>'product.productcategoryid',
            "ui"=>'users.id'
        ];

        if(!empty($ptyArray[$pty])){

            $ptyString = $ptyArray[$pty];

        }else{
            if($request->wantsJson()){

                return response()->json(["success"=>false, "message"=>'Invalid request, Please be warned']);
            }
            return back()->with('output','Malicious request');
        }

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
            ->where($ptyArray[$pty],'=',$val)
            ->select($productfieldarray)->orderBy('product.id','DESC')->distinct()->paginate(100,$productfieldarray);

        if($request->wantsJson()){
            return response()->json(["products"=>$products,"success"=>true,"message"=>'products fetched']);
        }

        //echo(json_encode(["products"=>$products])); exit;
        return view('admin.product.updateproduct',['products'=>$products]);

    }

    protected function getAdminProducts(){
        $productfieldarray = [
            'product.id as id', 'product.title','product.imageurl as imageurl',
            'product.updated_at as dateofpublication','product.discountrate','quantity','price','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'productcategory.id as productcategoryid','productcategory.name as productcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $products = DB::table('product')->join('category','product.categoryid','=','category.id')
            ->join('productcategory','product.productcategoryid','=','productcategory.id')
            ->join('users','product.userid','=','users.id')
            ->select($productfieldarray)->orderBy('product.id','DESC')->distinct()->paginate(10,$productfieldarray);
        //$productcertificationlevels = DB::table('productcertificationlevel')->get();

        return view('admin.product.productpanel',['Adminproducts'=>$products]);
    }

    protected function getproductForEdit(Request $request){
        $productid = ($request->input('productid')?$request->input('productid'):$request->route('productid'));

        $productArray = ['product.*', 'users.id as usersid', 'users.name as usersname', 'users.imageurl as usersimageurl' ];
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');
        $filefieldsarray = array('id','objectid','fileurl','filetype','description');
        $commentfieldsarray = ['productcomment.*',
            'users.id as usersid', 'users.name as usersname', 'users.imageurl as usersimageurl'
        ];

        $product = DB::table('product')
            ->join('users', 'users.id','=','product.userid')->where('product.id','=',$productid)->select($productArray)->get();


        $files = DB::table('file')
            ->where(['objectid'=> $productid, 'objecttypeid'=>$this->ObjectTypeId])
            ->select($filefieldsarray)->get();

        $comments = DB::table('productcomment')->join('users','users.id','=','userid')
            ->where(['productid'=> $productid])
            ->select($commentfieldsarray)->get();

        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$productid, 'clusterobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $productid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectTypeId])
            ->select($focalareafieldsarray)->get();

        if($request->wantsJson()){
            return response()->json(["product"=>$product[0],"productfiles"=>$files, "product_comments"=>$comments, "product_locations"=>[], "product_sublocations"=>[], "product_clusters"=>$clusters, "product_focalareas"=>$focalareas, "success"=>true, "message"=>'product fetched successfully']);
        }

        return view('admin.product.updateproduct',["product"=>$product[0],"productfiles"=>$files, "product_locations"=>[], "product_sublocations"=>[], "product_clusters"=>$clusters, "product_focalareas"=>$focalareas]);
    }


    //
    protected function createProduct(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "price"=> 'required|digits_between:0,10',
            "quantity"=> 'required|digits_between:0,10',
            "detail"=> 'required|string',
            "discountrate"=> 'string',
            "categoryid"=>'required|integer',
            "productcategoryid"=>'required|integer',
            "imageurl"=> 'image|nullable'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/product/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/product/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'quantity'=>$validatedData['quantity'],
            'price'=>$validatedData['price'],
            'discountrate'=>$validatedData['discountrate'],
            'categoryid'=>$validatedData['categoryid'],
            'productcategoryid'=>$validatedData['productcategoryid'],
            'dateofpublication'=>date("Y-m-d h:i:s"),
            'imageurl'=>((!empty($url))?$url: NULL),
            'userid'=>$userid
        );
        if($productid = DB::table('product')->insertGetId($insertiondata)){
            //check for product pic
            if($request->hasFile('productfiles')){
                $insertArray = array();
                $productfilescount = count($request->file('productfiles'));
                for($i=0; $i<$productfilescount; $i++){
                    $filename = $request->file('productfiles')[$i]->getClientOriginalName().time().'.'.$request->file('productfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('productfiles')[$i]->storeAs('public/images/productfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/productfiles/'.$filename);
                    $filetype = $request->file('productfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['title'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$productid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }


                //echo implode("---", $insertArray)[0];
                //echo($insertArray[0]["objectid"]." obtt".$insertArray[0]["objecttypeid"]); exit;
                $sql = DB::table('file')->insert($insertArray);
            }

            //add product to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$productid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add product to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$productid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add product to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$productid,'eventlocation',$locationsscount,'location','productid','locationid');

            //add product to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$productid,'eventsublocation',$sublocationsscount,'sublocation','productid','sublocationid');

            $message = 'product Posted Successfully';
        }else{
            $message = 'product Not Posted Successfully';
        }

        return back()->with('output',$message);
    }



//
    protected function createProductComment(Request $request){
        $productid = ($request->input('productid')?$request->input('productid'): $request->route('productid'));


        //IF loggin from app, check for email and pass
        if($request->has('password')){
            if(!Auth::attempt($request->only(['email','password']))){
                $message = "You are not logged in";
                if($request->wantsJson()){
                    return response()->json(["success"=>false, "message"=>$message]);
                }
                return back()->with(["output"=>$message]);
            }

        }else{


            if(!Auth::check()){
                $message = "You are not logged in";
                if($request->wantsJson()){
                    return response()->json(["success"=>false, "message"=>$message]);
                }
                return back()->with(["output"=>$message]);
            }
        }


        $userid = Auth::id();



        $requestArray = $request->only([
            'detail','productid'
        ]);
        $validationArray = array(
            'detail'=> 'required|string',
            'productid'=>'required|integer'

        );

        if($request->has('imageurl')){
            $requestArray[] = 'imageurl';
            $validationArray['imageurl'] = 'image';
        }

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



//check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/productcomment/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/productcomment/'.$filename);
        }


//store validated data
        $insertiondata = array(
            'detail'=>$request->input('detail'),
            "userid"=>$userid,
            "productid"=>$productid,
            "updated_at"=>date("Y-M-D H:i:s"),
            "dateofpublication"=>date("Y-M-D H:i:s")
        );
        if($request->hasFile('imageurl')){
            $insertiondata['imageurl'] = (!empty($url)?$url: null);
        }

        if($productcommentid = DB::table('productcomment')->insertGetId($insertiondata)){
//add productcomment to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$productcommentid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add productcomment to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$productcommentid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            $message = 'ProductComment Posted Successfully';
        }else{

            $message = 'ProductComment Not Posted Successfully';

        }

        if($request->wantsJson()){
            return response()->json(["success"=>true, "message"=>$message, "commentid"=>$productcommentid]);
        }

        return back()->with('success',$message);
    }




    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function createCategory(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];

        if(DB::table('productcategory')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function createCertificationLevel(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];

        if(DB::table('productcertificationlevel')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //
    protected function editProduct(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }

        $userid = Auth::id();

        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "price"=> 'required|digits_between:0,10',
            "quantity"=> 'required|digits_between:0,10',
            "detail"=> 'required|string',
            "discountrate"=> 'string',
            "categoryid"=>'required|integer',
            "productcategoryid"=>'required|integer',
            "imageurl"=> 'image|nullable',
            "productid"=>'required|integer',
            "old_imageurl"=>'string|nullable'
        ]);


        $productid = $request->input('productid');
        //check for product pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/product/',$filename)){
                echo('No no image not stored');
            }
            Storage::delete($request->input('old_imageurl'));
            $url = Storage::url('images/product/'.$filename);
        }

        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'quantity'=>$validatedData['quantity'],
            'price'=>$validatedData['price'],
            'discountrate'=>$validatedData['discountrate'],
            'categoryid'=>$validatedData['categoryid'],
            'productcategoryid'=>$validatedData['productcategoryid'],
            'dateofpublication'=>date("Y-m-d h:i:s"),
            'imageurl'=>((!empty($url))?$url: $validatedData['old_imageurl']),
            'userid'=>$userid
        );

        if(DB::table('product')->where("id","=",$validatedData['productid'])->update($insertiondata)){
            //store images

            //echo('updated going to file');
            //check for product pic
            if($request->hasFile('productfiles')){
                $insertArray = array();
                $productfilescount = count($request->file('productfiles'));
                for($i=0; $i<$productfilescount; $i++){
                    $filename = $request->file('productfiles')[$i]->getClientOriginalName().time().'.'.$request->file('productfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('productfiles')[$i]->storeAs('public/images/productfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/productfiles/'.$filename);
                    $filetype = $request->file('productfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['title'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$productid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }


                //echo implode("---", $insertArray)[0];
                //echo($insertArray[0]["objectid"]." obtt".$insertArray[0]["objecttypeid"]); exit;
                $sql = DB::table('file')->insert($insertArray);
            }

            //echo('updated going to focalareas');
            //add product to focalareas
            $productid = $validatedData['productid'];
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$productid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add product to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$productid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add product to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$productid,'eventlocation',$locationsscount,'location','productid','locationid');

            //add product to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$productid,'eventsublocation',$sublocationsscount,'sublocation','productid','sublocationid');


            $message = 'product Posted Successfully';
        }else{
            $message = 'product Not fully updated';
        }

        //echo($message);
        return back()->with('output',$message);
    }




//
    protected function editProductComment(Request $request){

        $commentid = ($request->input('commentid')?$request->input('commentid'):$request->route('commentid'));

        //IF loggin from app, check for email and pass
        if($request->has('password')){
            if(!Auth::attempt($request->only(['email','password']))){
                $message = "You are not logged in";
                if($request->wantsJson()){
                    return response()->json(["success"=>false, "message"=>$message]);
                }
                return back()->with(["output"=>$message]);
            }

        }else{


            if(!Auth::check()){
                $message = "You are not logged in";
                if($request->wantsJson()){
                    return response()->json(["success"=>false, "message"=>$message]);
                }
                return back()->with(["output"=>$message]);
            }
        }


        $userid = Auth::id();



        $requestArray = $request->only([
            'detail',
            'old_imageurl'
        ]);
        $validationArray = array(
            'detail'=> 'required|string',
            'old_imageurl'=> 'string|nullable'

        );

        if($request->has('imageurl')){
            $requestArray[] = 'imageurl';
            $validationArray['imageurl'] = 'image';
        }

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



//check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/productcomment/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/productcomment/'.$filename);
        }


//store validated data
        $insertiondata = array(
            'detail'=>$request->input('detail'),
            "userid"=>$userid,
            "updated_at"=>date("Y-M-D H:i:s"),
            "dateofpublication"=>date("Y-M-D H:i:s")
        );
        if($request->hasFile('imageurl')){
            $insertiondata['imageurl'] = (!empty($url)?$url: null);
        }

        if($productcommentid = DB::table('productcomment')->where(['id'=>$commentid, 'userid'=>$userid])->update($insertiondata)){

            $message = 'ProductComment Posted Successfully';
        }else{

            $message = 'ProductComment Not Posted Successfully';

        }

        if($request->wantsJson()){
            return response()->json(["success"=>true, "message"=>$message, "commentid"=>$productcommentid]);
        }

        return back()->with('success',$message);
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


        if($update = DB::table('productcategory')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

    //editing new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function editCertificationLevel(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //;
        $id = $request->input('id');
        $name = $validatedData['name'];
        $description = $validatedData['description'];


        if($update = DB::table('productcertificationlevel')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }


    protected function removeProductFromGroups(Request $request){
        $productid = $request->input('productid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            echo($productid.' productid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0'));
            if($this->removeIdFromMultipleGroups($request,$productid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully <br> '.$this->ObjectTypeId.' was objttp and productid= '.$productid.' productid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0').' second='.$request->input('focalarea1');
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$productid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        return redirect()->route("/admin/product/");
    }


    protected function removeProductFromLocations(Request $request){
        $productid = $request->input('productid');

        //removing user to locations
        if(($request->has('locationscount'))){
            $location_count = $request->input('locationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$productid,'eventlocation',$location_count,'location','productid','locationid')){
                $message = 'Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        //removing user to sublocations
        if(($request->has('sublocationscount'))){
            $sublocation_count = $request->input('sublocationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$productid,'eventsublocation',$sublocation_count,'sublocation','productid','sublocationid')){
                $message = 'sub Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        $message = "No locations updated";
        return back()->with("output",$message);
    }


    protected function deleteproductFile(Request $request){
        $fileid = $request->input('fileid');
        $fileurl_1 = $request->input('fileurl',DB::table('file')->where('id','=',$fileid)->select('fileurl')->get());
        $fileurl = public_path($fileurl_1);
        //$fileurl = preg_replace('/\/storage/i',$_SERVER['DOCUMENT_ROOT'], $fileurl_1);
        //$fileurl = preg_replace('/\//i','\\',$fileurl);

        if((file_exists($fileurl))){
            if(unlink($fileurl)){
                DB::table('file')->where('id','=',$fileid)->delete();
                return back()->with('output','file deleted successfully ');
            }
        }
        return back()->with('output','file not deleted ');
    }






}

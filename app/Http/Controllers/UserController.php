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

use App\User;
use App\Traits\ManyToManyHandler;
class UserController extends Controller
{
    //Use the trait for inserting user into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, articles , events, facilities etc
    use ManyToManyHandler;
    //


    protected $ObjectTypeId = 7;
    protected  $ObjectId = 7;
    public function __construct(){

    }


    protected function getAdminUsers(){
        $userfieldsarray = array('users.id','users.name','users.imageurl', 'rolenote', 'role.name as rolename','position.name as positionname',
            'location.name as locationname','sublocation.name as sublocationname');

        $users = DB::table('users')
            ->join('role', 'users.roleid', '=', 'role.id')
            ->join('position', 'users.positionid', '=', 'position.id')
            ->join('location', 'users.locationid', '=', 'location.id')
            ->join('sublocation', 'users.sublocationid', '=', 'sublocation.id')
            ->select($userfieldsarray)->orderBy('users.id','DESC')->distinct()->paginate(10,$userfieldsarray);


        /*echo(count($users));
       return true;*/
        return view('admin.users.userspanel',array("AdminUsers"=>$users));
    }



    protected function getUsersByProperty(Request $request){


        $pty = ((($request->route('pty')))? $request->route('pty'): $request->input('pty'));
        $val = ((($request->route('val')))? $request->route('val'): $request->input('val'));
        //$val = $request->input('val');

        $ptyArray = [
            "ui"=> 'users.id',"email"=>'users.email', "ur"=>'users.roleid', "up"=>'users.postitionid',
            "ul"=>'users.locationid', "us"=>'users.sublocationid', "uf"=>'users.focalareaid'
        ];
        if(!empty($ptyArray[$pty])){

            $ptyString = $ptyArray[$pty];

        }else{
            if($request->wantsJson()){

                return response()->json(["success"=>false, "message"=>'Invalid request, Please be warned']);
            }
            return back()->with('output','Malicious request');
        }

        $userfieldsarray = array('users.id','users.name','users.imageurl', 'rolenote', 'role.name as rolename','position.name as positionname',
            'location.name as locationname','sublocation.name as sublocationname');

        $users = DB::table('users')
            ->join('role', 'users.roleid', '=', 'role.id')
            ->join('position', 'users.positionid', '=', 'position.id')
            ->join('location', 'users.locationid', '=', 'location.id')
            ->join('sublocation', 'users.sublocationid', '=', 'sublocation.id')
            ->where([$ptyString => $val])
            ->select($userfieldsarray)->orderBy('users.id','DESC')->distinct()->paginate(50,$userfieldsarray);



        //$users = $users[0];
        if($request->wantsJson()){
            return response()->json(["users"=>$users,"success"=>true, "message"=>'request successful']);
        }
        return view("admin.users.userspanel")->with(["AdminUsers" => $users]);
    }

    protected function getUser(Request $request){
        $userIdByRequest = $request->input('userid');
        $userIdByParam = $request->route('userid');
        $userid = ((!empty($userIdByParam)? $userIdByParam: $userIdByRequest));

        $userfieldArray = [
            'users.*', 'role.name as rolename','position.name as positionname','location.name as locationname', 'sublocation.name as sublocationname'
        ];
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');

        $user = DB::table('users')
            ->join('role','users.roleid','=','role.id')
            ->join('position','users.positionid','=','position.id')
            ->join('location','users.locationid','=','location.id')
            ->join('sublocation','users.sublocationid','=','sublocation.id')
            ->where('users.id','=',$userid)->select($userfieldArray)->get();

        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$userid, 'clusterobjecttype.objecttypeid'=> $this->ObjectId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $userid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectId])
            ->select($focalareafieldsarray)->get();

        $user = $user[0];
        if($request->wantsJson()){
            return response()->json(["user"=>$user, "user_clusters"=>$clusters, "user_focalareas"=>$focalareas,"success"=>true, "message"=>'got you']);
        }
        if($userIdByParam){
            return view('admin.users.userprofile',["user"=>$user, "user_clusters"=>$clusters, "user_focalareas"=>$focalareas,"success"=>true]);

        }
        return view('admin.users.updateuser',["user"=>$user, "user_clusters"=>$clusters, "user_focalareas"=>$focalareas]);
    }




    public  function registerUser(Request $request){
        $requestArray = [
            'name',
            'email',
            'mobile',
            'address'
        ];

        $validationArray = array(
            'name'=> 'required|string',
            "email"=>"required|email|unique:users",
            "mobile"=> "required",
            "address"=> 'string'
        );

        if($request->has('password_confirmation')){
            $requestArray[]= 'password';
            $requestArray[]= 'password_confirmation';
            $validationArray{"password"} = "required|confirmed";
        }


        $requestFullArray = $request->only($requestArray);
        $validator = Validator::make($requestFullArray,$validationArray);

        //$validator = $request->validate($validationArray);
        //u can do something like this:

    if ($validator->fails()) {

        if($request->wantsJson())
        {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorrect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        }else{
            return back()->withErrors($validator);
        }

    }

        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/users/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/users/'.$filename);
        }

        //$imagepath = $request->file('imageurl')->store('/api/public/images/users/');

        $insertionArray_user = array(
            "name"=>$request->input('name'),
            "email"=>$request->input('email'),
            "mobile"=>$request->input('mobile'),
            "password"=>Hash::make($request->input('password')),
            "address"=>$request->input('address', 'N\A'),
            "about"=>$request->input('about', 'Just an enthusiast'),
            "rolenote"=>$request->input('rolenote','just a good one'),
            "roleid"=>$request->input('roleid',1),
            "positionid"=>$request->input('positionid',1),
            "locationid"=>$request->input('locationid',38),
            "sublocationid"=>$request->input('sublocationid',768)
        );
        if(!empty($url)){
            $insertionArray_user{"imageurl"} = $url;
        }

        $userid = DB::table('users')->insertGetId($insertionArray_user);

        //$userid = DB::table('users')->where('email','=',$request->input('email'))->select('id')->get();
        //adding user to focalareas or department
        $focalarea_count = $request->input('focalareascount');
        $this->insertIdIntoMultipleGroups($request,$userid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',7);

        //ADDING USER TO CLUSTERS
        $cluster_count = $request->input('clusterscount');
        $this->insertIdIntoMultipleGroups($request,$userid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',7);

        $message = "User added successfully";
        if($request->wantsJson()){
            return response()->json(["success"=>true,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
    }


    protected function loginUser(Request $request){
        if(Auth::attempt($request->only(['email','password']))){
            $userid = Auth::id();
            $user = Auth::user();
            if($request->wantsJson()){
                return response()->json(["userid"=> $userid, "user"=>$user, "success"=>true, "message"=>'you are logged in succeddfully']);
            }else{
                return redirect()->route('userprofile',["userid"=>$userid]);
            }
        }
        return response()->json(["success"=>false,"message"=>'you are not a registered member']);
    }



    protected function editUserByUser(Request $request){
        $userid = ($request->route("userid") ? $request->route("userid") : $request->input('userid'));
        $old_email = $request->input('old_email');
        $requestArray = ([
            'name',
            'mobile',
            'address'
        ]);

        $validationArray = array(
            'name'=> 'required|string',
            "mobile"=> "required",
            "address"=> 'string'
        );

        if($request->has('password_confirmation')){
            $requestArray[]= 'password';
            $requestArray[]= 'password_confirmation';
            $validationArray{"password"} = "required|confirmed";
        }

        //if email is altered too; add email field validation
        if($request->input('email') != $old_email){
            $requestArray[] = 'email';
            $validationArray["email"] = "email|unique:users";
        }

        $requestFullArray = $request->only($requestArray);
        $validator = Validator::make($requestFullArray,$validationArray);

        //$validator = $request->validate($validationArray);
        //u can do something like this:

        if ($validator->fails()) {

            if($request->wantsJson())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorrect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }else{
                return back()->withErrors($validator);
            }

        }

        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/users/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/users/'.$filename);
        }

        $updateArray = array(
            "name"=>$request->input('name'),
            "mobile"=>$request->input('mobile'),
            //"password"=>Hash::make($request->input('password')),
            "address"=>$request->input('address', 'N\A'),
            "about"=>$request->input('about', 'Just an enthusiast'),
            "rolenote"=>$request->input('rolenote','just a good one'),
            "roleid"=>$request->input('roleid',1),
            "positionid"=>$request->input('positionid',2)
        );

        if($request->has('old_imageurl')){
            $updateArray["imageurl"] = ((!empty($url))? $url: $request->input('old_imageurl'));

        }
        //$imagepath = $request->file('imageurl')->store('/api/public/images/users/');
        DB::table('users')->where(['id'=>$userid,'email'=>$old_email])->update($updateArray);

        //$userid = DB::table('users')->where('email','=',$request->input('email'))->select('id')->get();
        //adding user to focalareas or department
        $focalarea_count = $request->input('focalareascount');
        $this->insertIdIntoMultipleGroups($request,$userid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

        //ADDING USER TO CLUSTERS
        $cluster_count = $request->input('clusterscount');
        $this->insertIdIntoMultipleGroups($request,$userid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

        $message = "Profile Edited";

        $message = "User added successfully";
        if($request->wantsJson()){
            return response()->json(["success"=>true,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
    }


    protected function editUserOneProperty(Request $request){
        $userid = ($request->route("userid") ? $request->route("userid") : $request->input('userid'));
        $old_email = $request->input('old_email');

        $requestArray = [
            'old_email'
        ];
        $validationArray = [
            "email" => 'email|exists:users'
        ];

        $requestFullArray = $request->only($requestArray);
        $validator = Validator::make($requestFullArray,$validationArray);

        //$validator = $request->validate($validationArray);
        //u can do something like this:

        if ($validator->fails()) {

            if($request->wantsJson())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorrect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }else{
                return back()->withErrors($validator);
            }

        }



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/users/',$filename)){
                echo('No no image not stored');
            }
            $url= Storage::url('images/users/'.$filename);
        };

        $pty = (($request->input('pty'))?$request->input('pty'): $request->route('pty'));
        $val = ((!empty($url)) ? $url : $request->route('val'));

        if($pty == 'password'){
            $val = Hash::make($val);
        }
        $updateArray = [$pty=>$val];
        DB::table('users')->where("id",'=',$userid)->where('email','=',$old_email)->update($updateArray);


        $message = "User added successfully";
        if($request->wantsJson()){
            return response()->json(["success"=>true,"message"=>$message]);
        }else{
            return back()->with("output",$message);
        }
    }




    protected function editUser(Request $request){
        $userid = $request->input('userid');
        $old_email = $request->input('old_email');


        $requestArray = ([
            'name',
            'mobile',
            'address','imageurl'
        ]);

        $validationArray = array(
            'name'=> 'required|string',
            "mobile"=> "required",
            "address"=> 'string',
            'imageurl'=>'image|nullable'
        );

        //if has passwod confirmation field then validata as such
        if($request->has('password_confirmation')){
            $requestArray[]= 'password';
            $requestArray[]= 'password_confirmation';
            $validationArray{"password"} = "required|confirmed";
        }
        //if email is altered too; add email field validation
        if($request->input('email') != $old_email){
            $requestArray[] = 'email';
            $validationArray["email"] = "email|unique:users";
        }



        $requestFullArray = $request->only($requestArray);
        $validator = Validator::make($requestFullArray,$validationArray);

        //$validator = $request->validate($validationArray);
        //u can do something like this:

        if ($validator->fails()) {

            if($request->wantsJson())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorrect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }else{
                return back()->withErrors($validator);
            }

        }

        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/users/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/users/'.$filename);
        }

        //$imagepath = $request->file('imageurl')->store('/api/public/images/users/');
       DB::table('users')->where(['id'=>$userid,'email'=>$old_email])->update(array(
            "name"=>$request->input('name'),
            "email"=>$request->input('email'),
            "mobile"=>$request->input('mobile'),
            "password"=>Hash::make($request->input('password')),
           "address"=>$request->input('address', 'N\A'),
            "about"=>$request->input('about', 'Just an enthusiast'),
            "imageurl"=>((!empty($url))? $url: $request->input('old_imageurl')),
            "rolenote"=>$request->input('rolenote','just a good one'),
            "roleid"=>$request->input('roleid',1),
            "positionid"=>$request->input('positionid',2)
        ));

        //$userid = DB::table('users')->where('email','=',$request->input('email'))->select('id')->get();
        //adding user to focalareas or department
        $focalarea_count = $request->input('focalareascount');
        $this->insertIdIntoMultipleGroups($request,$userid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

        //ADDING USER TO CLUSTERS
        $cluster_count = $request->input('clusterscount');
        $this->insertIdIntoMultipleGroups($request,$userid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

        $message = "Profile Edited";
        return back()->with("output",$message);
    }

    protected function removeUserFromGroups(Request $request){
        $userid = $request->input('userid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            if($this->removeIdFromMultipleGroups($request,$userid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$userid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
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

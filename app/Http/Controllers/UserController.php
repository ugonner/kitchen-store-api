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

    protected function getUser(Request $request){
        $userid = $request->input('userid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');

        $user = User::where('id','=',$userid)->select('*')->get();
        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$userid, 'clusterobjecttype.objecttypeid'=> $this->ObjectId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $userid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectId])
            ->select($focalareafieldsarray)->get();

        return view('admin.users.updateuser',["user"=>$user, "user_clusters"=>$clusters, "user_focalareas"=>$focalareas]);
    }



    public  function registerUser(Request $request){
        $validated = Validator::make($request->only([
            'name',
            'email',
            'password',
            'password_confirmation',
            'mobile'
        ]),array(
            'name'=> 'required|string',
            "email"=>"required|email|unique:users",
            "password"=>"required|confirmed",
            "mobile"=> "required",
            "imageurl"=> 'image|nullable'
        ));
        if($validated->fails()){
            Redirect::to('adminregform')->withErrors($validated)->withInput();
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

        $userid = DB::table('users')->insertGetId(array(
            "name"=>$request->input('name'),
            "email"=>$request->input('email'),
            "mobile"=>$request->input('mobile'),
            "password"=>Hash::make($request->input('password')),
            "about"=>$request->input('about', 'Just an enthusiast'),
            "imageurl"=>($url ? $url: '/api/images/users/user.jpg'),
            "rolenote"=>$request->input('rolenote','just a good one'),
            "roleid"=>$request->input('roleid',1),
            "positionid"=>$request->input('positionid',2),
            "locationid"=>$request->input('locationid',4),
            "sublocationid"=>$request->input('sublocationid',1)
        ));

        //$userid = DB::table('users')->where('email','=',$request->input('email'))->select('id')->get();
        //adding user to focalareas or department
        $focalarea_count = $request->input('focalareascount');
        $this->insertIdIntoMultipleGroups($request,$userid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',7);

        //ADDING USER TO CLUSTERS
        $cluster_count = $request->input('clusterscount');
        $this->insertIdIntoMultipleGroups($request,$userid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',7);

        $message = "User added successfully";
        return back()->with("output",$message);
    }


    protected function editUser(Request $request){
        $userid = $request->input('userid');
        $old_email = $request->input('old_email');
        $validated = Validator::make($request->only([
            'name',
            'password',
            'password_confirmation',
            'mobile',
            'imageurl'
        ]),array(
            'name'=> 'required|string',
            "password"=>"required|confirmed",
            "mobile"=> "required",
            "imageurl"=> 'image|nullable'
        ));
        if($validated->fails()){
            Redirect::to('adminregform')->withErrors($validated)->withInput();
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

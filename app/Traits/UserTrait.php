<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

use App\Traits\ManyToManyHandler;
trait UserTrait{

    use ManyToManyHandler;

    public  function registerUserGetId(Request $request){
        $requestArray = $request->only([
            'name',
            'email',
            'mobile',
            'address'
        ]);
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


        $validator = Validator::make($requestArray,$validationArray);

        //$validator = $request->validate($validationArray);
        //u can do something like this:

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
            "locationid"=>$request->input('locationid',4),
            "sublocationid"=>$request->input('sublocationid',1)
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
        $userid2 = (is_array($userid)? $userid[0]: $userid);
        return $userid2;

        //$message = "User added successfully";
        //if($request->wantsJson()){
        //    return response()->json(["results"=>true,"message"=>$message]);
        //}else{
        //    return back()->with("output",$message);
        //}
    }

    public function registerUserGetUser(Request $request){
        if($userid = $this->registerUserGetId($request)){
            $user = DB::table('users')->where('id','=',$userid)->get();
            $userObj = (is_array($user)? $user[0]: $user);
            return $userObj;
        }
        return false;
    }
}
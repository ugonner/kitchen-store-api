<?php

namespace App\Http\Controllers;

use \Illuminate\Database\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

use App\Traits\UserTrait;
class UserController extends Controller
{
    use UserTrait;
    //

    protected  $ObjectId = 1;
    public function __construct(){

    }


    protected function showRegisterForm(){
       return Redirect::to('auth/registration');
    }

    protected $redirectTo = RouteServiceProvider::HOME;
    public  function registerUser(Request $request){
        $validator = Validator::make($request->only([
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
        if($validator->fails()){
            Redirect::to('adminregform')->withErrors($validator)->withInput();
        }

        //check for profile pic
        if($request->hasFile('imageurl')){
            $imagepath = $request->file('imageurl')->store('/api/public/images/users/');
        }

        //$imagepath = $request->file('imageurl')->store('/api/public/images/users/');

        $userid = DB::table('users')->insertGetId(array(
            "name"=>$request->input('name'),
            "email"=>$request->input('email'),
            "mobile"=>$request->input('mobile'),
            "password"=>Hash::make($request->input('password')),
            "about"=>$request->input('about', 'Just an enthusiast'),
            "imageurl"=>($imagepath ? $imagepath: '/api/images/users/user.jpg'),
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

        return Auth::attempt($request->only(['email','password']));
    }



}

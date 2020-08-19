<?php

namespace App\Http\Controllers\Focalarea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FocalareaController extends Controller
{
    //


    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this focalarea
    public function createFocalarea(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];

        if(DB::table('focalarea')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //editing new data for any table with id,name and description cols
    //role and position tables fall within this focalarea
    public function editFocalarea(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //;
        $id = $request->input('id');
        $name = $validatedData['name'];
        $description = $validatedData['description'];


        if($update = DB::table('focalarea')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

}

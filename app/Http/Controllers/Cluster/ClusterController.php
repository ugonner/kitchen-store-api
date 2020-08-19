<?php

namespace App\Http\Controllers\Cluster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ClusterController extends Controller
{
    //


    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this cluster
    public function createCluster(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];

        if(DB::table('cluster')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //editing new data for any table with id,name and description cols
    //role and position tables fall within this cluster
    public function editCluster(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //;
        $id = $request->input('id');
        $name = $validatedData['name'];
        $description = $validatedData['description'];


        if($update = DB::table('cluster')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

}

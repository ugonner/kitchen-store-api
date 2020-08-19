<?php

namespace App\Http\Controllers\advert;

use App\Http\Controllers\Controller;
use App\models\advert;
use App\models\focalarea;
use App\models\location;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class AdvertController extends Controller
{
    //Use the trait for inserting advert into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, adverts , adverts, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 1;

    protected function getAdminAdverts(){
        $advertfieldarray = [
            'advert.id as id', 'advert.title','advert.imageurl as imageurl',
            'dateofpublication','no_of_views',
            'category.id as categoryid','category.name as categoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $adverts = DB::table('advert')->join('category','advert.categoryid','=','category.id')
            ->join('users','advert.userid','=','users.id')
            ->select($advertfieldarray)->orderBy('advert.id','DESC')->distinct()->paginate(10,$advertfieldarray);
        $advertplacemts = DB::table('placement')->get();

        return view('admin.advert.advertpanel',['Adminadverts'=>$adverts, "advertplacements"=>$advertplacemts]);
    }

    protected function getadvertForEdit(Request $request){
        $advertid = $request->input('advertid');
        $locationfieldsarray = array('locationid','location.name as locationname');
        $sublocationfieldsarray = array('sublocation.id as sublocationid','sublocation.name as sublocationname','locationid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');
        $filefieldsarray = array('id','objectid','fileurl','filetype','description');

        $advert = DB::table('advert')->where('id','=',$advertid)->select('*')->get();
        //echo(json_encode($advert));
        /*        $locations = DB::table('eventlocation')
                    ->join('location', 'eventlocation.locationid', '=', 'location.id')
                    ->where(['eventlocation.advertid'=>$advertid])
                    ->select($locationfieldsarray)->distinct()->get();
                $sublocations = DB::table('eventsublocation')
                    ->join('sublocation', 'eventsublocation.sublocationid', '=', 'sublocation.id')
                    ->where(['eventsublocation.advertid'=>$advertid])
                    ->select($sublocationfieldsarray)->distinct()->get();*/

        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$advertid, 'clusterobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $advertid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectTypeId])
            ->select($focalareafieldsarray)->get();

        $files = DB::table('file')
            ->where(['objectid'=> $advertid, 'objecttypeid'=>$this->ObjectTypeId])
            ->select($filefieldsarray)->get();

        return view('admin.advert.updateadvert',["advert"=>$advert[0],"advertfiles"=>$files, "advert_locations"=>[], "advert_sublocations"=>[], "advert_clusters"=>$clusters, "advert_focalareas"=>$focalareas]);
    }


    //
    protected function createAdvert(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "adverturl"=> 'required|string|max:255',
            "detail"=> 'required|string',
            "categoryid"=>'required|integer',
            "placementid"=>'required|integer',
            "clusterid"=>'required|integer',
            "focalareaid"=>'required|integer',
            "imageurl"=> 'image|nullable'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/advert/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/advert/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'adverturl'=>$validatedData['adverturl'],
            'placementid'=>$validatedData['placementid'],
            'categoryid'=>$validatedData['categoryid'],
            'clusterid'=>$validatedData['clusterid'],
            'focalareaid'=>$validatedData['focalareaid'],
            'imageurl'=>((!empty($url))?$url: NULL),
            'userid'=>$userid
        );
        if($advertid = DB::table('advert')->insertGetId($insertiondata)){
            //add advert to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$advertid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add advert to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$advertid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add advert to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$advertid,'eventlocation',$locationsscount,'location','advertid','locationid');

            //add advert to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$advertid,'eventsublocation',$sublocationsscount,'sublocation','advertid','sublocationid');

            $message = 'advert Posted Successfully';
        }else{
            $message = 'advert Not Posted Successfully';
        }

        return back()->with('output',$message);
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

        if(DB::table('placement')->insert(['name'=>$name,'description'=>$description])){
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

        if(DB::table('advertcertificationlevel')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //
    protected function editAdvert(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }

        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "detail"=> 'required',
            "adverturl"=> 'string',
            "categoryid"=>'required|integer',
            "imageurl"=> 'image|nullable',
            "advertid"=>'required|integer',
            "old_imageurl"=>'string|nullable',
            "clusterid"=>'integer',
            "focalareaid"=>'integer',
            "placementid"=>'integer'
            ]);



        $advertid = $request->input('advertid');
        //check for advert pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/advert/',$filename)){
                echo('No no image not stored');
            }
            Storage::delete($request->input('old_imageurl'));
            $url = Storage::url('images/advert/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'detail'=>$validatedData['detail'],
            'imageurl'=>((!empty($url))?$url: $validatedData['old_imageurl']),
            'title'=>$validatedData['title'],
            'adverturl'=>$validatedData['adverturl'],
            'placementid'=>$validatedData['placementid'],
            'categoryid'=>$validatedData['categoryid'],
            'clusterid'=>$validatedData['clusterid'],
            'focalareaid'=>$validatedData['focalareaid'],
            'userid'=>$userid
        );
        if(DB::table('advert')->where("id","=",$validatedData['advertid'])->update($insertiondata)){
            //store images

            //echo('updated going to file');
            //check for advert pic
            if($request->hasFile('advertfiles')){
                $insertArray = array();
                $advertfilescount = count($request->file('advertfiles'));
                for($i=0; $i<$advertfilescount; $i++){
                    $filename = $request->file('advertfiles')[$i]->getClientOriginalName().time().'.'.$request->file('advertfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('advertfiles')[$i]->storeAs('public/images/advertfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/advertfiles/'.$filename);
                    $filetype = $request->file('advertfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['title'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$advertid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }


                //echo implode("---", $insertArray)[0];
                //echo($insertArray[0]["objectid"]." obtt".$insertArray[0]["objecttypeid"]); exit;
                $sql = DB::table('file')->insert($insertArray);
            }

            //echo('updated going to focalareas');
            //add advert to focalareas
            $advertid = $validatedData['advertid'];
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$advertid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add advert to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$advertid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add advert to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$advertid,'eventlocation',$locationsscount,'location','advertid','locationid');

            //add advert to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$advertid,'eventsublocation',$sublocationsscount,'sublocation','advertid','sublocationid');


            $message = 'advert Posted Successfully';
        }else{
            $message = 'advert Not fully updated';
        }

        //echo($message);
        return back()->with('output',$message);
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


        if($update = DB::table('placement')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
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


        if($update = DB::table('advertcertificationlevel')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }


    protected function removeAdvertFromGroups(Request $request){
        $advertid = $request->input('advertid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            echo($advertid.' advertid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0'));
            if($this->removeIdFromMultipleGroups($request,$advertid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully <br> '.$this->ObjectTypeId.' was objttp and advertid= '.$advertid.' advertid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0').' second='.$request->input('focalarea1');
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$advertid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        return redirect()->route("/admin/advert/");
    }


    protected function removeAdvertFromLocations(Request $request){
        $advertid = $request->input('advertid');

        //removing user to locations
        if(($request->has('locationscount'))){
            $location_count = $request->input('locationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$advertid,'eventlocation',$location_count,'location','advertid','locationid')){
                $message = 'Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        //removing user to sublocations
        if(($request->has('sublocationscount'))){
            $sublocation_count = $request->input('sublocationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$advertid,'eventsublocation',$sublocation_count,'sublocation','advertid','sublocationid')){
                $message = 'sub Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        $message = "No locations updated";
        return back()->with("output",$message);
    }


    protected function deleteadvertFile(Request $request){
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

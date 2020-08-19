<?php

namespace App\Http\Controllers\facility;

use App\Http\Controllers\Controller;
use App\models\facility;
use App\models\focalarea;
use App\models\location;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class FacilityController extends Controller
{
    //Use the trait for inserting facility into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, facilitys , facilitys, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 5;

    protected function getAdminFacilitys(){
        $facilityfieldarray = [
            'facility.id as id', 'facility.title','facility.imageurl as imageurl',
            'dateofpublication','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'facilitycategory.id as facilitycategoryid','facilitycategory.name as facilitycategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $facilitys = DB::table('facility')->join('category','facility.categoryid','=','category.id')
            ->join('facilitycategory','facility.facilitycategoryid','=','facilitycategory.id')
            ->join('users','facility.userid','=','users.id')
            ->select($facilityfieldarray)->orderBy('facility.id','DESC')->distinct()->paginate(10,$facilityfieldarray);
        $facilitycertificationlevels = DB::table('facilitycertificationlevel')->get();

        return view('admin.facility.facilitypanel',['Adminfacilitys'=>$facilitys, "facilitycertificationlevels"=>$facilitycertificationlevels]);
    }

    protected function getfacilityForEdit(Request $request){
        $facilityid = $request->input('facilityid');
        $locationfieldsarray = array('locationid','location.name as locationname');
        $sublocationfieldsarray = array('sublocation.id as sublocationid','sublocation.name as sublocationname','locationid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');
        $filefieldsarray = array('id','objectid','fileurl','filetype','description');

        $facility = DB::table('facility')->where('id','=',$facilityid)->select('*')->get();
        //echo(json_encode($facility));

        $locations = DB::table('locationobjecttype')
            ->join('location', 'locationobjecttype.locationid', '=', 'location.id')
            ->where(['locationobjecttype.objectid'=>$facilityid, 'locationobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($locationfieldsarray)->distinct()->get();

        $sublocations = DB::table('sublocationobjecttype')
            ->join('sublocation', 'sublocationobjecttype.sublocationid', '=', 'sublocation.id')
            ->where(['sublocationobjecttype.objectid'=>$facilityid, 'sublocationobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($sublocationfieldsarray)->distinct()->get();

        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$facilityid, 'clusterobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $facilityid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectTypeId])
            ->select($focalareafieldsarray)->get();

        $files = DB::table('file')
            ->where(['objectid'=> $facilityid, 'objecttypeid'=>$this->ObjectTypeId])
            ->select($filefieldsarray)->get();

        //echo($facilityid ." id + obtty=".$this->ObjectTypeId); exit;
        return view('admin.facility.updatefacility',["facility"=>$facility[0],"facilityfiles"=>$files, "facility_locations"=>$locations, "facility_sublocations"=>$sublocations, "facility_clusters"=>$clusters, "facility_focalareas"=>$focalareas]);
    }


    //
    protected function createFacility(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "detail"=> 'required|string',
            "contact"=>'string',
            "contacturl"=>'string',
            "address"=>'required|string',
            "categoryid"=>'required|integer',
            "facilitycategoryid"=>'required|integer',
            "facilitycertificationlevelid"=>'required|integer',
            "locationid"=>'required|integer',
            "sublocationid"=>'required|integer',
            "imageurl"=> 'image|nullable'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/facility/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/facility/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'address'=>$validatedData['address'],
            'contact'=>$validatedData['contact'],
            'contacturl'=>$validatedData['contacturl'],
            'categoryid'=>$validatedData['categoryid'],
            'facilitycategoryid'=>$validatedData['facilitycategoryid'],
            'facilitycertificationlevelid'=>$validatedData['facilitycertificationlevelid'],
            'locationid'=>$validatedData['locationid'],
            'sublocationid'=>$validatedData['sublocationid'],
            'imageurl'=>((!empty($url))?$url: NULL),
            'userid'=>$userid
        );
        if($facilityid = DB::table('facility')->insertGetId($insertiondata)){
            //add facility to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$facilityid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add facility to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$facilityid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add facility to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups($request,$facilityid,'locationobjecttype',$locationsscount,'location','objectid','locationid','objecttypeid',$this->ObjectTypeId);

            //add facility to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups($request,$facilityid,'sublocationobjecttype',$sublocationsscount,'sublocation','objectid','sublocationid','objecttypeid',$this->ObjectTypeId);

            $message = 'facility Posted Successfully';
        }else{
            $message = 'facility Not Posted Successfully';
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

        if(DB::table('facilitycategory')->insert(['name'=>$name,'description'=>$description])){
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

        if(DB::table('facilitycertificationlevel')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //
    protected function editFacility(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }

        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "detail"=> 'required',
            "contact"=> 'string',
            "contacturl"=> 'string',
            "categoryid"=>'required|integer',
            "imageurl"=> 'image|nullable',
            "facilityid"=>'required|integer',
            "old_imageurl"=>'string|nullable',
            "address"=>'required|string',
            "locationid"=>'integer',
            "sublocationid"=>'integer',
            "facilitycertificationlevelid"=>'integer',
            "facilitycategoryid"=>'required|integer'
        ]);



        $facilityid = $request->input('facilityid');
        //check for facility pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/facility/',$filename)){
                echo('No no image not stored');
            }
            Storage::delete($request->input('old_imageurl'));
            $url = Storage::url('images/facility/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'contact'=>$validatedData['contact'],
            'contacturl'=>$validatedData['contacturl'],
            'categoryid'=>$validatedData['categoryid'],
            'imageurl'=>((!empty($url))?$url: $validatedData['old_imageurl']),
            'userid'=>$userid,
            'address'=>$validatedData['address'],
            'locationid'=>$validatedData['locationid'],
            'sublocationid'=>$validatedData['sublocationid'],
            'facilitycertificationlevelid'=>$validatedData['facilitycertificationlevelid'],
            'facilitycategoryid'=>$validatedData['facilitycategoryid']
        );
        if(DB::table('facility')->where("id","=",$validatedData['facilityid'])->update($insertiondata)){
            //store images

            //echo('updated going to file');
            //check for facility pic
            if($request->hasFile('facilityfiles')){
                $insertArray = array();
                $facilityfilescount = count($request->file('facilityfiles'));
                for($i=0; $i<$facilityfilescount; $i++){
                    $filename = $request->file('facilityfiles')[$i]->getClientOriginalName().time().'.'.$request->file('facilityfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('facilityfiles')[$i]->storeAs('public/images/facilityfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/facilityfiles/'.$filename);
                    $filetype = $request->file('facilityfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['title'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$facilityid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }


                //echo implode("---", $insertArray)[0];
                //echo($insertArray[0]["objectid"]." obtt".$insertArray[0]["objecttypeid"]); exit;
                $sql = DB::table('file')->insert($insertArray);
            }

            //echo('updated going to focalareas');
            //add facility to focalareas
            $facilityid = $validatedData['facilityid'];
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$facilityid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add facility to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$facilityid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add facility to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$facilityid,'eventlocation',$locationsscount,'location','facilityid','locationid');

            //add facility to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$facilityid,'eventsublocation',$sublocationsscount,'sublocation','facilityid','sublocationid');


            $message = 'facility Saved Successfully';
        }else{
            $message = 'facility Not fully updated';
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


        if($update = DB::table('facilitycategory')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
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


        if($update = DB::table('facilitycertificationlevel')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }


    protected function removeFacilityFromGroups(Request $request){
        $facilityid = $request->input('facilityid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            echo($facilityid.' facilityid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0'));
            if($this->removeIdFromMultipleGroups($request,$facilityid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully <br> '.$this->ObjectTypeId.' was objttp and facilityid= '.$facilityid.' facilityid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0').' second='.$request->input('focalarea1');
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$facilityid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        return redirect()->route("/admin/facility/");
    }


    protected function removeFacilityFromLocations(Request $request){
        $facilityid = $request->input('facilityid');

        //removing user to locations
        if(($request->has('locationscount'))){
            $location_count = $request->input('locationscount');
            if($this->removeIdFromMultipleGroups($request,$facilityid,'locationobjecttype',$location_count,'location','objectid','locationid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        //removing user to sublocations
        if(($request->has('sublocationscount'))){
            $sublocation_count = $request->input('sublocationscount');
            if($this->removeIdFromMultipleGroups($request,$facilityid,'sublocationobjecttype',$sublocation_count,'sublocation','objectid','sublocationid','objecttypeid',$this->ObjectTypeId)){
                $message = 'sub Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        $message = "No locations updated";
        return back()->with("output",$message);
    }


    protected function deletefacilityFile(Request $request){
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

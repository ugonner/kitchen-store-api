<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use App\models\organization;
use App\models\focalarea;
use App\models\location;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class OrganizationController extends Controller
{
    //Use the trait for inserting organization into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, organizations , organizations, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 6;

    protected function getAdminOrganizations(){
        $organizationfieldarray = [
            'organization.id as id', 'organization.name','organization.imageurl as imageurl',
            'dateofformation','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'organizationcategory.id as organizationcategoryid','organizationcategory.name as organizationcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $organizations = DB::table('organization')->join('category','organization.categoryid','=','category.id')
            ->join('organizationcategory','organization.organizationcategoryid','=','organizationcategory.id')
            ->join('users','organization.userid','=','users.id')
            ->select($organizationfieldarray)->orderBy('organization.id','DESC')->distinct()->paginate(10,$organizationfieldarray);
        $organizationcertificationlevels = DB::table('organizationcertificationlevel')->get();

        return view('admin.organization.organizationpanel',['Adminorganizations'=>$organizations, "organizationcertificationlevels"=>$organizationcertificationlevels]);
    }

    protected function getorganizationForEdit(Request $request){
        $organizationid = $request->input('organizationid');
        $locationfieldsarray = array('locationid','location.name as locationname');
        $sublocationfieldsarray = array('sublocation.id as sublocationid','sublocation.name as sublocationname','locationid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');
        $filefieldsarray = array('id','objectid','fileurl','filetype','description');

        $organization = DB::table('organization')->where('id','=',$organizationid)->select('*')->get();
        //echo(json_encode($organization));
/*        $locations = DB::table('eventlocation')
            ->join('location', 'eventlocation.locationid', '=', 'location.id')
            ->where(['eventlocation.organizationid'=>$organizationid])
            ->select($locationfieldsarray)->distinct()->get();
        $sublocations = DB::table('eventsublocation')
            ->join('sublocation', 'eventsublocation.sublocationid', '=', 'sublocation.id')
            ->where(['eventsublocation.organizationid'=>$organizationid])
            ->select($sublocationfieldsarray)->distinct()->get();*/

        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$organizationid, 'clusterobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $organizationid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectTypeId])
            ->select($focalareafieldsarray)->get();

        $files = DB::table('file')
            ->where(['objectid'=> $organizationid, 'objecttypeid'=>$this->ObjectTypeId])
            ->select($filefieldsarray)->get();

        return view('admin.organization.updateorganization',["organization"=>$organization[0],"organizationfiles"=>$files, "organization_locations"=>[], "organization_sublocations"=>[], "organization_clusters"=>$clusters, "organization_focalareas"=>$focalareas]);
    }


    //
    protected function createOrganization(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "name"=> 'required|string|max:255',
            "founder"=> 'required|string|max:255',
            "detail"=> 'required|string',
            "contact"=> 'string',
            "contacturl"=> 'string',
            "address"=>'required|string',
            "dateofformation"=>'string',
            "categoryid"=>'required|integer',
            "organizationcategoryid"=>'required|integer',
            "organizationcertificationlevelid"=>'required|integer',
            "focalareaid"=>'required|integer',
            "imageurl"=> 'image|nullable'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/organization/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/organization/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'name'=>$validatedData['name'],
            'detail'=>$validatedData['detail'],
            'contact'=>$validatedData['contact'],
            'contacturl'=>$validatedData['contacturl'],
            'founder'=>$validatedData['founder'],
            'address'=>$validatedData['address'],
            'dateofformation'=>$validatedData['dateofformation'],
            'categoryid'=>$validatedData['categoryid'],
            'organizationcategoryid'=>$validatedData['organizationcategoryid'],
            'organizationcertificationlevelid'=>$validatedData['organizationcertificationlevelid'],
            'focalareaid'=>$validatedData['focalareaid'],
            'imageurl'=>((!empty($url))?$url: NULL),
            'userid'=>$userid
        );
        if($organizationid = DB::table('organization')->insertGetId($insertiondata)){
            //add organization to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$organizationid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add organization to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$organizationid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add organization to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$organizationid,'eventlocation',$locationsscount,'location','organizationid','locationid');

            //add organization to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$organizationid,'eventsublocation',$sublocationsscount,'sublocation','organizationid','sublocationid');

            $message = 'organization Posted Successfully';
        }else{
            $message = 'organization Not Posted Successfully';
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

        if(DB::table('organizationcategory')->insert(['name'=>$name,'description'=>$description])){
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

        if(DB::table('organizationcertificationlevel')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //
    protected function editOrganization(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }

        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "name"=> 'required|string|max:255',
            "detail"=> 'required',
            "contact"=> 'string',
            "contacturl"=> 'string',
            "categoryid"=>'required|integer',
            "imageurl"=> 'image|nullable',
            "organizationid"=>'required|integer',
            "old_imageurl"=>'string|nullable',
            "address"=>'required|string',
            "focalareaid"=>'integer',
            "organizationcertificationlevelid"=>'integer',
            "dateofformation"=>'required|string',
            "founder"=>'required|string',
            "organizationcategoryid"=>'required|integer'
        ]);



        $organizationid = $request->input('organizationid');
        //check for organization pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/organization/',$filename)){
                echo('No no image not stored');
            }
            Storage::delete($request->input('old_imageurl'));
            $url = Storage::url('images/organization/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'name'=>$validatedData['name'],
            'detail'=>$validatedData['detail'],
            'contact'=>$validatedData['contact'],
            'contacturl'=>$validatedData['contacturl'],
            'categoryid'=>$validatedData['categoryid'],
            'imageurl'=>((!empty($url))?$url: $validatedData['old_imageurl']),
            'userid'=>$userid,
            'address'=>$validatedData['address'],
            'founder'=>$validatedData['founder'],
            'dateofformation'=>$validatedData['dateofformation'],
            'focalareaid'=>$validatedData['focalareaid'],
            'organizationcertificationlevelid'=>$validatedData['organizationcertificationlevelid'],
            'organizationcategoryid'=>$validatedData['organizationcategoryid']
        );
        if(DB::table('organization')->where("id","=",$validatedData['organizationid'])->update($insertiondata)){
            //store images

            //echo('updated going to file');
            //check for organization pic
            if($request->hasFile('organizationfiles')){
                $insertArray = array();
                $organizationfilescount = count($request->file('organizationfiles'));
                for($i=0; $i<$organizationfilescount; $i++){
                    $filename = $request->file('organizationfiles')[$i]->getClientOriginalName().time().'.'.$request->file('organizationfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('organizationfiles')[$i]->storeAs('public/images/organizationfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/organizationfiles/'.$filename);
                    $filetype = $request->file('organizationfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['name'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$organizationid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }


                //echo implode("---", $insertArray)[0];
                //echo($insertArray[0]["objectid"]." obtt".$insertArray[0]["objecttypeid"]); exit;
                $sql = DB::table('file')->insert($insertArray);
            }

            //echo('updated going to focalareas');
            //add organization to focalareas
            $organizationid = $validatedData['organizationid'];
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$organizationid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add organization to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$organizationid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add organization to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$organizationid,'eventlocation',$locationsscount,'location','organizationid','locationid');

            //add organization to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$organizationid,'eventsublocation',$sublocationsscount,'sublocation','organizationid','sublocationid');


            $message = 'organization Posted Successfully';
        }else{
            $message = 'organization Not fully updated';
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


        if($update = DB::table('organizationcategory')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
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


        if($update = DB::table('organizationcertificationlevel')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }


    protected function removeOrganizationFromGroups(Request $request){
        $organizationid = $request->input('organizationid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            echo($organizationid.' organizationid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0'));
            if($this->removeIdFromMultipleGroups($request,$organizationid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully <br> '.$this->ObjectTypeId.' was objttp and organizationid= '.$organizationid.' organizationid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0').' second='.$request->input('focalarea1');
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$organizationid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        return redirect()->route("/admin/organization/");
    }


    protected function removeOrganizationFromLocations(Request $request){
        $organizationid = $request->input('organizationid');

        //removing user to locations
        if(($request->has('locationscount'))){
            $location_count = $request->input('locationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$organizationid,'eventlocation',$location_count,'location','organizationid','locationid')){
                $message = 'Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        //removing user to sublocations
        if(($request->has('sublocationscount'))){
            $sublocation_count = $request->input('sublocationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$organizationid,'eventsublocation',$sublocation_count,'sublocation','organizationid','sublocationid')){
                $message = 'sub Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        $message = "No locations updated";
        return back()->with("output",$message);
    }


    protected function deleteorganizationFile(Request $request){
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

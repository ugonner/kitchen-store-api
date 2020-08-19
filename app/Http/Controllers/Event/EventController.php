<?php

namespace App\Http\Controllers\event;

use App\Http\Controllers\Controller;
use App\models\event;
use App\models\focalarea;
use App\models\location;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class EventController extends Controller
{
    //Use the trait for inserting event into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, events , events, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 4;

    protected function getAdminEvents(){
        $eventfieldarray = [
            'event.id as id', 'title','event.imageurl as imageurl',
            'dateofevent','no_of_comments','no_of_views','no_of_follows',
            'category.id as categoryid','category.name as categoryname',
            'eventcategory.id as eventcategoryid','eventcategory.name as eventcategoryname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $events = DB::table('event')->join('category','event.categoryid','=','category.id')
            ->join('eventcategory','event.eventcategoryid','=','eventcategory.id')
            ->join('users','event.userid','=','users.id')
            ->select($eventfieldarray)->orderBy('event.id','DESC')->distinct()->paginate(10,$eventfieldarray);

        return view('admin.event.eventpanel',['Adminevents'=>$events]);
    }

    protected function geteventForEdit(Request $request){
        $eventid = $request->input('eventid');
        $locationfieldsarray = array('locationid','location.name as locationname');
        $sublocationfieldsarray = array('sublocation.id as sublocationid','sublocation.name as sublocationname','locationid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');
        $filefieldsarray = array('id','objectid','fileurl','filetype','description');

        $event = DB::table('event')->where('id','=',$eventid)->select('*')->get();
        //echo(json_encode($event));
        $locations = DB::table('eventlocation')
            ->join('location', 'eventlocation.locationid', '=', 'location.id')
            ->where(['eventlocation.eventid'=>$eventid])
            ->select($locationfieldsarray)->distinct()->get();
        $sublocations = DB::table('eventsublocation')
            ->join('sublocation', 'eventsublocation.sublocationid', '=', 'sublocation.id')
            ->where(['eventsublocation.eventid'=>$eventid])
            ->select($sublocationfieldsarray)->distinct()->get();

        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$eventid, 'clusterobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $eventid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectTypeId])
            ->select($focalareafieldsarray)->get();

        $files = DB::table('file')
            ->where(['objectid'=> $eventid, 'objecttypeid'=>$this->ObjectTypeId])
            ->select($filefieldsarray)->get();

        return view('admin.event.updateevent',["event"=>$event[0],"eventfiles"=>$files, "event_locations"=>$locations, "event_sublocations"=>$sublocations, "event_clusters"=>$clusters, "event_focalareas"=>$focalareas]);
    }


    //
    protected function createEvent(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "detail"=> 'required|string',
            "contact"=> 'string',
            "contacturl"=> 'string',
            "venue"=>'required|string',
            "fee"=>'string',
            "frequency"=>'required|string',
            "dateofevent"=>'required|string',
            "timeofevent"=>'required|string',
            "organizer"=>'required|string',
            "eventcategoryid"=>'required|integer',
            "categoryid"=>'required|integer',
            "imageurl"=> 'image|nullable'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/event/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/event/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'contact'=>$validatedData['contact'],
            'contacturl'=>$validatedData['contacturl'],
            'venue'=>$validatedData['venue'],
            'organizer'=>$validatedData['organizer'],
            'dateofevent'=>$validatedData['dateofevent'],
            'timeofevent'=>$validatedData['timeofevent'],
            'fee'=>$validatedData['fee'],
            'frequency'=>$validatedData['frequency'],
            'categoryid'=>$validatedData['categoryid'],
            'eventcategoryid'=>$validatedData['eventcategoryid'],
            'imageurl'=>((!empty($url))?$url: NULL),
            'userid'=>$userid
        );
        if($eventid = DB::table('event')->insertGetId($insertiondata)){
            //add event to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$eventid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add event to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$eventid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add event to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$eventid,'eventlocation',$locationsscount,'location','eventid','locationid');

            //add event to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$eventid,'eventsublocation',$sublocationsscount,'sublocation','eventid','sublocationid');

            $message = 'event Posted Successfully';
        }else{
            $message = 'event Not Posted Successfully';
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

        if(DB::table('eventcategory')->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //
    protected function editEvent(Request $request){
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
            "eventid"=>'required|integer',
            "old_imageurl"=>'string|nullable',
            "venue"=>'required|string',
            "fee"=>'string',
            "frequency"=>'required|string',
            "dateofevent"=>'required|string',
            "timeofevent"=>'required|string',
            "organizer"=>'required|string',
            "eventcategoryid"=>'required|integer'
        ]);



        $eventid = $request->input('eventid');
        //check for event pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/event/',$filename)){
                echo('No no image not stored');
            }
            Storage::delete($request->input('old_imageurl'));
            $url = Storage::url('images/event/'.$filename);
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
            'venue'=>$validatedData['venue'],
            'organizer'=>$validatedData['organizer'],
            'dateofevent'=>$validatedData['dateofevent'],
            'timeofevent'=>$validatedData['timeofevent'],
            'fee'=>$validatedData['fee'],
            'frequency'=>$validatedData['frequency'],
            'eventcategoryid'=>$validatedData['eventcategoryid']
        );
        if(DB::table('event')->where("id","=",$validatedData['eventid'])->update($insertiondata)){
            //store images

            echo('updated going to file');
            //check for event pic
            if($request->hasFile('eventfiles')){
                $insertArray = array();
                $eventfilescount = count($request->file('eventfiles'));
                for($i=0; $i<$eventfilescount; $i++){
                    $filename = $request->file('eventfiles')[$i]->getClientOriginalName().time().'.'.$request->file('eventfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('eventfiles')[$i]->storeAs('public/images/eventfiles/',$filename)){
                        back()->with(['message'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/eventfiles/'.$filename);
                    $filetype = $request->file('eventfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['title'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$eventid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }
                DB::table('file')->insert($insertArray);
            }

            //echo('updated going to focalareas');
            //add event to focalareas
            $eventid = $validatedData['eventid'];
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$eventid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add event to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$eventid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);

            //add event to associated locations
            $locationsscount = $request->input('locationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$eventid,'eventlocation',$locationsscount,'location','eventid','locationid');

            //add event to associated sublocations
            $sublocationsscount = $request->input('sublocationscount');
            $this->insertIdIntoMultipleGroups_TwoCols($request,$eventid,'eventsublocation',$sublocationsscount,'sublocation','eventid','sublocationid');


            $message = 'event Posted Successfully';
        }else{
            $message = 'event Not fully updated';
        }

        echo($message);
        //return redirect()->route('/admin/user/',['message'=>$message]);
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


        if($update = DB::table('eventcategory')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

    protected function removeEventFromGroups(Request $request){
        $eventid = $request->input('eventid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            echo($eventid.' eventid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0'));
            if($this->removeIdFromMultipleGroups($request,$eventid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully <br> '.$this->ObjectTypeId.' was objttp and eventid= '.$eventid.' eventid and focalareacounts= '.$focalarea_count. ' focalarea0= '.$request->input('focalarea0').' second='.$request->input('focalarea1');
                return back()->with("output",$message);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$eventid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return back()->with("output",$message);
            }
        }

        return redirect()->route("/admin/event/");
    }


    protected function removeEventFromLocations(Request $request){
        $eventid = $request->input('eventid');

        //removing user to locations
        if(($request->has('locationscount'))){
            $location_count = $request->input('locationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$eventid,'eventlocation',$location_count,'location','eventid','locationid')){
                $message = 'Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        //removing user to sublocations
        if(($request->has('sublocationscount'))){
            $sublocation_count = $request->input('sublocationscount');
            if($this->removeIdFromMultipleGroups_TwoCols($request,$eventid,'eventsublocation',$sublocation_count,'sublocation','eventid','sublocationid')){
                $message = 'sub Locations updated successfully';
                return back()->with("output",$message);
            }
        }

        $message = "No locations updated";
        return back()->with("output",$message);
    }



    protected function deleteeventFile(Request $request){
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

<?php

namespace App\Http\Controllers\donation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class DonationController extends Controller
{
    //Use the trait for inserting donation into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, donations , donations, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 6;

    protected function getAdminDonations(){
        $donationfieldarray = [
            'donation.id as id', 'donation.description','donation.amount','donation.dateofdonation','donation.redeemed',
            'focalarea.id as focalareaid','focalarea.name as focalareaname',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];

        $donations = DB::table('donation')->join('focalarea','donation.focalareaid','=','focalarea.id')
            ->join('users','donation.userid','=','users.id')
            ->select($donationfieldarray)->orderBy('donation.id','DESC')->distinct()->paginate(10,$donationfieldarray);

        return view('admin.donation.donationpanel',['Admindonations'=>$donations]);
    }

    protected function getdonationForEdit(Request $request){
        $donationid = $request->input('donationid');

        $donation = DB::table('donation')->where('id','=',$donationid)->select('*')->get();
        //echo(json_encode($donation));

        return view('admin.donation.updatedonation',["donation"=>$donation[0]]);
    }


    //
    protected function createDonation(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        //$userid = Auth::id();
        //validate post data

        $validatedData = $request->validate([
            "name"=> 'required|string|max:255',
            "email"=> 'required|unique:users|string|max:255',
            "password"=> 'required|confirmed',
            "mobile"=> 'required|string|max:255',
            "imageurl"=> 'image|nullable',
            "description"=> 'required|string',
            "amount"=> 'string',
            "redeemed"=> 'string',
            "focalareaid"=>'required|integer'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/donation/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/donation/'.$filename);
        }


        //store validated data
        $insertion_user_data = array(
            'name'=>$validatedData['name'],
            'email'=>$validatedData['email'],
            'password'=>Hash::make($validatedData['password']),
            'mobile'=>$validatedData['mobile'],
            'imageurl'=>((!empty($url))?$url: NULL),
            'roleid'=>1,
            'rolenote'=>'Donor'
        );

        if($userid = DB::table('users')->insertGetId($insertion_user_data)){
            //store validated data
            $insertiondata = array(
                'description'=>$validatedData['description'],
                'amount'=>$validatedData['amount'],
                'focalareaid'=>$validatedData['focalareaid'],
                'redeemed'=>$validatedData['redeemed'],
                'userid'=>$userid
            );

            $donationid = DB::table('donation')->insertGetId($insertiondata);

            $message = 'donation Posted Successfully';
        }else{
            $message = 'donation Not Posted Successfully';
        }

        return back()->with('output',$message);
    }



    //
    protected function editDonation(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }

        //$userid = Auth::id();
        $validatedData = $request->validate([
            "description"=> 'required|string',
            "amount"=> 'string',
            "redeemed"=> 'string',
            "focalareaid"=>'required|integer',
            "donationid"=>'required|integer'
        ]);



        //$donationid = $request->input('donationid');
        //store validated data
        $insertiondata = array(
            'description'=>$validatedData['description'],
            'amount'=>$validatedData['amount'],
            'focalareaid'=>$validatedData['focalareaid'],
            'redeemed'=>$validatedData['redeemed']
        );

        if(DB::table('donation')->where("id","=",$validatedData['donationid'])->update($insertiondata)){
            //store images

            //echo('updated going to file');
            //check for donation pic
            if($request->hasFile('donationfiles')){
                $insertArray = array();
                $donationfilescount = count($request->file('donationfiles'));
                for($i=0; $i<$donationfilescount; $i++){
                    $filename = $request->file('donationfiles')[$i]->getClientOriginalName().time().'.'.$request->file('donationfiles')[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file('donationfiles')[$i]->storeAs('public/images/donationfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/donationfiles/'.$filename);
                    $filetype = $request->file('donationfiles')[$i]->getClientMimeType();
                    $description = 'File on '.$validatedData['name'];
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$donationid,'objecttypeid'=>$this->ObjectTypeId];
                    $insertArray[]=$fileobj;

                }


                //echo implode("---", $insertArray)[0];
                //echo($insertArray[0]["objectid"]." obtt".$insertArray[0]["objecttypeid"]); exit;
                $sql = DB::table('file')->insert($insertArray);
            }


            $message = 'donation Posted Successfully';
        }else{
            $message = 'donation Not fully updated';
        }

        //echo($message);
        return back()->with('output',$message);
    }


    protected function deletedonationFile(Request $request){
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

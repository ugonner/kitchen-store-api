<?php

namespace App\Http\Controllers\gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class GalleryController extends Controller
{
    //Use the trait for inserting gallery into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, gallerys , gallerys, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 6;

    protected function getAdminGallerys(){
        $galleryfieldarray = [
            'galleryfile.id as id', 'galleryfile.description','galleryfile.filetype','galleryfile.fileurl',
            'users.id as userid','users.name as username','users.imageurl as userimageurl'];

        $gallerys = DB::table('galleryfile')
            ->join('users','galleryfile.userid','=','users.id')
            ->select($galleryfieldarray)->orderBy('galleryfile.id','DESC')->distinct()->paginate(10,$galleryfieldarray);

        return view('admin.gallery.gallerypanel',['Admingalleryfiles'=>$gallerys]);
    }


    //
    protected function createGallery(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //uploading files in five groups of multiple-input-files
        //wtth uniform name and description label

        $insertArray = array();
        //looping through the FIVE groups of multiple-file-input elements
        for($glabel=0; $glabel<5; $glabel++){

            //formname for each groups description text
            $label = 'galleryfiles'.$glabel.'label';

            //formmae of each group
            $fileinput_formname = 'galleryfiles'.$glabel;

            //descriiption for each grouup
            $description = $request->input($label);

            if($request->hasFile($fileinput_formname)){
                $galleryfilescount = count($request->file($fileinput_formname));
                for($i=0; $i<$galleryfilescount; $i++){
                    $filename = $request->file($fileinput_formname)[$i]->getClientOriginalName().time().'.'.$request->file($fileinput_formname)[$i]->extension();

                    //echo($filename); //exit;
                    if(!$imagepath = $request->file($fileinput_formname)[$i]->storeAs('public/images/galleryfiles/',$filename)){
                        back()->with(['output'=>'No no image not stored']);
                    }
                    $fileurl = Storage::url('images/galleryfiles/'.$filename);
                    $filetype = $request->file($fileinput_formname)[$i]->getClientMimeType();
                    $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,"userid"=>$userid];
                    $insertArray[]=$fileobj;

                }
            }
        }
        if(!empty($insertArray)){
           DB::table('galleryfile')->insert($insertArray);
            $message = 'gallery Posted Successfully';
        }else{
            $message = 'gallery Not fully updated';
        }

        //echo($message);
        return back()->with('output',$message);
    }


    protected function deleteGalleryFile(Request $request){
        $fileid = $request->input('fileid');
        $fileurl_1 = $request->input('fileurl',DB::table('galleryfile')->where('id','=',$fileid)->select('fileurl')->get());
        $fileurl = public_path($fileurl_1);
        //$fileurl = preg_replace('/\/storage/i',$_SERVER['DOCUMENT_ROOT'], $fileurl_1);
        //$fileurl = preg_replace('/\//i','\\',$fileurl);

        if((file_exists($fileurl))){
            if(unlink($fileurl)){
                DB::table('galleryfile')->where('id','=',$fileid)->delete();
                return back()->with('output','file deleted successfully ');
            }
        }
        return back()->with('output','file not deleted ');
    }


}

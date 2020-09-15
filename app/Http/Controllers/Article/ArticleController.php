<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ManyToManyHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ArticleController extends Controller
{
    //Use the trait for inserting article into and removing out of many to many relationship tables like 'focalareaObjecttype table'
    //where 'Objecttype includes Objects: Users, articles , events, facilities etc
    use ManyToManyHandler;

    protected $ObjectTypeId = 2;

    protected function getArticles(Request $request, Response $response){
        $articlefieldarray = ['article.id as id', 'title','article.imageurl as imageurl','dateofpublication','no_of_comments','no_of_views','no_of_follows','category.id as categoryid','category.name as categoryname','users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $articles = DB::table('article')->join('category','article.categoryid','=','category.id')
            ->join('users','article.userid','=','users.id')
            ->select($articlefieldarray)->orderBy('article.id','DESC')->distinct()->paginate(10,$articlefieldarray);

        if($request->wantsJson()){
            return response()->json(array("articles"=>$articles));
        }
        return view('admin.article.articlepanel',['Adminarticles'=>$articles]);
    }

    protected function getAdminArticles(){
        $articlefieldarray = ['article.id as id', 'title','article.imageurl as imageurl','dateofpublication','no_of_comments','no_of_views','no_of_follows','category.id as categoryid','category.name as categoryname','users.id as userid','users.name as username','users.imageurl as userimageurl'];
        $articles = DB::table('article')->join('category','article.categoryid','=','category.id')
            ->join('users','article.userid','=','users.id')
            ->select($articlefieldarray)->orderBy('article.id','DESC')->distinct()->paginate(10,$articlefieldarray);

        return view('admin.article.articlepanel',['Adminarticles'=>$articles]);
    }

    protected function getArticleForEdit(Request $request){
        $articleid = $request->input('articleid');
        $clusterfieldsarray = array('clusterid','cluster.name as clustername','cluster.description as description');
        $focalareafieldsarray = array('focalareaid','focalarea.name as focalareaname','focalarea.description as description');
        $filefieldsarray = array('id','objectid','fileurl','filetype','description');

        $article = DB::table('article')->where('id','=',$articleid)->select('*')->get();
        //echo(json_encode($article));
        $clusters = DB::table('clusterobjecttype')
            ->join('cluster', 'clusterobjecttype.clusterid', '=', 'cluster.id')
            ->where(['clusterobjecttype.objectid'=>$articleid, 'clusterobjecttype.objecttypeid'=> $this->ObjectTypeId])
            ->select($clusterfieldsarray)->distinct()->get();

        $focalareas = DB::table('focalareaobjecttype')
            ->join('focalarea', 'focalareaobjecttype.focalareaid', '=', 'focalarea.id')
            ->where(['focalareaobjecttype.objectid'=> $articleid, 'focalareaobjecttype.objecttypeid'=>$this->ObjectTypeId])
            ->select($focalareafieldsarray)->get();

        $files = DB::table('file')
            ->where(['objectid'=> $articleid, 'objecttypeid'=>$this->ObjectTypeId])
            ->select($filefieldsarray)->get();

        return view('admin.article.updatearticle',["article"=>$article[0],"articlefiles"=>$files, "article_clusters"=>$clusters, "article_focalareas"=>$focalareas]);
    }


    //
    protected function createArticle(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "detail"=> 'required',
            "categoryid"=>'required|integer',
            "imageurl"=> 'image|nullable'
        ]);



        //check for profile pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/article/',$filename)){
                echo('No no image not stored');
            }
            $url = Storage::url('images/article/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'categoryid'=>$validatedData['categoryid'],
            'imageurl'=>((!empty($url))?$url: NULL),
            'userid'=>$userid
        );
        if($articleid = DB::table('article')->insertGetId($insertiondata)){
            //add article to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$articleid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add article to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$articleid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);
            $message = 'Article Posted Successfully';
        }else{
            $message = 'Article Not Posted Successfully';
        }

        return back()->with('success',$message);
    }



    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function createCategory(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string',
            'parentcategoryid'=>'integer|nullable'
        ]);

        //create role;
        $name = $validatedData['name'];
        $description = $validatedData['description'];
        $parentcategoryid = $validatedData['parentcategoryid'];

        if(DB::table('category')->insert(['name'=>$name,'description'=>$description,'parentcategoryid'=>$parentcategoryid,"objecttypeid"=>$this->ObjectTypeId])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return back()->with("output",$message);
    }

    //
    protected function editArticle(Request $request){
        if(!Auth::check()){
            $message = "Please log in first";
            return redirect()->route('login',["message"=>$message]);
        }
        $userid = Auth::id();
        //validate post data
        $validatedData = $request->validate([
            "title"=> 'required|string|max:255',
            "detail"=> 'required',
            "categoryid"=>'required|integer',
            "imageurl"=> 'image|nullable',
            "articleid"=>'required|integer',
            "old_imageurl"=>'string|nullable'
        ]);



        //check for article pic
        if($request->hasFile('imageurl') && $request->file('imageurl')->isValid()){
            $filename = $request->file('imageurl')->getClientOriginalName().time().'.'.$request->file('imageurl')->extension();
            if(!$imagepath = $request->file('imageurl')->storeAs('public/images/article/',$filename)){
                echo('No no image not stored');
            }
            Storage::delete($request->input('old_imageurl'));
            $url = Storage::url('images/article/'.$filename);
        }


        //store validated data
        $insertiondata = array(
            'title'=>$validatedData['title'],
            'detail'=>$validatedData['detail'],
            'categoryid'=>$validatedData['categoryid'],
            'imageurl'=>((!empty($url))?$url: $validatedData['old_imageurl']),
            'userid'=>$userid
        );
        if($articleid = DB::table('article')->where("id","=",$validatedData['articleid'])->update($insertiondata)){
            //store images

            //check for article pic
            if($request->hasFile('articlefiles')){
                $insertArray = array();
                $articlefilescount = count($request->file('articlefiles'));
                for($i=0; $i<$articlefilescount; $i++){
                        $filename = $request->file('articlefiles')[$i]->getClientOriginalName().time().'.'.$request->file('articlefiles')[$i]->extension();

                        //echo($filename); //exit;
                        if(!$imagepath = $request->file('articlefiles')[$i]->storeAs('public/images/articlefiles/',$filename)){
                            back()->with(['output'=>'No no image not stored']);
                        }
                        $fileurl = Storage::url('images/articlefiles/'.$filename);
                        $filetype = $request->file('articlefiles')[$i]->getClientMimeType();
                        $description = 'File on '.$validatedData['title'];
                        $fileobj = ['fileurl'=>$fileurl, 'filetype'=>$filetype, 'description'=>$description,'objectid'=>$articleid,'objecttypeid'=>$this->ObjectTypeId];
                        $insertArray[]=$fileobj;

                }
                DB::table('file')->insert($insertArray);
            }



            //add article to focalareas
            $focalareascount = $request->input('focalareascount');
            $this->insertIdIntoMultipleGroups($request,$articleid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

            //add article to clusters
            $clusterscount = $request->input('clusterscount');
            $this->insertIdIntoMultipleGroups($request,$articleid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);



            $message = 'Article Posted Successfully';
        }else{
            $message = 'Article Not Posted Successfully';
        }

        echo($message);
        //return redirect()->route('/admin/user/',['output'=>$message]);
    }


    //editing new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function editCategory(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string',
            'parentcategoryid'=>'integer|nullable'
        ]);

        //;
        $id = $request->input('id');
        $name = $validatedData['name'];
        $description = $validatedData['description'];
        $parentcategoryid = $validatedData['parentcategoryid'];


        if($update = DB::table('category')->where(["id"=>$id])->update(['name'=>$name,'description'=>$description,"parentcategoryid"=>$parentcategoryid,"objecttypeid"=>$this->ObjectTypeId])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return back()->with("output",$message);
    }

    protected function removeArticleFromGroups(Request $request){
        $articleid = $request->input('articleid');

        //removing user to focalareas or department
        if(($request->has('focalareascount'))){
            $focalarea_count = $request->input('focalareascount');
            if($this->removeIdFromMultipleGroups($request,$articleid,'focalareaobjecttype',$focalarea_count,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return redirect()->route('userspanel',["message"=>$message]);
            }
        }

        //REMOVING USER TO CLUSTERS
        if($request->has('clusterscount')){
            $cluster_count = $request->input('clusterscount');
            if($this->removeIdFromMultipleGroups($request,$articleid,'clusterobjecttype',$cluster_count,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId)){
                $message = 'Profile updated successfully';
                return redirect()->route('userspanel',["message"=>$message]);
            }
        }

        return redirect()->route("/admin/article/");
    }


    //inserting new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function createRoleOrPosition(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //create role;
        $name = $request->input('name');
        $description = $request->input('description');

        //form field 'r' or 'p' helps to determine table name for roles and position
        //else specify a tablename form field
        $form_tablename = ($request->has('tablename')?$request->input('tablename'): '');
        $tablename = (($request->has('p'))? 'position': ($request->has('r')? 'role': $form_tablename));

        if(DB::table($tablename)->insert(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully created';
        }else{
            $message = $name.' not created';
        }
        return Redirect::route('adminpanel',["message"=>$message]);
    }

    //editing new data for any table with id,name and description cols
    //role and position tables fall within this category
    public function editRoleOrPosition(Request $request){
        $validatedData = $request->validate([
            'name'=> 'required|string',
            'description' => 'required|string'
        ]);

        //;
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');

        //form field 'r' or 'p' helps to determine table name for roles and position
        //else specify a tablename form field
        $form_tablename = ($request->has('tablename')?$request->input('tablename'): '');
        $tablename = (($request->has('p'))? 'position': ($request->has('r')? 'role': $form_tablename));

        if($update = DB::table($tablename)->where(["id"=>$id])->update(['name'=>$name,'description'=>$description])){
            $message = $name.' successfully updated';
        }else{
            $message = $name.' not updated';
        }
        return redirect('admin/user/userspanel')->with(["message"=>$message]);
    }


    protected function deleteArticleFile(Request $request){
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

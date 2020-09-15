
//
protected function createProductComment(Request $request){
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
if(!$imagepath = $request->file('imageurl')->storeAs('public/images/productcomment/',$filename)){
echo('No no image not stored');
}
$url = Storage::url('images/productcomment/'.$filename);
}


//store validated data
$insertiondata = array(
'title'=>$validatedData['title'],
'detail'=>$validatedData['detail'],
'categoryid'=>$validatedData['categoryid'],
'imageurl'=>((!empty($url))?$url: NULL),
'userid'=>$userid
);
if($productcommentid = DB::table('productcomment')->insertGetId($insertiondata)){
//add productcomment to focalareas
$focalareascount = $request->input('focalareascount');
$this->insertIdIntoMultipleGroups($request,$productcommentid,'focalareaobjecttype',$focalareascount,'focalarea','objectid','focalareaid','objecttypeid',$this->ObjectTypeId);

//add productcomment to clusters
$clusterscount = $request->input('clusterscount');
$this->insertIdIntoMultipleGroups($request,$productcommentid,'clusterobjecttype',$clusterscount,'cluster','objectid','clusterid','objecttypeid',$this->ObjectTypeId);
$message = 'ProductComment Posted Successfully';
}else{
$message = 'ProductComment Not Posted Successfully';
}

return back()->with('success',$message);
}


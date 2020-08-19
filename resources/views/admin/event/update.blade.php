
Route::prefix('/admin/gallery')->group(function(){

Route::get("/",function(){
return view('admin/gallery/gallerypanel');
})->name('gallerypanel');

Route::get("updategallery",'Gallery\GalleryController@getgalleryForEdit')->name('updategallery');

Route::post('creategallery','Gallery\GalleryController@creategallery')->name('create_gallery');
Route::post('editgallery','Gallery\GalleryController@editgallery')->name('edit_gallery');


});

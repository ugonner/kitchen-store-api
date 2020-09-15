
Route::prefix('/admin/cart')->group(function(){

Route::get("/",'Cart\CartController@getAdminCarts')->name('cartpanel');

Route::get("cartform",function(){
return view('admin/cart/cartform');
})->name('admincartform');

Route::get("updatecart",'Cart\CartController@getCartForEdit')->name('updatecart');

Route::get("articlepanel",function(){
return view('admin/article/articlepanel');
})->name('adminarticlepanel');

Route::post('createcart','Cart\CartController@createCart')->name('createcart');
Route::post('editcart','Cart\CartController@editCart')->name('editcart');
//actual Remove from groups
Route::post('removecartFromGroups', 'Cart\CartController@removeCartFromGroups')->name('remove_cart_from_groups');
Route::post('removecartFromLocations', 'Cart\CartController@removeCartFromLocations')->name('remove_cart_from_locations');
Route::post('deleteCartFile', 'Cart\CartController@deleteCartFile')->name('deletecartFile');
//deleteArticleFile
//manage category (create and edit)
Route::post('createcartcategory', 'Cart\CartController@createCategory')->name('create_cartcategory');
Route::post('editcartcategory', 'Cart\CartController@editCategory')->name('edit_cartcategory');

});

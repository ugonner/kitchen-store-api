<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('auth')->get('/admin',function(){
    return view("layouts.admin");
})->name('admin');

Route::get('admin/getmenuitems',["uses"=>"Admin\AdminController@getMenuItems"])->name('getmenuitems');
Route::get('/admin/getindexproducts',["uses"=>"Admin\AdminController@getIndexProducts"])->name('getindexproducts');

Route::prefix('admin/user')->group(function(){

    Route::get('/', function(){
        return view('layouts.admin');
    })->name('adminpanel');

    //get users in admin panel
    Route::get('getusers/{pty}/{val}','UserController@getUsersByProperty')->name('getusersbyproperty');
    Route::get('userspanel','UserController@getAdminUsers')->name('userspanel');
    Route::get('userprofile/{userid}','UserController@getUser')->name('userprofile');

    Route::get('register', function(){
        return view('admin.users.register');
    })->name('adminregform');
    Route::get('updateuser/', 'UserController@getUser')->name('updateuser');

    //actural registration
    Route::post('registration', 'UserController@registerUser')->name('adminreg');
    Route::post('login', 'UserController@loginUser')->name('adminlogin');

    //actual update
    Route::post('update', 'UserController@editUser')->name('update');
    //update by public users
    Route::post('updateprofile', 'UserController@editUserByUser')->name('updateprofile');

    //update by public users
    Route::post('edit', 'UserController@editUserOneProperty')->name('updateprofile');

    //actual Remove from groups
    Route::post('removeUserFromGroups', 'UserController@removeUserFromGroups')->name('remove_user_from_groups');

    //actual creation roles or position
    Route::post('create_roles_or_positions', 'UserController@createRoleOrPosition')->name('create_roles_or_positions');

    //actual editing roles or position
    Route::post('edit_roles_or_positions', 'UserController@editRoleOrPosition')->name('edit_roles_or_positions');

    //actual creation roles or position
    Route::post('delete_roles_or_positions', 'UserController@deleteRoleOrPosition')->name('delete_roles_or_positions');

});

Route::prefix('/admin/article')->group(function(){

    Route::get("/",'Article\ArticleController@getAdminArticles')->name('articlepanel');

    Route::get("/getarticles",'Article\ArticleController@getArticles')->name('getarticles');

    Route::get("articleform",function(){
        return view('admin/article/articleform');
    })->name('adminarticleform');

    Route::get("updatearticle","Article\ArticleController@getArticleForEdit")->name('updatearticle');

    Route::get("articlepanel",function(){
        return view('admin/article/articlepanel');
    })->name('adminarticlepanel');

    Route::post('createarticle','Article\ArticleController@createArticle')->name('createarticle');
    Route::post('editarticle','Article\ArticleController@editArticle')->name('editarticle');
    //actual Remove from groups
    Route::post('removeArticleFromGroups', 'Article\ArticleController@removeArticleFromGroups')->name('remove_article_from_groups');
    Route::post('deleteArticleFile', 'Article\ArticleController@deleteArticleFile')->name('deleteArticleFile');

    //manage category (create and edit)
    Route::post('createcategory', 'Article\ArticleController@createCategory')->name('create_category');
    Route::post('editcategory', 'Article\ArticleController@editCategory')->name('edit_category');

});

Route::prefix('/admin/cart')->group(function(){

    Route::get("/",'Cart\CartController@getCarts')->name('cartpanel');
    Route::get("/getcart/id/{id}",'Cart\CartController@getCart')->name('getcart_with_route_parameters');
    Route::get("/getcart",'Cart\CartController@getCart')->name('getcart');
    Route::get("/getcarts/{pty}/{val}",'Cart\CartController@getCartsByProperty')->name('getcarts_filtered');

    Route::get("cartform",function(){
        return view('admin/cart/cartform');
    })->name('admincartform');

    Route::get("editcart/{cartid}",'Cart\CartController@getCartForEdit')->name('editcart');


    Route::post('createcart',"Cart\CartController@registerCart")->name('createcart');
    Route::post('updatecart','Cart\CartController@editCart')->name('updatecart');
//actual Remove from groups
    Route::post('removecartFromGroups', 'Cart\CartController@removeCartFromGroups')->name('remove_cart_from_groups');
    Route::post('removecartFromLocations', 'Cart\CartController@removeCartFromLocations')->name('remove_cart_from_locations');
    Route::post('deleteCartFile', 'Cart\CartController@deleteCartFile')->name('deletecartFile');
//deleteArticleFile
//manage category (create and edit)
    Route::post('createcartcategory', ["uses"=>"Cart\CartController@createCategory"])->name('create_cartcategory');
    Route::post('editcartcategory', ["uses"=>'Cart\CartController@editCategory'])->name('edit_cartcategory');

//manage organisation certification levels
    Route::post('createcarttype', ["uses"=>"Cart\CartController@createCarttype"])->name('create_carttype');
    Route::post('editcarttype', ["uses"=>"Cart\CartController@editCarttype"])->name('edit_carttype');

});


Route::prefix('/admin/event')->group(function(){

    Route::get("/",'Event\EventController@getAdminEvents')->name('eventpanel');

    Route::get("eventform",function(){
        return view('admin/event/eventform');
    })->name('admineventform');

    Route::get("updateevent",'Event\EventController@getEventForEdit')->name('updateevent');

    Route::get("articlepanel",function(){
        return view('admin/article/articlepanel');
    })->name('adminarticlepanel');

    Route::post('createevent','Event\EventController@createEvent')->name('createevent');
    Route::post('editevent','Event\EventController@editEvent')->name('editevent');
    //actual Remove from groups
    Route::post('removeeventFromGroups', 'Event\EventController@removeEventFromGroups')->name('remove_event_from_groups');
    Route::post('removeeventFromLocations', 'Event\EventController@removeEventFromLocations')->name('remove_event_from_locations');
    Route::post('deleteEventFile', 'Event\EventController@deleteEventFile')->name('deleteeventFile');
//deleteArticleFile
    //manage category (create and edit)
    Route::post('createeventcategory', 'Event\EventController@createCategory')->name('create_eventcategory');
    Route::post('editeventcategory', 'Event\EventController@editCategory')->name('edit_eventcategory');

});


Route::prefix('/admin/organization')->group(function(){

    Route::get("/",'Organization\OrganizationController@getAdminorganizations')->name('organizationpanel');

    Route::get("organizationform",function(){
        return view('admin/organization/organizationform');
    })->name('adminorganizationform');

    Route::get("updateorganization",'Organization\OrganizationController@getorganizationForEdit')->name('updateorganization');

    Route::get("articlepanel",function(){
        return view('admin/article/articlepanel');
    })->name('adminarticlepanel');

    Route::post('createorganization','Organization\OrganizationController@createorganization')->name('createorganization');
    Route::post('editorganization','Organization\OrganizationController@editorganization')->name('editorganization');
//actual Remove from groups
    Route::post('removeorganizationFromGroups', 'Organization\OrganizationController@removeorganizationFromGroups')->name('remove_organization_from_groups');
    Route::post('removeorganizationFromLocations', 'Organization\OrganizationController@removeorganizationFromLocations')->name('remove_organization_from_locations');
    Route::post('deleteorganizationFile', 'Organization\OrganizationController@deleteorganizationFile')->name('deleteorganizationFile');
//deleteArticleFile
//manage category (create and edit)
    Route::post('createorganizationcategory', 'Organization\OrganizationController@createCategory')->name('create_organizationcategory');
    Route::post('editorganizationcategory', 'Organization\OrganizationController@editCategory')->name('edit_organizationcategory');

    //manage organisation certification levels
    Route::post('createorganizationcertificationlevel', 'Organization\OrganizationController@createCertificationLevel')->name('create_organizationcertificationlevel');
    Route::post('editorganizationcertificationlevel', 'Organization\OrganizationController@editCertificationlevel')->name('edit_organizationcertificationlevel');

});



Route::prefix('/admin/facility')->group(function(){

    Route::get("/",'Facility\FacilityController@getAdminfacilitys')->name('facilitypanel');

    Route::get("facilityform",function(){
        return view('admin/facility/facilityform');
    })->name('adminfacilityform');

    Route::get("updatefacility",'Facility\FacilityController@getfacilityForEdit')->name('updatefacility');

    Route::get("articlepanel",function(){
        return view('admin/article/articlepanel');
    })->name('adminarticlepanel');

    Route::post('createfacility','Facility\FacilityController@createfacility')->name('createfacility');
    Route::post('editfacility','Facility\FacilityController@editfacility')->name('editfacility');
//actual Remove from groups
    Route::post('removefacilityFromGroups', 'Facility\FacilityController@removefacilityFromGroups')->name('remove_facility_from_groups');
    Route::post('removefacilityFromLocations', 'Facility\FacilityController@removefacilityFromLocations')->name('remove_facility_from_locations');
    Route::post('deletefacilityFile', 'Facility\FacilityController@deletefacilityFile')->name('deletefacilityFile');
//deleteArticleFile
//manage category (create and edit)
    Route::post('createfacilitycategory', 'Facility\FacilityController@createCategory')->name('create_facilitycategory');
    Route::post('editfacilitycategory', 'Facility\FacilityController@editCategory')->name('edit_facilitycategory');

//manage organisation certification levels
    Route::post('createfacilitycertificationlevel', 'Facility\FacilityController@createCertificationLevel')->name('create_facilitycertificationlevel');
    Route::post('editfacilitycertificationlevel', 'Facility\FacilityController@editCertificationlevel')->name('edit_facilitycertificationlevel');

});




Route::prefix('/admin/advert')->group(function(){

    Route::get("/",'Advert\AdvertController@getAdminadverts')->name('advertpanel');

    Route::get("advertform",function(){
        return view('admin/advert/advertform');
    })->name('adminadvertform');

    Route::get("updateadvert",'Advert\AdvertController@getadvertForEdit')->name('updateadvert');

    Route::get("articlepanel",function(){
        return view('admin/article/articlepanel');
    })->name('adminarticlepanel');

    Route::post('createadvert','Advert\AdvertController@createadvert')->name('createadvert');
    Route::post('editadvert','Advert\AdvertController@editadvert')->name('editadvert');
//actual Remove from groups
    Route::post('removeadvertFromGroups', 'Advert\AdvertController@removeadvertFromGroups')->name('remove_advert_from_groups');
    Route::post('removeadvertFromLocations', 'Advert\AdvertController@removeadvertFromLocations')->name('remove_advert_from_locations');
    Route::post('deleteadvertFile', 'Advert\AdvertController@deleteadvertFile')->name('deleteadvertFile');
//deleteArticleFile
//manage category (create and edit)
    Route::post('createadvertcategory', 'Advert\AdvertController@createCategory')->name('create_advertcategory');
    Route::post('editadvertcategory', 'Advert\AdvertController@editCategory')->name('edit_advertcategory');

//manage organisation certification levels
    Route::post('createadvertcertificationlevel', 'Advert\AdvertController@createCertificationLevel')->name('create_advertcertificationlevel');
    Route::post('editadvertcertificationlevel', 'Advert\AdvertController@editCertificationlevel')->name('edit_advertcertificationlevel');

});




Route::prefix('/admin/donation')->group(function(){

    Route::get("/",'Donation\DonationController@getAdmindonations')->name('donationpanel');

    Route::get("donationform",function(){
        return view('admin/donation/donationform');
    })->name('admindonationform');

    Route::get("updatedonation",'Donation\DonationController@getdonationForEdit')->name('updatedonation');

    Route::post('createdonation','Donation\DonationController@createdonation')->name('createdonation');
    Route::post('editdonation','Donation\DonationController@editdonation')->name('editdonation');


});


Route::prefix('/admin/cluster')->group(function(){

    Route::get("/",function(){
        return view('admin/cluster/clusterpanel');
    })->name('clusterpanel');

    Route::get("updatecluster",'Cluster\ClusterController@getclusterForEdit')->name('updatecluster');

    Route::post('createcluster','Cluster\ClusterController@createcluster')->name('create_cluster');
    Route::post('editcluster','Cluster\ClusterController@editcluster')->name('edit_cluster');


});



Route::prefix('/admin/focalarea')->group(function(){

    Route::get("/",function(){
        return view('admin/focalarea/focalareapanel');
    })->name('focalareapanel');

    Route::get("updatefocalarea",'Focalarea\FocalareaController@getfocalareaForEdit')->name('updatefocalarea');

    Route::post('createfocalarea','Focalarea\FocalareaController@createfocalarea')->name('create_focalarea');
    Route::post('editfocalarea','Focalarea\FocalareaController@editfocalarea')->name('edit_focalarea');


});



Route::prefix('/admin/gallery')->group(function(){

    /*Route::get("/",function(){
        return view('admin/gallery/gallerypanel');
    })->name('gallerypanel');*/

    Route::get("/",'Gallery\GalleryController@getAdmingallerys')->name('gallerypanel');

    Route::get("updategallery",'Gallery\GalleryController@getgalleryForEdit')->name('updategallery');

    Route::post('creategallery','Gallery\GalleryController@createGallery')->name('create_gallery');
    Route::post('deletegalleryFile', 'Gallery\GalleryController@deleteGalleryFile')->name('delete_gallery_file');


});


Route::prefix('/admin/product')->group(function(){
    Route::get("getproducts",'Product\ProductController@getProducts')->name('getproducts');
    Route::get("getproducts/{pty}/{val}",'Product\ProductController@getProductsByProperty')->name('getproducts_filtered');



    Route::get("/",'Product\ProductController@getAdminproducts')->name('productpanel');

    Route::get("productform",function(){
        return view('admin/product/productform');
    })->name('adminproductform');

    Route::get("updateproduct",'Product\ProductController@getproductForEdit')->name('updateproduct');
    Route::get("getproduct/productid/{productid}",'Product\ProductController@getproductForEdit')->name('getproduct_with_route_parameters');
    Route::get("getproduct",'Product\ProductController@getproductForEdit')->name('getproduct');

    Route::get("articlepanel",function(){
        return view('admin/article/articlepanel');
    })->name('adminarticlepanel');

    Route::post('createproduct','Product\ProductController@createproduct')->name('createproduct');
    Route::post('editproduct','Product\ProductController@editProduct')->name('editproduct');

    //CREATING AND EDITING COMMENTS ON PRODUCT
    Route::post('createproductcomment','Product\ProductController@createProductComment')->name('createproductcomment');
    Route::post('editproductcomment','Product\ProductController@editProductComment')->name('editproductcomment');

//actual Remove from groups
    Route::post('removeproductFromGroups', 'Product\ProductController@removeproductFromGroups')->name('remove_product_from_groups');
    Route::post('removeproductFromLocations', 'Product\ProductController@removeproductFromLocations')->name('remove_product_from_locations');
    Route::post('deleteproductFile', 'Product\ProductController@deleteproductFile')->name('deleteproductFile');
//deleteArticleFile
//manage category (create and edit)
    Route::post('createproductcategory', 'Product\ProductController@createCategory')->name('create_productcategory');
    Route::post('editproductcategory', 'Product\ProductController@editCategory')->name('edit_productcategory');

//manage organisation certification levels
    Route::post('createproductcertificationlevel', 'Product\ProductController@createCertificationLevel')->name('create_productcertificationlevel');
    Route::post('editproductcertificationlevel', 'Product\ProductController@editCertificationlevel')->name('edit_productcertificationlevel');

});


Route::get('/seedtables', 'seeders\SeederController@seedTables')->name('seed');

Route::get('/home', 'HomeController@index')->name('home');

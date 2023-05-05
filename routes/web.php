<?php

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

Route::group(['namespace' => 'App\Http\Controllers\Admin','prefix' => env('admin')], function(){

    Route::get('/','AdminController@index');
    Route::get('login','AdminController@index');
    Route::post('login','AdminController@login');
    
    Route::group(['middleware' => 'admin'], function(){
        /*
        |-----------------------------------------
        |Dashboard and Account Setting & Logout
        |-----------------------------------------
        */
        Route::get('home','AdminController@home');
        Route::get('setting','AdminController@setting');
        Route::post('setting','AdminController@update');
        Route::get('logout','AdminController@logout');

        
        /*
        |------------------------------
        |Manage App Pages
        |------------------------------
        */
        Route::resource('page','PageController');
        Route::resource('text','TextController');
        
        /*
        |------------------------------
        |Manage Users
        |------------------------------
        */
        Route::resource('user','UserController');
        Route::get('user/delete/{id}','UserController@delete');
        Route::get('user/status/{id}','UserController@status');
        Route::get('user/{id}/viewqr','UserController@viewqr');
        Route::get('imageRemove/{id}','UserController@imageRemove');
        Route::get('loginWithID/{id}','UserController@loginWithID');
        Route::get('user/pay/{id}','UserController@pay');
        Route::patch('user_pay/{id}','UserController@user_pay');
        
        /*
        |------------------------------
        |Manage Sub-Users
        |------------------------------
        */
        Route::resource('subusers','SubUserController');
        Route::get('subusers/delete/{id}','SubUserController@delete');
        Route::get('subusers/status/{id}','SubUserController@status');
    
        Route::get('importSubUsers','SubUserController@import');
        Route::post('importSubUsers','SubUserController@_import');
        /*
        |------------------------------
        |Manage Admin Account
        |------------------------------
        */
        Route::resource('adminUser','AdminUserController');
        Route::get('adminUser/delete/{id}','AdminUserController@delete');
        
        /*
        |------------------------------
        |Manage City
        |------------------------------
        */
        Route::resource('city','CityController');
        Route::get('city/delete/{id}','CityController@delete');
        Route::get('city/status/{id}','CityController@status');
        
        /*
        |------------------------------
        |Manage vehicles
        |------------------------------
        */
        Route::resource('vehicles','vehiclesController');
        Route::get('vehicles/delete/{id}','vehiclesController@delete');
        Route::get('vehicles/status/{id}','vehiclesController@status'); 

        /*
        |------------------------------
        |Manage Zones
        |------------------------------
        */
        Route::resource('zones','ZonesController');
        Route::get('zones/delete/{id}','ZonesController@delete');
        Route::get('zones/status/{id}','ZonesController@status');
        Route::get('zones/getCoords/{id}','ZonesController@getCoords')->name('getCoords');
        

        /*
        |------------------------------
        |Manage Offer
        |------------------------------
        */
        Route::resource('offer','OfferController');
        Route::get('offer/delete/{id}','OfferController@delete');
        Route::get('offer/status/{id}','OfferController@status');
        Route::post('offer/assign','OfferController@assign');
        
        /*
        |------------------------------
        |Delivery Staff
        |------------------------------
        */
        Route::resource('delivery','DeliveryController');
        Route::get('delivery/{id}/pay','DeliveryController@pay');
        Route::get('delivery/{id}/rate','DeliveryController@rate');
        Route::get('delivery/delete/{id}','DeliveryController@delete');
        Route::get('delivery/status/{id}','DeliveryController@status');
        Route::patch('delivery_pay/{id}','DeliveryController@delivery_pay');

        /*
        |-------------------------------
        |Send Push Notification
        |-------------------------------
        */
        Route::get('push','PushController@index');
        Route::post('push','PushController@send');
        
        /*
        |-------------------------------
        |Reporting
        |-------------------------------
        */
        Route::get('report','ReportController@index');
        Route::post('report','ReportController@report');
        Route::get('payment','ReportController@payment');
        Route::get('paymentReport','ReportController@paymentReport');
        Route::post('exportData','ReportController@exportData');
        
        /*
        |-------------------------------
        |Reporting Staff
        |-------------------------------
        */
        Route::get('report_staff','ReportController@index_staff');
        Route::post('report_staff','ReportController@report_staff');
        Route::get('data_Staff_id/{id}','ReportController@data_Staff_id');
        Route::post('exportData_staff','ReportController@exportData_staff');
    
        
        /*
        |-------------------------------
        |App Users
        |-------------------------------
        */
        Route::resource('appUser','AppUserController');
        Route::get('appUser/status/{id}', 'AppUserController@status');
        Route::get('appUser/trash/{id}', 'AppUserController@delete');
        Route::get('appUser/update/{id}','AppUserController@update');
        
        /*
        |-------------------------------
        |Servicios
        |-------------------------------
        */
        Route::resource('Services','ServiceController');
        Route::get('Services/delete/{id}','ServiceController@delete');
        Route::get('Services/status/{id}/{status}','ServiceController@status');

        /*
        |-----------------------------
        |Manage Branchs
        |-----------------------------
        */
        Route::resource('branchs','BranchsController');
        Route::get('branchs/delete/{id}','BranchsController@delete');
        Route::get('branchs/status/{id}','BranchsController@status');


        /*
        |-----------------------------
        |Manage Materials
        |-----------------------------
        */
        Route::resource('materials','MaterialsController');
        Route::get('materials/delete/{id}','MaterialsController@delete');
        Route::get('materials/status/{id}','MaterialsController@status');

        
        /*
        |-----------------------------
        |Manage Places
        |-----------------------------
        */
        Route::resource('places_loading','PlacesLoadingController');
        Route::get('places_loading/delete/{id}','PlacesLoadingController@delete');
        Route::get('places_loading/status/{id}','PlacesLoadingController@status');

        
    });
});

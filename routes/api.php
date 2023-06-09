<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(array('namespace' => 'App\Http\Controllers\Api'), function () {

    Route::get('welcome','ApiController@welcome');
    Route::get('getAdmin','ApiController@getAdmin');
    Route::get('city','ApiController@city');
    Route::get('GetNearbyCity','ApiController@GetNearbyCity');
    Route::get('homepage/{user_id}','ApiController@homepage');
    Route::get('getStore/{id}','ApiController@getStore');
    Route::get('getTypeDelivery/{id}','ApiController@getTypeDelivery');
    Route::get('search/{query}/{type}/{city}','ApiController@search');
    Route::get('SearchCat/{city}','ApiController@SearchCat');
    Route::post('addToCart','ApiController@addToCart');
    Route::get('cartCount/{cartNo}','ApiController@cartCount');
    Route::get('updateCart/{id}/{type}','ApiController@updateCart');
    Route::get('getCart/{cartNo}','ApiController@getCart');
    Route::get('getOffer/{cartNo}','ApiController@getOffer');
    Route::get('applyCoupen/{id}/{cartNo}','ApiController@applyCoupen');
    Route::post('signup','ApiController@signup');
    Route::post('sendOTP','ApiController@sendOTP');
    Route::post('chkUser','ApiController@chkUser');
    Route::post('sendOtpSms','ApiController@sendOtpSms');
    Route::post('SignPhone','ApiController@SignPhone');
    Route::post('login','ApiController@login');
    Route::post('Newlogin','ApiController@Newlogin');
    Route::post('loginfb','ApiController@loginfb');
    Route::post('forgot','ApiController@forgot');
    Route::post('verify','ApiController@verify');
    Route::post('updatePassword','ApiController@updatePassword');
    Route::get('getAddress/{id}','ApiController@getAddress');
    Route::get('getAllAdress/{id}','ApiController@getAllAdress');
    Route::post('addAddress','ApiController@addAddress');
    Route::get('removeAddress/{id}','ApiController@removeAddress');
    Route::post('searchLocation','ApiController@searchLocation');
    Route::post('order','ApiController@order');
    Route::get('userinfo/{id}','ApiController@userinfo');
    Route::post('updateInfo/{id}','ApiController@updateInfo');
    Route::get('cancelOrder/{id}/{uid}','ApiController@cancelOrder');
    Route::post('loginFb','ApiController@loginFb');
    Route::post('sendChat','ApiController@sendChat');
    Route::post('rate','ApiController@rate');
    Route::get('pages','ApiController@pages');
    Route::get('myOrder/{id}','ApiController@myOrder');
    Route::get('lang','ApiController@lang');
    Route::get('makeStripePayment', 'ApiController@stripe');
    Route::get('getStatus/{id}', 'ApiController@getStatus');
    Route::get('sendPushprueba/{id}', 'ApiController@sendPushprueba');
    Route::get('getPolylines','ApiController@getPolylines');
    Route::get('getChat/{id}','ApiController@getChat');
    Route::get('getEventsDetails/{id}','ApiController@getEventsDetails');
    Route::get('updateCity','ApiController@updateCity');
    Route::get('GetInfiniteScroll/{id}','ApiController@GetInfiniteScroll');
    Route::post('deleteOrders','ApiController@deleteOrders');
    Route::post('setLocationavailability','ApiController@setLocationavailability');
    Route::post('viewNearbyDrivers','ApiController@viewNearbyDrivers');
    Route::get('getAllStaffs','ApiController@getAllStaffs');

    // Mandaditos
    Route::post('OrderComm','ApiController@OrderComm');
    Route::post('ViewCostShipCommanded','ApiController@ViewCostShipCommanded');
    Route::get('chkEvents_comm/{id}','ApiController@chkEvents_comm');
    Route::post('chkEvents_staffs/','ApiController@chkEvents_staffs');
    Route::get('getNearbyEvents/{id}','ApiController@getNearbyEvents');
    Route::get('setStaffEvent/{event_id}/{dboy}','ApiController@setStaffEvent');
    Route::get('delStaffEvent/{event_id}','ApiController@delStaffEvent');
    Route::get('cancelComm_event/{id}','ApiController@cancelComm_event');
    Route::post('rateComm_event','ApiController@rateComm_event');
    Route::get('chk_comm/{id}','ApiController@chk_comm');
    Route::get('demoCronNodejs','ApiController@demoCronNodejs');

    // Servicios
    Route::post('AddService','ApiController@AddService');
    Route::get('chkServices/{id}','ApiController@chkServices');
    Route::post('ChangeService','ApiController@ChangeService');

    Route::get('getBranchs/{id}','ApiController@getBranchs');
    Route::get('getSubClients/{id}','ApiController@getSubClients');

    include("dboy.php");

});

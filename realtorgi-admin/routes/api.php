<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});


Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {
    Route::get('admin/profile', 'ProfileController@profile');
    Route::put('admin/profile', 'ProfileController@updateProfile');
    Route::post('admin/change-password', 'ProfileController@changePassword');
    Route::get('admin/user/list', 'UserController@list');
    Route::get('admin/user/all/list', 'UserController@allList');
    Route::get('admin/tag/list', 'TagController@list');
    Route::get('admin/auction/list', 'AuctionController@list');
    Route::get('admin/category/list', 'CategoryController@list');
    Route::post('admin/product/upload', 'ProductController@upload');
    Route::get('admin/lot/list', 'LotController@list');
    Route::get('admin/city/list', 'CountryController@list');
    /* Images and Files */
    /* Lot */
    Route::post('admin/lot/uploadImages/{id}', 'LotController@upload');
    Route::post('admin/lot/destroyImages/{id}', 'LotController@destroyImage');
    Route::post('admin/lot/destroyFiles/{id}', 'LotController@destroyFiles');
    /* User */
    Route::post('admin/gallery/uploadFiles/{id}', 'GalleryController@upload');
    Route::post('admin/gallery/destroyImages/{id}', 'GalleryController@destroyImage');
    Route::post('gallery/destroyFiles/{id}', 'GalleryController@destroyFiles');
    /* Lot Items */
    Route::post('admin/item/uploadImages/{id}', 'LotItemsGalleryController@upload');
    Route::post('admin/item/destroyImages/{id}', 'LotItemsGalleryController@destroyImage');
    /* Auction */
    Route::post('admin/auction/uploadImages/{id}', 'AuctionController@upload');
    Route::post('admin/auction/destroyImages/{id}', 'AuctionController@destroyImage');
    Route::post('admin/auction/destroyFiles/{id}', 'AuctionController@destroyFiles');

    Route::post('admin/user/login', 'UserController@login');
    Route::post('admin/categoryFilter', 'LotController@categoryFilter');
    Route::post('admin/user/lotList', 'UserController@lotList');
    Route::post('admin/user/betList', 'UserController@betList');
    Route::post('admin/user/login', 'UserController@login');
    Route::get('admin/bet/list/{id}', 'AuctionBetsController@list');
    Route::get('admin/user/notifications/{id}', 'NotificationsController@userList');
    Route::get('admin/user/auctions/{id}', 'AuctionController@userList');
    Route::get('admin/user/lots/{id}', 'LotController@userList');
    Route::get('admin/lot/confirms/{id}', 'LotController@auctionConfirms');
    Route::get('admin/user/confirms/{id}', 'UserController@auctionConfirms');
    Route::post('admin/notifications/update_all', 'NotificationsController@updateAll');
    Route::get('admin/auction/lots/{id}', 'AuctionController@lots');
    Route::post('admin/registration/confirm/{id}', 'UserController@confirm_user');
    Route::get('admin/user_applications/{id}', 'AuctionConfirmController@allApplications');
    Route::post('admin/user/sells', 'LotController@userSells');
    Route::get('admin/user/sells/{id}', 'LotController@userSellsInfo');
    /* Архив */
    Route::get('admin/archive/download/{id}', 'ExportDataController@download');
    Route::post('admin/auctionConfirm/notification/{id}', 'AuctionConfirmController@notificateComment');
    Route::post('admin/notifications/readAll', 'NotificationsController@readAllNotifications');

    Route::apiResources([
        'admin/user' => 'UserController',
        'admin/product' => 'ProductController',
        'admin/auction' => 'AuctionController',
        'admin/auctionConfirm' => 'AuctionConfirmController',
        'admin/bet' => 'AuctionBetsController',
        'admin/lot' => 'LotController',
        'admin/category' => 'CategoryController',
        'admin/country' => 'CountryController',
        'admin/notification' => 'NotificationsController',
        'admin/auction_bet' => 'AuctionBetsController',
        'admin/lot_item' => 'LotItemsController',
        'admin/change_user' => 'UserNotificationsController',
        'admin/feedback' => 'FeedbackController',
        'admin/message' => 'ContactController',
        'admin/archive' => 'ExportDataController',
        //'front/auction_front' => 'AuctionController'
    ]);
});

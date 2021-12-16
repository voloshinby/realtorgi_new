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


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     Log::debug('User:' . serialize($request->user()));
//     return $request->user();
// });


Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {
    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');
    Route::get('user/list', 'UserController@list');
    Route::get('user/all/list', 'UserController@allList');
    Route::get('tag/list', 'TagController@list');
    Route::get('auction/list', 'AuctionController@list');
    Route::get('category/list', 'CategoryController@list');
    Route::post('product/upload', 'ProductController@upload');
    Route::get('lot/list', 'LotController@list');
    Route::get('city/list', 'CountryController@list');
    /* Images and Files */
    /* Lot */
    Route::post('lot/uploadImages/{id}', 'LotController@upload');
    Route::post('lot/destroyImages/{id}', 'LotController@destroyImage');
    Route::post('lot/destroyFiles/{id}', 'LotController@destroyFiles');
    /* User */
    Route::post('gallery/uploadFiles/{id}', 'GalleryController@upload');
    Route::post('gallery/destroyImages/{id}', 'GalleryController@destroyImage');
    Route::post('gallery/destroyFiles/{id}', 'GalleryController@destroyFiles');
    /* Lot Items */
    Route::post('item/uploadImages/{id}', 'LotItemsGalleryController@upload');
    Route::post('item/destroyImages/{id}', 'LotItemsGalleryController@destroyImage');
    /* Auction */
    Route::post('auction/uploadImages/{id}', 'AuctionController@upload');
    Route::post('auction/destroyImages/{id}', 'AuctionController@destroyImage');
    Route::post('auction/destroyFiles/{id}', 'AuctionController@destroyFiles');

    Route::post('user/login', 'UserController@login');
    Route::post('categoryFilter', 'LotController@categoryFilter');
    Route::post('user/lotList', 'UserController@lotList');
    Route::post('user/betList', 'UserController@betList');
    Route::get('bet/list/{id}', 'AuctionBetsController@list');
    Route::get('user/notifications/{id}', 'NotificationsController@userList');
    Route::get('user/auctions/{id}', 'AuctionController@userList');
    Route::get('user/lots/{id}', 'LotController@userList');
    Route::get('lot/confirms/{id}', 'LotController@auctionConfirms');
    Route::get('user/confirms/{id}', 'UserController@auctionConfirms');
    Route::post('notifications/update_all', 'NotificationsController@updateAll');
    Route::get('auction/lots/{id}', 'AuctionController@lots');
    Route::post('registration/confirm/{id}', 'UserController@confirm_user');
    Route::get('user_applications/{id}', 'AuctionConfirmController@allApplications');
    Route::post('auctionConfirm/notification/{id}', 'AuctionConfirmController@notificateComment');
    /* Архив */
    Route::get('archive/download/{id}', 'ExportDataController@download');

    Route::apiResources([
        'user' => 'UserController',
        'product' => 'ProductController',
        'auction' => 'AuctionController',
        'auctionConfirm' => 'AuctionConfirmController',
        'bet' => 'AuctionBetsController',
        'lot' => 'LotController',
        'category' => 'CategoryController',
        'country' => 'CountryController',
        'notification' => 'NotificationsController',
        'auction_bet' => 'AuctionBetsController',
        'lot_item' => 'LotItemsController',
        'change_user' => 'UserNotificationsController',
        'feedback' => 'FeedbackController',
        'message' => 'ContactController',
        'archive' => 'ExportDataController',
        //'front/auction_front' => 'AuctionController'
    ]);
});

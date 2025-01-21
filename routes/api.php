<?php

use App\Http\Controllers\Api\AboutLandController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AdvertiseLandController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerLandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServicesLandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const pag=10;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(['middleware'=>['check.pass','check.lang']],function (){

    /////////////////    Auth ////////////////
    Route::group(['prefix'=>'v1/auth'],function (){
        Route::post('register', [AuthController::class, 'register']);
        Route::post('verify-email', [AuthController::class, 'verifyEmail']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('resent-otp', [AuthController::class, 'resentOtp']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('forget-password', [AuthController::class, 'forgetPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::get('singleUser/{user_id}', [AuthController::class, 'singleUser']);
        Route::get('showAll', [AuthController::class, 'showAll']);
        Route::post('updateProfile', [AuthController::class, 'updateProfile'])->middleware('auth:api');

    });




    Route::group(['prefix'=>'v1/admin'],function (){

        //////////////////// category ///////////////////
        Route::group(['prefix'=>'category'],function (){
            Route::post('save',[CategoryController::class,'save']);
            Route::get('singleCategory/{category_id}',[CategoryController::class,'singleCategory']);
            Route::get('allCategories',[CategoryController::class,'allCategories']);
            Route::get('showAll',[CategoryController::class,'showAll']);
            Route::post('update/{category_id}',[CategoryController::class,'update']);
            Route::get('delete/{category_id}',[CategoryController::class,'delete']);
        });


        //////////////////// products ///////////
        Route::group(['prefix'=>'product'],function (){
            Route::post('save',[ProductController::class,'save']);
            Route::get('singleProduct/{product_id}',[ProductController::class,'singleProduct']);
            Route::get('allProducts',[ProductController::class,'allProducts']);
            Route::get('showAll',[ProductController::class,'showAll']);
            Route::post('update/{product_id}',[ProductController::class,'update']);
            Route::get('delete/{product_id}',[ProductController::class,'delete']);
            Route::get('deleteAll',[ProductController::class,'deleteAll']);
            Route::get('relatedProducts/{product_id}',[ProductController::class,'relatedProducts']);
            Route::get('searchProduct',[ProductController::class,'searchProduct']);
        });

        ///////////////////   Services  //////////////

        Route::group(['prefix'=>'services'],function (){
            Route::post('save',[ServicesLandController::class,'save']);
            Route::post('update/{id}',[ServicesLandController::class,'update']);
            Route::get('show',[ServicesLandController::class,'show']);
            Route::get('showAll',[ServicesLandController::class,'showAll']);
            Route::get('delete/{id}',[ServicesLandController::class,'delete']);

        });


        ////////////////   about /////////////

        Route::group(['prefix'=>'about'],function (){
            Route::post('save',[AboutLandController::class,'save']);
            Route::post('update/{id}',[AboutLandController::class,'update']);
            Route::get('show',[AboutLandController::class,'show']);
            Route::get('showAll',[AboutLandController::class,'showAll']);
            Route::get('delete/{id}',[AboutLandController::class,'delete']);

        });

        /////////////  Banner     ///////////////
        Route::group(['prefix'=>'banner'],function (){
            Route::post('save',[BannerLandController::class,'save']);
            Route::post('update/{id}',[BannerLandController::class,'update']);
            Route::post('updateStatus/{id}',[BannerLandController::class,'updateStatus']);
            Route::get('show',[BannerLandController::class,'show']);
            Route::get('showAll',[BannerLandController::class,'showAll']);
            Route::get('showLand',[BannerLandController::class,'showLand']);
            Route::get('delete/{id}',[BannerLandController::class,'delete']);

        });

            /////////// Advertise   ///////////////
        Route::group(['prefix'=>'advertise'],function (){
            Route::post('save',[AdvertiseLandController::class,'save']);
            Route::post('update/{id}',[AdvertiseLandController::class,'update']);
            Route::post('updateStatus/{id}',[AdvertiseLandController::class,'updateStatus']);
            Route::get('show',[AdvertiseLandController::class,'show']);
            Route::get('showAll',[AdvertiseLandController::class,'showAll']);
            Route::get('showLand',[AdvertiseLandController::class,'showLand']);
            Route::get('delete/{id}',[AdvertiseLandController::class,'delete']);

        });

        //////////////////// admin    ///////
        Route::post('save',[AdminController::class,'save']);
        Route::post('login',[AdminController::class,'login']);
        Route::get('logout',[AdminController::class,'logout']);
        Route::post('updateProfile',[AdminController::class,'updateProfile'])->middleware('auth:admin');
        Route::post('updatePassword',[AdminController::class,'updatePassword'])->middleware('auth:admin');
        Route::get('singleUser/{id}',[AdminController::class,'singleUser']);
        Route::get('showAll',[AdminController::class,'showAll']);
        Route::get('showByType/{type}',[AdminController::class,'showByType']);





    });

    Route::group(['prefix'=>'v1'],function (){

        /////////////////  products //////////////
//        Route::get('product/allProducts',[ProductController::class,'allProducts']);

    });

});



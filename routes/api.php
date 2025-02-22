<?php

use App\Http\Controllers\Api\AboutLandController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AdvertiseLandController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerLandController;
use App\Http\Controllers\Api\CachBackController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommissionController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\FeatureController;
use App\Http\Controllers\Api\imagesbannerController;
use App\Http\Controllers\Api\OverAllInfoController;
use App\Http\Controllers\Api\PaymobController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\ServicesLandController;
use App\Http\Controllers\Api\socailmediaController;
use App\Http\Controllers\Api\SubscribeController;
use App\Http\Controllers\Api\WhyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//const pag=10;
if (!defined('pag')) {
    define('pag', 10);
}
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
        Route::post('changePassword', [AuthController::class, 'changePassword'])->middleware('auth:api');
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::get('singleUser', [AuthController::class, 'singleUser'])->middleware('auth:api');
        Route::get('showAll', [AuthController::class, 'showAll']);
        Route::post('updateProfile', [AuthController::class, 'updateProfile'])->middleware('auth:api');
        Route::get('searchUsers', [AuthController::class, 'searchUsers']);

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
            Route::get('searchCategories',[CategoryController::class,'searchCategories']);
        });


        //////////////////// products ///////////
        Route::group(['prefix'=>'product'],function (){
            Route::post('save',[ProductController::class,'save']);
            Route::get('singleProduct/{product_id}',[ProductController::class,'singleProductWithRelated']);
            Route::get('allProducts',[ProductController::class,'allProducts']);
            Route::get('showAll',[ProductController::class,'showAll']);
            Route::get('showOutOfStock',[ProductController::class,'showOutOfStock']);
            Route::post('update/{product_id}',[ProductController::class,'update']);
            Route::get('delete/{product_id}',[ProductController::class,'delete']);
            Route::get('deleteAll',[ProductController::class,'deleteAll']);
            Route::get('relatedProducts/{product_id}',[ProductController::class,'relatedProducts']);
            Route::get('searchProducts',[ProductController::class,'searchProducts']);
            Route::get('getAllColors',[ProductController::class,'getAllColors']);
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
        Route::get('showSales',[AdminController::class,'showSales']);
        Route::get('showByType/{type}',[AdminController::class,'showByType']);
        Route::get('delete/{id}',[AdminController::class,'delete']);
        Route::get('getDashboardStats',[AdminController::class,'getDashboardStats']);
        Route::get('searchAdmins',[AdminController::class,'searchAdmins']);

        //////////////////////   reviews /////
        Route::group(['prefix'=>'review'],function (){
            Route::post('store/{product_id}', [ReviewsController::class, 'store'])->middleware('auth:api');
            Route::get('show/{product_id}', [ReviewsController::class, 'show']);
            Route::get('showAll', [ReviewsController::class, 'showAll']);

        });

        ////////////////  cart   ///////
        Route::group(['prefix'=>'cart','middleware'=>'auth:api'],function (){

            Route::post('addToCart/{product_id}', [CartController::class, 'addToCart']);

            Route::get('viewCart', [CartController::class, 'viewCart']);

            Route::post('updateQuantity', [CartController::class, 'updateQuantity']);

            Route::get('removeFromCart/{id}', [CartController::class, 'removeFromCart']);

        });

        //////////////////////////  paymob  ///////
        Route::group(['prefix'=>'payment'],function (){
            Route::post('/generateToken', [PaymobController::class, 'generateToken']);
            Route::post('/sendPayment', [PaymobController::class, 'sendPayment']);
            Route::match(['GET','POST'],'/callback', [PaymobController::class, 'callBack'])->withoutMiddleware(['check.pass','check.lang']);
            Route::get('/showAll', [PaymobController::class, 'showAll']);
            Route::get('/showByCode/{code}', [PaymobController::class, 'showByCode']);
            Route::post('/cashOnDelivery', [PaymobController::class, 'cashOnDelivery']);
            Route::get('/searchOrders', [PaymobController::class, 'searchOrders']);


        });

        //////////////////  cash Back /////////////
        Route::group(['prefix'=>'cashBack'],function (){
            Route::get('/', [CachBackController::class, 'index']); // عرض جميع السجلات
            Route::post('/store', [CachBackController::class, 'store']); // إنشاء سجل جديد
            Route::get('/edit/{id}', [CachBackController::class, 'edit']); // عرض سجل واحد
            Route::post('/update/{id}', [CachBackController::class, 'update']); // تحديث سجل
            Route::get('/delete/{id}', [CachBackController::class, 'destroy']); // حذف سجل
            Route::get('/showCashbackToUser/{code}/{user_id}', [CachBackController::class, 'showCashbackToUser']); // حذف سجل
        });


        //////////////////////////  contact  //////////
        Route::group(['prefix'=>'contact'],function (){
            Route::get('/', [ContactController::class, 'index']); // عرض جميع السجلات
            Route::post('/save', [ContactController::class, 'save']); // إنشاء سجل جديد
            Route::get('/searchContacts', [ContactController::class, 'searchContacts']);

        });
        ///////////////////////// subscribe //////////
        Route::group(['prefix'=>'subscribe'],function (){
            Route::get('/show', [SubscribeController::class, 'index']); // عرض جميع السجلات
            Route::post('/save', [SubscribeController::class, 'save']); // إنشاء سجل جديد

        });

        /////////////////   Why section ////////

        Route::group(['prefix'=>'why'],function (){
            Route::post('save',[WhyController::class,'save']);
            Route::post('update/{id}',[WhyController::class,'update']);
            Route::get('/show',[WhyController::class,'index']);
            Route::get('/showAll',[WhyController::class,'showAll']);
            Route::get('delete/{id}',[WhyController::class,'delete']);

        });


        /////////////////   Feature section ////////

        Route::group(['prefix'=>'feature'],function (){
            Route::post('save',[FeatureController::class,'save']);
            Route::post('update/{id}',[FeatureController::class,'update']);
            Route::get('/show',[FeatureController::class,'index']);
            Route::get('/showAll',[FeatureController::class,'showAll']);
            Route::get('delete/{id}',[FeatureController::class,'delete']);

        });


        //////////////////  ContactUs //////////
        Route::group(['prefix'=>'contactUs'],function (){
            Route::post('save',[ContactUsController::class,'save']);
            Route::post('update/{id}',[ContactUsController::class,'update']);
            Route::get('/show',[ContactUsController::class,'show']);
            Route::get('/showAll',[ContactUsController::class,'showAll']);
            Route::get('delete/{id}',[ContactUsController::class,'delete']);

        });


        ///////////////   over all info  ////////
        Route::group(['prefix'=>'info'],function (){
            Route::post('save',[OverAllInfoController::class,'save']);
            Route::post('update/{id}',[OverAllInfoController::class,'update']);
            Route::get('/show',[OverAllInfoController::class,'show']);
            Route::get('/showAll',[OverAllInfoController::class,'showAll']);

        });

        ////////////////  Images Banner  ///

        Route::group(['prefix'=>'ImagesBanner'],function (){
            Route::post('save',[imagesbannerController::class,'save']);
            Route::post('update/{id}',[imagesbannerController::class,'update']);
            Route::get('/show',[imagesbannerController::class,'show']);

        });

        ////////////////  Social medial  ///

        Route::group(['prefix'=>'social'],function (){
            Route::post('save',[socailmediaController::class,'save']);
            Route::post('update/{id}',[socailmediaController::class,'update']);
            Route::get('/show',[socailmediaController::class,'show']);

        });

        ////////////////// commission  ////////////
        Route::prefix('commissions')->group(function() {
            // حساب العمولة
            Route::get('/calculate/{adminId}', [CommissionController::class, 'calculateCommission']);
            // طلب سحب العمولة
            Route::post('/withdraw/{adminId}', [CommissionController::class, 'requestWithdrawal']);
            // تحديث حالة العمولة
            Route::post('/update/{commissionId}', [CommissionController::class, 'updateCommissionStatus']);
            // جلب العمولات الخاصة بالبائع
            Route::get('/sales/{adminId}', [CommissionController::class, 'getCommissionsByAdmin']);
            // حساب العمولة الشهرية
            Route::get('/monthly/{adminId}/{month}/{year}', [CommissionController::class, 'calculateMonthlyCommission']);
            // التحقق من العمولات المعلقة في فترة زمنية
            Route::get('/pending/{adminId}/{fromDate}/{toDate}', [CommissionController::class, 'checkPendingCommissions']);
            // البحث في العمولات
            Route::post('/search', [CommissionController::class, 'searchCommissions']);
            // عرض العمولات
            Route::get('/show', [CommissionController::class, 'show']);
            Route::get('/showByCode/{code}', [CommissionController::class, 'showByCode']);
        });



    });


});



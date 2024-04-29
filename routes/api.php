<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ConstributionController;
use App\Http\Controllers\WorkTableController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\EducationlMaterialController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SurveysController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [UserController::class, 'login'])->middleware('throttle:5,1');
    Route::post('register', [UserController::class, 'saveUser']);
});

Route::get('soap/users', [UserController::class, 'getUserSoap']);

Route::put('user/recover/password/rut/{rut}', [UserController::class, 'recoverPassword']);
Route::get('terms', [HomeController::class, 'termn']);
Route::put('term/change/flag', [HomeController::class, 'changeFlag']);

Route::put('user/recovery/password/id', [UserController::class, 'saveRecoveryPassword']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'service'], function () {
        Route::get('current/settlement', [ServiceController::class, 'currentSettlement']);
        Route::get('terms', [ServiceController::class, 'isAcceptanceTermsText']);
        Route::post('acceptance/terms', [ServiceController::class, 'isAcceptanceTerms']);
    });

    Route::get('suspend/user', [UserController::class, 'suspendUser']);

    Route::get('work/table/tutorial', [WorkTableController::class, 'tutorial']);
    Route::get('work/table/faq', [WorkTableController::class, 'faq']);
    Route::get('work/table/contact', [WorkTableController::class, 'contact']);

    Route::get('onboarding', [UserController::class, 'onboarding']); //api de induccion
    Route::get('send/pdf', [ServiceController::class, 'sendPdf']);

    Route::get('latest/settlements', [UserController::class, 'latestSettlements']);
    Route::get('logout', [UserController::class, 'logoutApi']);
    Route::get('certificate', [CertificateController::class, 'certificate']);
    Route::get('certificados/renta-1887', [CertificateController::class, 'certificateRent']);

    Route::put('user/home/term', [HomeController::class, 'updateUserTermCondition']);
    Route::get('user/home/term', [HomeController::class, 'isCheckTermn']);

    Route::put('user/service/term', [ServiceController::class, 'updateUserTermCondition']);
    Route::put('user/notification', [UserController::class, 'updateUserNotification']);

    Route::patch('user/read/notification', [NotifyController::class, 'readNotification']);

    Route::get('service/settlements', [ServiceController::class, 'settlements']);

    Route::get('user/notification', [NotifyController::class, 'getNotification']);

    Route::get('user/service/term', [ServiceController::class, 'isCheckTermn']);

    Route::patch('unlink/user/{rut}', [UserController::class, 'unlinkUser']);
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('me', [UserController::class, 'profile']);

    Route::put('/user/register/full', [HomeController::class, 'registerFull']);

    //Constribution
    Route::get('covid/ammounts', [ConstributionController::class, 'index']);
    Route::post('covid/user/ammounts', [ConstributionController::class, 'voluntaryContribution']);
    // Text controller

    //Route::get('region', 'RegionController@index');
    //Route::get('commune/{id}', 'CommuneController@communeByRegion');
    //Route::post('pay', 'PayController@savePurchase');
    //Route::get('pay', 'PayController@get');
    //Route::delete('pay/{id}', 'PayController@delete');
    //Route::get('order/history', 'PayController@orderHistory');
    Route::post('user/complete', [UserController::class, 'completeData']);

});

Route::get('materials/categories', [CategoryController::class, 'index']);

Route::get('educational/materials/categories/{slug}/items', [CategoryController::class, 'categoriesItem']);

Route::get('/text', [TextController::class, 'handle']);

Route::post('send', [NotifyController::class, 'send']);
Route::get('releases', [ReleaseController::class, 'index']);
Route::get('educational/materials/activity', [EducationlMaterialController::class, 'indexActivity']);
Route::get('educational/materials/{slug}', [EducationlMaterialController::class, 'showActivity']);

Route::get('educational/materials/workshop', [EducationlMaterialController::class, 'indexWorkshop']);
Route::get('educational/materials/workshop/{slug}', [EducationlMaterialController::class, 'showWorkshop']);

Route::get('educational/materials/video',  [EducationlMaterialController::class, 'video']);
Route::get('job/offers', [ReleaseController::class, 'jobOffers']);
Route::get('posts', [PostController::class, 'index']);
Route::get('post/{slug}', [PostController::class, 'show']);

Route::get('benefits', [BenefitController::class, 'index']);
Route::get('benefit/{slug}', [BenefitController::class, 'show']);

Route::get('surveys', [SurveysController::class, 'index']);
Route::get('survey/{slug}', [SurveysController::class, 'show']);
Route::get('release/{release}', [ReleaseController::class, 'show']);
Route::get('home', [HomeController::class, 'index']);
Route::get('benefits', [BenefitController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

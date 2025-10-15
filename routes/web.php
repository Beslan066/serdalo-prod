<?php


use Illuminate\Support\Facades\Route;
use Spatie\Feed\Feed;
use Spatie\Feed\FeedItem;


use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\LanguageController as FrontendLanguageController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\PaymentController as FrontendPaymentController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\MaterialController as FrontendMaterialController;
use App\Http\Controllers\Frontend\AuthorController as FrontendAuthorController;
use App\Http\Controllers\Frontend\ArchiveController as FrontendArchiveController;
use App\Http\Controllers\Frontend\SearchController as FrontendSearchController;
use App\Http\Controllers\Frontend\DictionaryController as FrontendDictionaryController;
use App\Http\Controllers\Frontend\PersonalDataController as FrontendPersonalDataController;
use App\Http\Controllers\Frontend\VersionController as FrontendVersionController;
use App\Http\Controllers\Frontend\SubscribeController as FrontendSubscribeController;
use App\Http\Controllers\Frontend\VideoStudioController as FrontendVideoStudioController;
use App\Http\Controllers\Frontend\PhotoReportageController as PhotoReportageController;
use App\Http\Controllers\RssController as RssController;
use App\Http\Controllers\Frontend\VideoReportageController as VideoReportageController;
use App\Http\Controllers\Other\ExportController;
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


Route::feeds();
Route::get('/testart', function() {
    Artisan::call('cache:clear');
});
Route::get('/testview', function() {
    Artisan::call('view:clear');
});

Route::get('/routes', function() {
    Artisan::call('route:clear');
});

Route::get('yandex-news.xml', [FrontendHomeController::class, 'generateYandexNews'])->name('yandex_news');
Route::get('/rss.xml', [RssController::class, 'generateRss']);

Route::get('generate-yml', [FrontendHomeController::class, 'generateYml']);

Route::get('/export/posts-with-translations', [ExportController::class, 'exportPostsWithTranslations'])
    ->name('export.posts.translations');


Route::get('/change-language/{language}', [FrontendLanguageController::class, 'change'])->name('change-language');
Route::get('/change-version/{version}', [FrontendVersionController::class, 'change'])->name('change-version');
Route::get('/show-version', function() { dump(session('frontend_version')); })->name('show-version');
Route::group(['prefix' => App\Http\Middleware\Language::getLanguage()], function() {


    Route::group(['middleware' => 'auth'], function() {
        Route::get('/payment', [FrontendPaymentController::class, 'paymentPage'])->name('payment-page');
    });
    Route::get('/museum', [FrontendHomeController::class, 'museum'])->name('museum');


    Route::group(['prefix' => 'ajax'], function() {
        Route::post('/subscribe', [FrontendSubscribeController::class, 'store'])->name('subscribe-store');
    });

    Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
    Route::get('/litsalon', [FrontendHomeController::class, 'litsalon'])->name('litsalon');
    Route::get('/game', [\App\Http\Controllers\Frontend\GameController::class, 'index'])->name('game');
    Route::get('/games', [\App\Http\Controllers\Frontend\GameController::class, 'allGames'])->name('games');
    Route::get('/dictionary', [FrontendDictionaryController::class, 'index'])->name('dictionary');
    Route::get('/materials/{category_id?}', [FrontendMaterialController::class, 'index'])->name('materials-index');
    Route::get('/material/{slug}', [FrontendMaterialController::class, 'single'])->name('material-single');
    Route::get('/authors/{author_id}', [FrontendAuthorController::class, 'index'])->name('authors-index');
    Route::get('/authors/news/{author_id}', [FrontendAuthorController::class, 'news'])->name('author-news');
    Route::get('/authors/posts/{author_id}', [FrontendAuthorController::class, 'posts'])->name('authors-posts');
    Route::get('/archive', [FrontendArchiveController::class, 'index'])->name('archive-index');
    Route::get('/press-releases', [FrontendMaterialController::class, 'getPressReleases'])->name('releases-index');
    Route::get('/journalism', [FrontendMaterialController::class, 'getJournalism'])->name('journalism-index');
    Route::get('/journalism/{slug}', [FrontendMaterialController::class, 'getJournalismSingle'])->name('journalism-single');
    Route::get('/soglasie-na-obrabotku-personalnykh-dannykh', [FrontendPersonalDataController::class, 'index'])->name('personal');
    Route::get('/pravila-ispolzovaniya-materialov', [FrontendPersonalDataController::class, 'rules'])->name('material-rules');


    Route::get('/about', [FrontendPersonalDataController::class, 'about'])->name('about');


    Route::get('/documents', [FrontendHomeController::class, 'getDocuments'])->name('documents-index');
    Route::get('/documents/{document}', [FrontendHomeController::class, 'getDocumentSingle'])->name('documentSingle');
    Route::get('videostudio', 'App\Http\Controllers\Frontend\VideoStudioController@index')->name('videostudio');
    Route::get('/lit-salon', 'App\Http\Controllers\Frontend\LitSalonController@index')->name('litSalon');
    Route::get('/lit-salon/{article}', 'App\Http\Controllers\Frontend\LitSalonController@single')->name('litSingle');
    Route::get('/salon/{work}', 'App\Http\Controllers\Frontend\LitSalonController@singleWorks')->name('workSingle');
    Route::get('/photo-reportages', 'App\Http\Controllers\Frontend\PhotoReportageController@index')->name('reportages');
    Route::get('/photo-reportages/{reportage}', [PhotoReportageController::class, 'single'])->name('reportage-single');
    Route::get('/video-reportages', [VideoReportageController::class, 'index'])->name('videoreportage');
    Route::get('/tags/{tag}', [FrontendPostController::class, 'getPostsByTag'])->name('posts.by.tag');
    Route::get('/search/{search?}', 'App\Http\Controllers\Frontend\SearchController@index')->name('search-index');
    Route::get('/material-tags/{category_id?}', [FrontendMaterialController::class, 'getMaterialByTag'])->name('material.by.category');
    Route::get('/login', [FrontendAuthController::class, 'loginPage'])->name('login-page');
    Route::get('/register', [FrontendAuthController::class, 'registerPage'])->name('register-page');
    Route::post('/login', [FrontendAuthController::class, 'login'])->name('login-frontend');
    Route::post('/register', [FrontendAuthController::class, 'register'])->name('register-frontend');
    Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('logout-frontend');
    Route::get('/news/{tag_id?}', [FrontendPostController::class, 'index'])->name('posts-index');
    Route::get('/{slug}', [FrontendPostController::class, 'single'])->name('post-single');
    Route::get('/news-sitemap.xml', [FrontendPostController::class, 'newsSitemap'])->name('news-sitemap');

});



//Route::match(['get', 'post'], '/payment-success', [FrontendPaymentController::class, 'success'])->name('payment-success-frontend');

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/export', [App\Http\Controllers\ExportController::class, 'index'])->name('export');







Route::get('{any?}', function () {
    return view('vue');
})
    ->where('any', 'admin|admin/.*|sanctum|sanctum/.*')
    ->name('index');

Route::get('/admin-empty', function () {
    return view('vue');
});
Route::get('admin-login', function () {
    return view('vue');
})->name('login');
Route::prefix('admin')->group(function() {
    Route::get('{any?}', function () {
        return view('vue');
    });
});
Route::prefix('sanctum')->group(function() {
    Route::get('{any?}', function () {
        return view('vue');
    });
});

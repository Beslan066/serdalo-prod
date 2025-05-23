<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\PaymentController as FrontendPaymentController;

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\Admin\PostsController as AdminPostsController;
use App\Http\Controllers\Api\v1\Admin\PostsTranslationsController as AdminPostsTranslationsController;
use App\Http\Controllers\Api\v1\Admin\TagsController as AdminTagsController;
use App\Http\Controllers\Api\v1\Admin\FilesController as AdminFilesController;
use App\Http\Controllers\Api\v1\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Api\v1\Admin\MaterialsController as AdminMaterialsController;
use App\Http\Controllers\Api\v1\Admin\MaterialsTranslationsController as AdminMaterialsTranslationsController;
use App\Http\Controllers\Api\v1\Admin\CategoriesController as AdminCategoriesController;
use App\Http\Controllers\Api\v1\Admin\AuthorsController as AdminAuthorsController;
use App\Http\Controllers\Api\v1\Admin\AuthorsTranslationsController as AdminAuthorsTranslationsController;
use App\Http\Controllers\Api\v1\Admin\ExpertController as AdminExpertController;

use App\Http\Controllers\Api\v1\Admin\NewspapersController as AdminNewspapersController;
use App\Http\Controllers\Api\v1\Admin\StatisticsController as AdminStatisticsController;
use App\Http\Controllers\Api\v1\Admin\VideoArticlesController as AdminVideoArticlesController;
use App\Http\Controllers\Api\v1\Admin\VideoArticlesTranslationsController as AdminVideoArticlesTranslationsController;
use App\Http\Controllers\Api\v1\Admin\DocumentsController as AdminDocumentsController;
use App\Http\Controllers\Api\v1\Admin\DocumentsTranslationsController as AdminDocumentsTranslationsController;
use App\Http\Controllers\Api\v1\Admin\LitsalonArticlesController as AdminLitsalonArticlesController;
use App\Http\Controllers\Api\v1\Admin\LitsalonArticlesTranslationsController as AdminLitsalonArticlesTranslationsController;
use App\Http\Controllers\Api\v1\Admin\PhotoReportageTranslateController as AdminPhotoReportageTranslationsController;
use App\Http\Controllers\Api\v1\Admin\PhotoReportageController as PhotoReportageController;
use App\Http\Controllers\Api\v1\Admin\MaterialsIngController as AdminMaterialsIngController;

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

Route::match(['get', 'post'], '/payment-result', [FrontendPaymentController::class, 'paymentResult'])->name('payment-frontend');

Route::prefix('sanctum')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'user']);
});
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function() {

    Route::resource('posts', AdminPostsController::class, ['as' => 'admin']);
    Route::prefix('posts')->group(function() {
        Route::get('/{post_id}/posts_translations/create', [AdminPostsTranslationsController::class, 'create'], ['as' => 'admin'])->name('posts_translations.create');
        Route::post('/{post_id}/posts_translations', [AdminPostsTranslationsController::class, 'store'], ['as' => 'admin'])->name('posts_translations.store');
        Route::get('/posts_translations/{id}/edit', [AdminPostsTranslationsController::class, 'edit'], ['as' => 'admin'])->name('posts_translations.edit');
        Route::put('/posts_translations/{id}', [AdminPostsTranslationsController::class, 'update'], ['as' => 'admin'])->name('posts_translations.update');
    });
    Route::resource('tags', AdminTagsController::class, ['as' => 'admin']);

    Route::resource('materials', AdminMaterialsController::class, ['as' => 'admin']);
    Route::prefix('materials')->group(function() {
        Route::get('/{material_id}/materials_translations/create', [AdminMaterialsTranslationsController::class, 'create'], ['as' => 'admin'])->name('materials_translations.create');
        Route::post('/{material_id}/materials_translations', [AdminMaterialsTranslationsController::class, 'store'], ['as' => 'admin'])->name('materials_translations.store');
        Route::get('/materials_translations/{id}/edit', [AdminMaterialsTranslationsController::class, 'edit'], ['as' => 'admin'])->name('materials_translations.edit');
        Route::put('/materials_translations/{id}', [AdminMaterialsTranslationsController::class, 'update'], ['as' => 'admin'])->name('materials_translations.update');
    });

    Route::resource('materials-inh', AdminMaterialsIngController::class, ['as' => 'admin']);

    Route::resource('photoreportage', PhotoReportageController::class, ['as' => 'admin']);
    Route::prefix('photoreportage')->group(function() {
        Route::get('{reportage_id}/photo_reportage_translations/create', [AdminPhotoReportageTranslationsController::class, 'create'], ['as' => 'admin'])->name('photo_reportage_translations.create');
        Route::post('/{reportage_id}/photo_reportage_translations', [AdminPhotoReportageTranslationsController::class, 'store'], ['as' => 'admin'])->name('photo_reportage_translations.store');
        Route::get('/photo_reportage_translations/{id}/edit', [AdminPhotoReportageTranslationsController::class, 'edit'], ['as' => 'admin'])->name('photo_reportage_translations.edit');
        Route::put('/photo_reportage_translations/{id}', [AdminPhotoReportageTranslationsController::class, 'update'], ['as' => 'admin'])->name('photo_reportage_translations.update');
    });


    Route::resource('categories', AdminCategoriesController::class, ['as' => 'admin']);

    Route::resource('authors', AdminAuthorsController::class, ['as' => 'admin']);
    Route::prefix('authors')->group(function() {
        Route::get('/{author_id}/authors_translations/create', [AdminAuthorsTranslationsController::class, 'create'], ['as' => 'admin'])->name('authors_translations.create');
        Route::post('/{author_id}/authors_translations', [AdminAuthorsTranslationsController::class, 'store'], ['as' => 'admin'])->name('authors_translations.store');
        Route::get('/authors_translations/{id}/edit', [AdminAuthorsTranslationsController::class, 'edit'], ['as' => 'admin'])->name('authors_translations.edit');
        Route::put('/authors_translations/{id}', [AdminAuthorsTranslationsController::class, 'update'], ['as' => 'admin'])->name('authors_translations.update');
    });

    Route::resource('experts', AdminExpertController::class, ['as' => 'admin']);


    Route::resource('files', AdminFilesController::class, ['as' => 'admin']);

    Route::resource('users', AdminUsersController::class, ['as' => 'admin']);

    Route::resource('newspapers', AdminNewspapersController::class, ['as' => 'admin']);

    Route::get('/statistics', [AdminStatisticsController::class, 'index'], ['as' => 'admin'])->name('statistics.index');
    Route::get('/statistics/materials', [AdminStatisticsController::class, 'materials'], ['as' => 'admin'])->name('statistics.materials');

    Route::resource('video_articles', AdminVideoArticlesController::class, ['as' => 'admin']);
    Route::prefix('video_articles')->group(function() {
        Route::get('/{video_article_id}/video_articles_translations/create', [AdminVideoArticlesTranslationsController::class, 'create'], ['as' => 'admin'])->name('video_articles_translations.create');
        Route::post('/{video_article_id}/video_articles_translations', [AdminVideoArticlesTranslationsController::class, 'store'], ['as' => 'admin'])->name('video_articles_translations.store');
        Route::get('/video_articles_translations/{id}/edit', [AdminVideoArticlesTranslationsController::class, 'edit'], ['as' => 'admin'])->name('video_articles_translations.edit');
        Route::put('/video_articles_translations/{id}', [AdminVideoArticlesTranslationsController::class, 'update'], ['as' => 'admin'])->name('video_articles_translations.update');
    });


    Route::resource('documents', AdminDocumentsController::class, ['as' => 'admin']);
    Route::prefix('documents')->group(function() {
        Route::get('/{document_id}/documents_translations/create', [AdminDocumentsTranslationsController::class, 'create'], ['as' => 'admin'])->name('documents_translations.create');
        Route::post('/{document_id}/documents_translations', [AdminDocumentsTranslationsController::class, 'store'], ['as' => 'admin'])->name('documents_translations.store');
        Route::get('/documents_translations/{id}/edit', [AdminDocumentsTranslationsController::class, 'edit'], ['as' => 'admin'])->name('documents_translations.edit');
        Route::put('/documents_translations/{id}', [AdminDocumentsTranslationsController::class, 'update'], ['as' => 'admin'])->name('documents_translations.update');
    });

    Route::resource('litsalon_articles', AdminLitsalonArticlesController::class, ['as' => 'admin']);
    Route::prefix('litsalon_articles')->group(function() {
        Route::delete('/{id}/{publication_id}/delete_publication', [AdminLitsalonArticlesController::class, 'destroyPublication'], ['as' => 'admin'])->name('litsalon_articles.destroy_publication');
        Route::delete('/{id}/{award_id}/delete_award', [AdminLitsalonArticlesController::class, 'destroyAward'], ['as' => 'admin'])->name('litsalon_articles.destroy_award');
        Route::get('/{litsalon_article_id}/litsalon_articles_translations/create', [AdminLitsalonArticlesTranslationsController::class, 'create'], ['as' => 'admin'])->name('litsalon_articles_translations.create');
        Route::post('/{litsalon_article_id}/litsalon_articles_translations', [AdminLitsalonArticlesTranslationsController::class, 'store'], ['as' => 'admin'])->name('litsalon_articles_translations.store');
        Route::get('/litsalon_articles_translations/{id}/edit', [AdminLitsalonArticlesTranslationsController::class, 'edit'], ['as' => 'admin'])->name('litsalon_articles_translations.edit');
        Route::put('/litsalon_articles_translations/{id}', [AdminLitsalonArticlesTranslationsController::class, 'update'], ['as' => 'admin'])->name('litsalon_articles_translations.update');
    });
});

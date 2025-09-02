<?php

declare(strict_types=1);

use App\Http\Controllers\Backend\ActionLogController;
use App\Http\Controllers\Backend\Auth\ScreenshotGeneratorLoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LocaleController;
use App\Http\Controllers\Backend\ModulesController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\PostsController;
use App\Http\Controllers\Backend\ProfilesController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\TermsController;
use App\Http\Controllers\Backend\TranslationController;
use App\Http\Controllers\Backend\UserLoginAsController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CollectionController;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\FarmerController;
use App\Http\Controllers\Backend\CropController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PromocodeController;

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



/**
 * Admin routes.
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::delete('roles/delete/bulk-delete', [RolesController::class, 'bulkDelete'])->name('roles.bulk-delete');

    // Permissions Routes.
    Route::get('/permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{id}', [PermissionsController::class, 'show'])->name('permissions.show');

    // Modules Routes.
    Route::get('/modules', [ModulesController::class, 'index'])->name('modules.index');
    Route::post('/modules/toggle-status/{module}', [ModulesController::class, 'toggleStatus'])->name('modules.toggle-status');
    Route::post('/modules/upload', [ModulesController::class, 'store'])->name('modules.store');
    Route::delete('/modules/{module}', [ModulesController::class, 'destroy'])->name('modules.delete');

    // Settings Routes.
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

    // Translation Routes
    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::post('/translations', [TranslationController::class, 'update'])->name('translations.update');
    Route::post('/translations/create', [TranslationController::class, 'create'])->name('translations.create');

    // Login as & Switch back
    Route::resource('users', UsersController::class);
    Route::delete('users/delete/bulk-delete', [UsersController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('users/{id}/login-as', [UserLoginAsController::class, 'loginAs'])->name('users.login-as');
    Route::post('users/switch-back', [UserLoginAsController::class, 'switchBack'])->name('users.switch-back');
    
    Route::get('/supplier-user/editStore/{id?}', [UsersController::class, 'editStore'])->name('users.edit-store');
    Route::post('/supplier-user/save-store', [UsersController::class, 'saveStore'])->name('users.save-store');
    
    // Action Log Routes.
    Route::get('/action-log', [ActionLogController::class, 'index'])->name('actionlog.index');

    // Content Management Routes

    // Posts/Pages Routes - Dynamic post types
    Route::get('/posts/{postType?}', [PostsController::class, 'index'])->name('posts.index');
    Route::get('/posts/{postType}/create', [PostsController::class, 'create'])->name('posts.create');
    Route::post('/posts/{postType}', [PostsController::class, 'store'])->name('posts.store');
    Route::get('/posts/{postType}/{id}', [PostsController::class, 'show'])->name('posts.show');
    Route::get('/posts/{postType}/{id}/edit', [PostsController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{postType}/{id}', [PostsController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{postType}/{id}', [PostsController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/posts/{postType}/delete/bulk-delete', [PostsController::class, 'bulkDelete'])->name('posts.bulk-delete');

    // Terms Routes (Categories, Tags, etc.)
    Route::get('/terms/{taxonomy}', [TermsController::class, 'index'])->name('terms.index');
    Route::get('/terms/{taxonomy}/{term}/edit', [TermsController::class, 'edit'])->name('terms.edit');
    Route::post('/terms/{taxonomy}', [TermsController::class, 'store'])->name('terms.store');
    Route::put('/terms/{taxonomy}/{id}', [TermsController::class, 'update'])->name('terms.update');
    Route::delete('/terms/{taxonomy}/{id}', [TermsController::class, 'destroy'])->name('terms.destroy');
    Route::delete('/terms/{taxonomy}/delete/bulk-delete', [TermsController::class, 'bulkDelete'])->name('terms.bulk-delete');

    // Editor Upload Route
    Route::post('/editor/upload', [App\Http\Controllers\Backend\EditorController::class, 'upload'])->name('editor.upload');

    // Category Route
    Route::resource('category', CategoryController::class);
    Route::get('/category/{id}/subcategories', [CategoryController::class, 'getSubcategories']);

    // Collection Route
    Route::resource('collection', CollectionController::class);
    Route::get('/collection/{id}/subcollections', [CollectionController::class, 'getSubcollections']);

    // Brand Route
    Route::resource('brand', BrandController::class);
    Route::resource('crop', CropController::class);    
    Route::get('/crop/{crop}/details', [CropController::class, 'details']);

    Route::resource('tag', TagController::class);
    Route::resource('product', ProductController::class);
    Route::put('product/update/update-inventory/{id}', [ProductController::class, 'updateInventory'])->name('product.update-inventory');
    Route::resource('promocode', PromocodeController::class);

    // Farmers Route
    Route::resource('farmer', FarmerController::class);
    Route::get('/farmer/list-farm/{id}', [FarmerController::class, 'farmList'])->name('farmer.listFarm');
    Route::get('/farmer/add-farm/{id}', [FarmerController::class, 'farmCreate'])->name('farmer.createFarm');
    Route::post('/farmer/add-farm', [FarmerController::class, 'farmStore']);
    
    // Orders Route
    Route::resource('orders', OrderController::class);
    Route::put('orders/update/bulk-update', [OrderController::class, 'bulkUpdate'])->name('orders.bulk-update');


});

/**
 * Profile routes.
 */
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    Route::get('/edit', [ProfilesController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfilesController::class, 'update'])->name('update');
});

Route::get('/locale/{lang}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/screenshot-login/{email}', [ScreenshotGeneratorLoginController::class, 'login'])->middleware('web')->name('screenshot.login');

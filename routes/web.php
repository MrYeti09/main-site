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

\Viaativa\Viaroot\Helpers\Router::registerPageRoutes();

//Route::get('test-set-redis/{text}', function($text){
//    \Illuminate\Support\Facades\Redis::set('test', $text);
//    echo "Redis Setted";
//});
//
//Route::get('test-get-redis', function(){
//    $test = \Illuminate\Support\Facades\Redis::get('test');
//    echo "Redis Getted: {$test}";
//});


Route::post('voyager-forms-submit-enquiry/{id}',['uses' => 'Viaativa\Viaroot\Http\Controllers\EnquiryController@submit', 'as' => 'voyager.enquiries.submit']);
Route::get('{slug?}',['uses' => "Viaativa\Viaroot\Http\Controllers\PageViaController@getPage", 'as' => '', 'middleware' => 'web']);
Route::get('{slug?}/amp',['uses' => "Viaativa\Viaroot\Http\Controllers\PageViaController@getPage", 'as' => 'amp', 'middleware' => 'web']);
Route::group(['prefix' => 'admin','middleware' => ['web', 'admin.user']], function () {

    Route::post('optimize',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@optimize", 'as' => 'optimize']);
    Route::get('optimize',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@view_optimize", 'as' => 'optimize']);
    Route::get('verify',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@verify", 'as' => 'verify-admin']);
    Route::get('toggle-widget',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@toggle_widget", 'as' => 'toggle-widget']);
    Route::get('docs',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@docs", 'as' => 'docs-dev']);
    Route::get('verify/{menu}',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@verify_menu", 'as' => 'verify-menu']);
    Route::post('save-log',['uses' => "Viaativa\Viaroot\Http\Controllers\Controller@write_log", 'as' => 'save-log']);
    Route::post('setup',['uses' => 'Viaativa\Viaroot\Http\Controllers\Controller@setup_voyager', 'as' => 'voyager.setup']);

    Route::get('notifications/view',['uses' => 'Viaativa\Viaroot\Http\Controllers\Controller@mark_notification', 'as' => 'notification-view']);

    Route::group([
        'as' => 'voyager.bread.','namespace' => 'Viaativa\Viaroot\Http\Controllers\Voyager', 'prefix' => 'bread'
    ], function () {
        Route::post('action/save', ['uses' => "VoyagerBreadController@save_block", 'as' => 'save-block']);
    });

    Route::group([
        'as' => 'voyager.page-blocks.','namespace' => 'Viaativa\Viaroot\Http\Controllers', 'prefix' => 'page-blocks'
    ], function () {
        Route::post('duplicate', ['uses' => "PageBlockController@duplicate", 'as' => 'duplicate']);
        Route::post('duplicate-to', ['uses' => "PageBlockController@duplicate_to", 'as' => 'duplicate-to']);
        Route::post('custom-slug', ['uses' => "PageBlockController@custom", 'as' => 'custom-slug']);
        Route::post('duplicate-item-to', ['uses' => "PageBlockController@duplicate_item_to", 'as' => 'duplicate-item-to']);
        Route::get('action/sort', ['uses' => "PageBlockController@sort", 'as' => 'sort-custom']);
        Route::get('action/template/save-block', ['uses' => "PageBlockController@create_layout", 'as' => 'create-layout']);
        Route::get('action/template/{template}/add/{page}', ['uses' => "PageBlockController@add_template", 'as' => 'add-template']);
        Route::get('action/template/remove', ['uses' => "PageBlockController@remove_template", 'as' => 'remove-template']);
        Route::post('action/blocks/delete', ['uses' => "PageBlockController@delete_blocks", 'as' => 'delete-blocks']);
        Route::get('action/template/edit/name', ['uses' => "PageBlockController@edit_template_name", 'as' => 'edit-template-name']);
        Route::get('action/remove-image', ['uses' => "PageBlockController@remove", 'as' => 'remove-image']);
        Route::post('{id}/action/modal', ['uses' => "PageBlockController@editmodal", 'as' => 'block-modal']);
        Route::get('edit/block', ['uses' => "PageBlockController@load_block_form", 'as' => 'block-form']);
        Route::get('{id}/action/add-tab', ['uses' => "PageBlockController@add_tab", "as" => 'add-tab']);
        Route::get('{id}/action/remove-tab/{tab_id}', ['uses' => "PageBlockController@remove_tab", "as" => 'remove-tab']);

        Route::get('{id}/action/modal', function(){
            abort(419);
        });
        Route::get('edit/header-footer', ['uses' => "PageBlockController@mainsettings", "as" => 'main-settings']);
        Route::post('settings-modal', ['uses' => "PageBlockController@settings_modal", 'as' => 'settings-modal']);
        Route::post('edit/header-footer/add', ['uses' => "PageBlockController@add_main", "as" => 'main-settings.add']);
    });



    Route::group([
        'as' => 'voyager.page-categories.',
        'middleware' => 'admin.user',
        'namespace' => 'Viaativa\Viaroot\Http\Controllers',
    ], function () {
        Route::post('sortitem',['uses' => "PageCategoryController@sort_page", "as" => 'sort-item']);
        Route::post('page-categories/{slug}',['uses' => "PageCategoryController@navigate", "as" => 'navigate']);
        Route::get('page-categories/{id}/blocks',['uses' => "PageCategoryController@edit_blocks", "as" => 'blocks.view']);
        Route::get('page-categories/{template}/add/{page}', ['uses' => "PageCategoryController@add_template", 'as' => 'blocks.add-template']);
        Route::post('page-categories/{id}/blocks/add',['uses' => "PageCategoryController@store_block", "as" => 'blocks.store']);
        Route::get('page-categories/action/delete/{slug}',['uses' => "PageCategoryController@delete", "as" => 'delete-page-category']);

    });

    Route::group([
        'as' => 'voyager.menus.',
        'middleware' => 'admin.user',
        'namespace' => 'Viaativa\Viaroot\Http\Controllers\Voyager',
    ], function () {
        Route::post('{menu}/action/order', ['uses' => "VoyagerMenuController@order", 'as' => 'order-faster']);

    });

    Route::group([
        'as' => 'voyager.pages.',
        'middleware' => 'admin.user',
        'namespace' => 'Viaativa\Viaroot\Http\Controllers',
    ], function () {
        Route::post('duplicate', ['uses' => "PageViaController@duplicate", 'as' => 'duplicate']);
        Route::post('load-block-customized', ['uses' => "PageBlockController@load_block", "as" => 'load-block']);
        Route::post('sort',['uses' => "PageBlockController@sort_page", "as" => 'sort-item']);

    });



});

Route::get('/estado', ['uses' => '\Viaativa\Viaroot\Http\Controllers\PageViaController@get_city', 'as' => 'city']);

Route::group(['as' => 'voyager.','middleware' => 'web','namespace' => 'Viaativa\Viaroot\Http\Controllers'], function () {

    $namespacePrefix = '\\' . config('voyager.controllers.namespace') . '\\';

    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        // Menu Routes
        Route::group([
            'as' => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {

            Route::group([
                'as' => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::put('/', ['uses' => $namespacePrefix . 'VoyagerMenuController@update_item_advanced', 'as' => 'update']);
            });
        });
    });
});


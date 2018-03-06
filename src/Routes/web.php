<?php

    $routePrefix = config('cabin.backend-route-prefix', 'cabin');

    Route::group(['middleware' => 'web'], function () use ($routePrefix) {
        Route::get($routePrefix, 'CabinFeatureController@sendHome');

        /*
        |--------------------------------------------------------------------------
        | Set Language
        |--------------------------------------------------------------------------
        */

        Route::get($routePrefix.'/language/set/{language}', 'CabinFeatureController@setLanguage');

        /*
        |--------------------------------------------------------------------------
        | Public Routes
        |--------------------------------------------------------------------------
        */

        Route::get('public-preview/{encFileName}', 'AssetController@asPreview');
        Route::get('public-asset/{encFileName}', 'AssetController@asPublic');
        Route::get('public-download/{encFileName}/{encRealFileName}', 'AssetController@asDownload');

        /*
         * --------------------------------------------------------------------------
         * Internal APIs
         * --------------------------------------------------------------------------
        */
        Route::group(['middleware' => 'auth'], function () {
            Route::group(['prefix' => 'cabin/api'], function () {
                Route::get('images/list', 'ImagesController@apiList');
                Route::post('images/store', 'ImagesController@apiStore');
                Route::get('files/list', 'FilesController@apiList');
            });

            Route::group(['prefix' => 'cabin'], function () {
                Route::get('images/bulk-delete/{ids}', 'ImagesController@bulkDelete');
                Route::post('images/upload', 'ImagesController@upload');
                Route::post('files/upload', 'FilesController@upload');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Cabin
        |--------------------------------------------------------------------------
        */

        Route::group(['prefix' => $routePrefix], function () use ($routePrefix) {
            Route::get('asset/{path}/{contentType}', 'AssetController@asset');

            Route::group(['middleware' => ['auth', 'cabin']], function () use ($routePrefix) {
                Route::get('dashboard', 'DashboardController@main');
                Route::get('help', 'HelpController@main');

                /*
                |--------------------------------------------------------------------------
                | Common Features
                |--------------------------------------------------------------------------
                */

                Route::get('preview/{entity}/{entityId}', 'CabinFeatureController@preview');
                Route::get('rollback/{entity}/{entityId}', 'CabinFeatureController@rollback');
                Route::get('revert/{id}', 'CabinFeatureController@revert');

                /*
                |--------------------------------------------------------------------------
                | Menus
                |--------------------------------------------------------------------------
                */

                Route::resource('menus', 'MenuController', ['except' => ['show'], 'as' => $routePrefix]);
                Route::post('menus/search', 'MenuController@search');
                Route::put('menus/{id}/order', 'MenuController@setOrder');

                /*
                |--------------------------------------------------------------------------
                | Links
                |--------------------------------------------------------------------------
                */

                Route::resource('links', 'LinksController', ['except' => ['index', 'show'], 'as' => $routePrefix]);
                Route::post('links/search', 'LinksController@search');

                /*
                |--------------------------------------------------------------------------
                | Images
                |--------------------------------------------------------------------------
                */

                Route::resource('images', 'ImagesController', ['as' => $routePrefix, 'except' => ['show']]);
                Route::post('images/search', 'ImagesController@search');

                /*
                |--------------------------------------------------------------------------
                | Blog
                |--------------------------------------------------------------------------
                */

                Route::resource('blog', 'BlogController', ['as' => $routePrefix, 'except' => ['show']]);
                Route::post('blog/search', 'BlogController@search');
                Route::get('blog/{id}/history', 'BlogController@history');

                /*
                |--------------------------------------------------------------------------
                | Pages
                |--------------------------------------------------------------------------
                */

                Route::resource('pages', 'PagesController', ['as' => $routePrefix, 'except' => ['show']]);
                Route::post('pages/search', 'PagesController@search');
                Route::get('pages/{id}/history', 'PagesController@history');

                /*
                |--------------------------------------------------------------------------
                | Widgets
                |--------------------------------------------------------------------------
                */

                Route::resource('widgets', 'WidgetsController', ['as' => $routePrefix, 'except' => ['show']]);
                Route::post('widgets/search', 'WidgetsController@search');

                /*
                |--------------------------------------------------------------------------
                | FAQs
                |--------------------------------------------------------------------------
                */

                Route::resource('faqs', 'FAQController', ['as' => $routePrefix, 'except' => ['show']]);
                Route::post('faqs/search', 'FAQController@search');

                /*
                |--------------------------------------------------------------------------
                | Events
                |--------------------------------------------------------------------------
                */

                Route::resource('events', 'EventController', ['as' => $routePrefix, 'except' => ['show']]);
                Route::post('events/search', 'EventController@search');
                Route::get('events/{id}/history', 'EventController@history');

                /*
                |--------------------------------------------------------------------------
                | Files
                |--------------------------------------------------------------------------
                */

                Route::get('files/remove/{id}', 'FilesController@remove');
                Route::post('files/search', 'FilesController@search');

                Route::resource('files', 'FilesController', ['as' => $routePrefix, 'except' => ['show']]);
            });
        });
    });

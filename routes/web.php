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

Auth::routes(['register' => false]);

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('set_locale');

Route::group(['as' => 'app.', 'middleware' => 'locale'], function() {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/about', 'HomeController@about')->name('about');
    Route::get('/team', 'HomeController@team')->name('team');
    Route::get('/partner', 'HomeController@partner')->name('partner');
    Route::get('/product', 'HomeController@product')->name('product');

    Route::get('/blog', 'HomeController@blog')->name('blog');
    Route::get('/blog/category/{id}', 'HomeController@blogCategory')->name('blog.category');
    Route::get('/blog/{slug}', 'HomeController@single')->name('single');

    Route::get('/career', 'HomeController@career')->name('career');
    Route::get('/career/{id}/apply', 'HomeController@careerApply')->name('career.apply');
    Route::post('/career/apply', 'HomeController@apply')->name('career.post');

    Route::get('/contact', 'HomeController@contact')->name('contact');
    Route::post('/contact/send', 'HomeController@send')->name('contact.send');

    Route::get('/search', 'HomeController@search')->name('search');
});

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'view backend', 'as' => 'admin.'], function() {
    Route::get('/', 'AdminController@index')->name('dashboard');

    Route::group(['prefix' => 'message', 'middleware' => 'manage message', 'as' => 'message.'], function() {
        Route::get('/', 'AdminController@message')->name('index');
        Route::get('/{id}', 'AdminController@showMessage')->name('show');
        Route::delete('/{id}/destroy', 'AdminController@deleteMessage')->name('destroy');
    });

    // General Setting
    Route::group(['prefix' => 'setting', 'middleware' => 'manage setting', 'as' => 'setting.'], function() {
        Route::get('/', 'AdminController@profile')->name('profile');

        Route::get('/profile', 'AdminController@profile')->name('profile');
        Route::post('/profile/save', 'AdminController@saveProfile')->name('profile.save');

        Route::delete('/profile/{id}/destroy-logo', 'AdminController@destroyProfileLogo')->name('profile.destroyLogo');

        Route::group(['prefix' => 'mission', 'as' => 'mission.'], function() {
            Route::post('/', 'AdminController@getMission')->name('index');
            Route::post('/store', 'AdminController@storeMission')->name('store');
            Route::patch('/update', 'AdminController@updateMission')->name('update');
            Route::delete('/destroy', 'AdminController@destroyMission')->name('destroy');
        });

        Route::get('/social', 'AdminController@social')->name('social');
        Route::post('/social/save', 'AdminController@saveSocial')->name('social.save');

        Route::get('/web', 'AdminController@web')->name('web');
        Route::post('/web/save', 'AdminController@saveWeb')->name('web.save');

        Route::delete('/web/{id}/destroy-logo', 'AdminController@destroyWebLogo')->name('web.destroyLogo');

    });

    // Profile Route
    Route::group(['prefix' => 'profile', 'middleware' => 'manage profile', 'as' => 'profile.'], function() {
        Route::get('/', 'ProfileController@index')->name('index');

        Route::get('/preview/{id}', function($id) {
            Session::put('profile-preview', $id);
            return redirect()->route('app.home');
        })->name('preview');

        Route::get('/exit', function() {
            Session::forget('locale');
            Session::forget('profile-preview');
            return back();
        })->name('exit_preview');

        Route::get('/get-active', 'ProfileController@getActive')->name('get_active');
        Route::get('/get-preview', 'ProfileController@getPreview')->name('get_preview');
        Route::post('/store', 'ProfileController@store')->name('store');
        Route::patch('/update', 'ProfileController@update')->name('update');
        Route::patch('/update-active', 'ProfileController@updateActive')->name('update_active');
        Route::patch('/update-preview', 'ProfileController@updatePreview')->name('update_preview');
        Route::delete('/destroy', 'ProfileController@destroy')->name('destroy');
    });

    // Team Route
    Route::group(['prefix' => 'team', 'middleware' => 'manage team', 'as' => 'team.'], function() {
        Route::get('/', 'TeamController@index')->name('index');
        Route::get('/create', 'TeamController@create')->name('create');

        Route::get('/division/{id}', 'TeamController@teamDivision')->name('team_division');

        Route::post('/datatable', 'TeamController@datatable')->name('datatable');
        Route::post('/store', 'TeamController@store')->name('store');

        Route::delete('/destroy', 'TeamController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'TeamController@destroyAll')->name('destroy_all');

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/edit', 'TeamController@edit')->name('edit');
            Route::patch('/update', 'TeamController@update')->name('update');
        });

        Route::group(['prefix' => 'division', 'as' => 'division.'], function() {
            Route::get('/', 'TeamController@divisionIndex')->name('index');
            Route::post('/store', 'TeamController@divisionStore')->name('store');
            Route::post('/update', 'TeamController@divisionUpdate')->name('update');
            Route::delete('/destroy', 'TeamController@divisionDestroy')->name('destroy');
        });
    });

    // Product Route
    Route::group(['prefix' => 'product', 'middleware' => 'manage product', 'as' => 'product.'], function() {
        Route::get('/', 'ProductController@index')->name('index');
        Route::get('/datatable', 'ProductController@datatable')->name('datatable');
        Route::get('/create', 'ProductController@create')->name('create');

        Route::post('/store', 'ProductController@store')->name('store');
        Route::post('/get-edit', 'ProductController@getEdit')->name('get_edit');

        Route::patch('/update', 'ProductController@update')->name('update');

        Route::delete('/destroy', 'ProductController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'ProductController@destroyAll')->name('destroy_all');
    });

    // Client Route
    Route::group(['prefix' => 'client', 'middleware' => 'manage client', 'as' => 'client.'], function() {
        Route::get('/', 'ClientController@index')->name('index');
        Route::get('/datatable', 'ClientController@datatable')->name('datatable');
        Route::get('/create', 'ClientController@create')->name('create');

        Route::post('/store', 'ClientController@store')->name('store');
        Route::post('/get-edit', 'ClientController@getEdit')->name('get_edit');

        Route::patch('/update', 'ClientController@update')->name('update');

        Route::delete('/destroy', 'ClientController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'ClientController@destroyAll')->name('destroy_all');
    });

    // Service Route
    Route::group(['prefix' => 'service', 'middleware' => 'manage service', 'as' => 'service.'], function() {
        Route::get('/', 'ServiceController@index')->name('index');
        Route::get('/datatable', 'ServiceController@datatable')->name('datatable');
        Route::get('/create', 'ServiceController@create')->name('create');

        Route::post('/store', 'ServiceController@store')->name('store');
        Route::post('/get-edit', 'ServiceController@getEdit')->name('get_edit');

        Route::patch('/update', 'ServiceController@update')->name('update');

        Route::delete('/destroy', 'ServiceController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'ServiceController@destroyAll')->name('destroy_all');
    });

    // Testimony Route
    Route::group(['prefix' => 'testimony', 'middleware' => 'manage testimony', 'as' => 'testimony.'], function() {
        Route::get('/', 'TestimonyController@index')->name('index');
        Route::get('/datatable', 'TestimonyController@datatable')->name('datatable');
        Route::get('/create', 'TestimonyController@create')->name('create');

        Route::post('/store', 'TestimonyController@store')->name('store');
        Route::post('/get-edit', 'TestimonyController@getEdit')->name('get_edit');

        Route::patch('/update', 'TestimonyController@update')->name('update');

        Route::delete('/destroy', 'TestimonyController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'TestimonyController@destroyAll')->name('destroy_all');
    });

    // Admin Management Route
    Route::group(['prefix' => 'user', 'middleware' => 'manage user', 'as' => 'user.'], function() {
        Route::get('/', 'UserController@index')->name('index');
        Route::get('/datatable', 'UserController@datatable')->name('datatable');

        Route::post('/store', 'UserController@store')->name('store');
        Route::delete('/destroy', 'UserController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'UserController@destroyAll')->name('destroy_all');

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/edit', 'UserController@edit')->name('edit');
            Route::patch('/update', 'UserController@update')->name('update');
        });

        Route::group(['prefix' => 'role', 'as' => 'role.'], function() {
            Route::get('/', 'RoleController@index')->name('index');
            Route::post('/edit', 'RoleController@edit')->name('get_edit');
            Route::post('/store', 'RoleController@store')->name('store');
            Route::post('/update', 'RoleController@update')->name('update');
            Route::delete('/destroy', 'RoleController@destroy')->name('destroy');
        });
    });

    // Post Route
    Route::group(['prefix' => 'post', 'middleware' => 'manage blog', 'as' => 'post.'], function() {
        Route::get('/', 'PostController@index')->name('index');
        // Route::get('/type/{type}', 'PostController@postType')->name('type');
        Route::get('/create', 'PostController@create')->name('create');
        Route::get('/datatable', 'PostController@datatable')->name('datatable');
        Route::get('/get-category', 'PostController@getCategory')->name('get_category');

        Route::post('/store', 'PostController@store')->name('store');
        Route::post('/add-category', 'PostController@addCategory')->name('add_category');

        Route::delete('/destroy', 'PostController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'PostController@destroyAll')->name('destroy_all');
        Route::delete('/destroy-category', 'PostController@destroyCategory')->name('destroy_category');

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/edit', 'PostController@edit')->name('edit');
            Route::patch('/update', 'PostController@update')->name('update');
        });
    });

    // Career Route
    Route::group(['prefix' => 'career', 'middleware' => 'manage career', 'as' => 'career.'], function() {
        Route::get('/', 'CareerController@index')->name('index');
        Route::get('/create', 'CareerController@create')->name('create');
        Route::get('/datatable', 'CareerController@datatable')->name('datatable');
        Route::post('/datatable/applicant', 'CareerController@datatableApplicant')->name('datatableApplicant');
        Route::post('/store', 'CareerController@store')->name('store');

        Route::delete('/destroy', 'CareerController@destroy')->name('destroy');
        Route::delete('/destroy-all', 'CareerController@destroyAll')->name('destroy_all');

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/show', 'CareerController@show')->name('show');
            Route::get('/edit', 'CareerController@edit')->name('edit');
            Route::patch('/update', 'CareerController@update')->name('update');
        });

        Route::get('/get-category', 'CareerController@getCategory')->name('get_category');
        Route::post('/add-category', 'CareerController@addCategory')->name('add_category');
        Route::delete('/destroy-category', 'CareerController@destroyCategory')->name('destroy_category');

        Route::get('/applicants', 'CareerController@applicant')->name('applicant.index');
        Route::get('/applicants/datatable', 'CareerController@applicantDatatable')->name('applicant.datatable');
        Route::delete('/applicants/destroy', 'CareerController@applicantDestroy')->name('applicant.destroy');

    });

});

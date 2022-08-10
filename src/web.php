<?php

Route::namespace('Administration\Controllers')->group(function () {
    Route::middleware(['web', 'auth'])->group(function () {

        Route::get('/documentation', 'DocumentationController@documentation')->middleware('role_or_permission:Super Admin|Admin|permissions show|permissions index|permissions edit|permissions create|permissions delete')->name('doc-home');
        Route::get('/doc-contact', 'DocumentationController@contact')->name('doc-contact');
        Route::resource('doc', 'DocumentationController');
        Route::post('/send-mail', 'DocumentationController@sendMail')->name('doc-send-mail');
        Route::get('/doc/table/data', 'DocumentationController@tableData')->name('docs.data');
        Route::resource('changelog', 'ChangeLogController');
        Route::get('/changelog/table/data', 'ChangeLogController@tableData')->name('changelog.data');
        Route::get('/doc-changelog', 'ChangeLogController@changelog')->name('doc-changelog');

        Route::get('/home', 'HomeController@index')->name('home');

        Route::resource('permissions', 'PermissionController');
        Route::get('/permissions/table/data', 'PermissionController@tableData')->name('permissions.data');

        Route::resource('roles', 'RoleController');
        Route::get('/roles/table/data', 'RoleController@tableData')->name('roles.data');
        Route::get('/roles/render/form', 'RoleController@renderForm')->name('roles.form');

        Route::resource('users', 'UserController');
        Route::get('/users/table/data', 'UserController@tableData')->name('users.data');
        Route::get('/users/unlock/{user}', 'UserController@unlock')->name('users.unlock');
        Route::put('/users/{user}/reset', 'UserController@resetPassword')->name('users.reset');
        Route::get('/profile/{user}/', 'UserController@edit')->name('users.profile');
        Route::post('/profile/{user}/update', 'UserController@updatePrimaryData')->name('users.user-update');
        Route::post('/profile/{user}/password', 'UserController@updatePassword')->name('users.password');

        Route::resource('activity-logs', 'ActivityLogController');
        Route::get('/activity-logs/table/data', 'ActivityLogController@tableData')->name('activity-logs.data');

        Route::resource('menu', 'MenuController');
        Route::get('/menu/table/data', 'MenuController@tableData')->name('menu.data');
    });
});

Route::middleware(['web'])->group(function () {
    Route::post('user-login', 'Administration\Controllers\LoginController@login')->name('user.login');
});

Route::post('log-out', 'Administration\Controllers\LoginController@logout')->name('log-out');

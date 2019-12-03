<?php

Route::namespace('Administration\Controllers')->group(function () {
    Route::middleware(['web', 'auth'])->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');

        Route::resource('permissions', 'PermissionController')->middleware(['permission_in_role:permissions index']);
        Route::get('/permissions/table/data', 'PermissionController@tableData')->name('permissions.data');

        Route::resource('roles', 'RoleController');
        Route::get('/roles/table/data', 'RoleController@tableData')->name('roles.data');

        Route::resource('users', 'UserController');
        Route::get('/users/table/data', 'UserController@tableData')->name('users.data');
        Route::put('/users/{user}/reset', 'UserController@resetPassword')->name('users.data');
    });
});


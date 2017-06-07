<?php

// Route native à Laravel
Route::get('/', 'ActiviteController@index');

// Route de l'API
Route::group(['prefix' => 'api'], function(){

		Route::resource('activite', 'ActiviteController');

		Route::get('activite/{id}/next', 'ActiviteController@goNextScene')->name('activite.next');
		Route::get('activite/{id}/show', 'ActiviteController@showScene')->name('activite.showScene');
		Route::get('activite/{id}/new', 'SessionController@newSession')->name('activite.new');

		Route::resource('result', 'ResultController');

});

Auth::routes();

Route::get('/home', 'HomeController@index');

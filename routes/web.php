<?php

// Route native à Laravel
Route::get('/', 'ActiviteController@index');

// Route de l'API
Route::group(['prefix' => 'api'], function(){

			Route::resource('activite', 'ActiviteController');

			Route::get('activite/{id}/next', 'ActiviteController@goNextScene')->name('activite.next');
			Route::get('activite/{id}/previous', 'ActiviteController@goPreviousScene')->name('activite.previous');			// Finish.Activite
			Route::get('activite/{id}/result', 'ActiviteController@viewResults')->name('activite.result');
			Route::get('activite/{id}/new', 'SessionController@newSession')->name('activite.new');

			Route::resource('results', 'ResultsController');

			// Route adapter : .../api/context/{ctx}
      Route::resource('context', 'ContextController');


});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/exercice', 'H5PController@exercice');

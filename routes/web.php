<?php

// Route native à Laravel
Route::get('/', function () { return view('welcome'); });

// Route de l'API
Route::group(['prefix' => 'api'], function(){

			Route::get('activite', 'ActiviteController@index');
      Route::get('activite/{id}', 'ActiviteController@show');

});

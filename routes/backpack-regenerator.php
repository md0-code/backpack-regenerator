<?php

Route::group([
	'namespace'  => 'MD0\BackpackReGenerator\Http\Controllers',
	'prefix' => config('backpack.base.route_prefix', 'admin'),
	'middleware' => array_merge(
		(array) config('backpack.base.web_middleware', 'web'),
		(array) config('backpack.base.middleware_key', 'admin')
	),
], function () {
	Route::crud('reports', ReportsCrudController::class);
	Route::get('reports/{id}/pdf', 'ReportsCrudController@getPdfReport');
	Route::get('reports/{id}/csv', 'ReportsCrudController@getCsvReport');
});

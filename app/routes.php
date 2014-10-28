<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'MainController@makeIndex');

Route::get('checkShows', 'MainController@checkNewShows');

Route::get('show/{name}', array('as' => 'show', function($name){
	$showController = new ShowController();
	return $showController->getShow($name);
}));

Route::get('show/{name}/{season}/{episode}', array('as' => 'episode', function($name, $season, $episode){
	$showController = new ShowController();
	return $showController->getEpisode($name, $season, $episode);
}));

Route::post('search', function(){
	$search = Input::get('search_term');
	$mainController = new MainController();
	return $mainController->search($search);
});

Route::get('search/{search}', function($search){
	$mainController = new MainController();
	return $mainController->search($search);
});

Route::get('torrent', array('as' => 'torrent', function(){
	$mainController = new MainController();
	$mainController->addTorrent(Input::get('magnet'),  html_entity_decode($Input::get('name')));
}));
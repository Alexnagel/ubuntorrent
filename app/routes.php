<?php
use Moinax\TvDb\Client;
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
	$tvdb = new Client('http://thetvdb.com', '6FCFFB713EA09F9E');

	$serverTime = $tvdb->getServerTime();
	// Search for a show
	$data = $tvdb->getSeries($name);
	// Use the first show found and get the S01E01 episode
	$show = $tvdb->getSerie($data[0]->id);
	
	return View::make('show')->with(array('show' => $show));
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
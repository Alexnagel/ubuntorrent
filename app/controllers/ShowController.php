<?php
use Moinax\TvDb\Client;

class ShowController extends BaseController {
	
	public function getShow($name)
	{
		$show = Serie::where('name', '=', $name)->first();

		if(count($show) == 0)
		{
			$tvdb = new Client('http://thetvdb.com', '6FCFFB713EA09F9E');

			$serverTime = $tvdb->getServerTime();
			// Search for a show
			$data = $tvdb->getSeries($name);
			// Use the first show found and get the S01E01 episode
			$show = $tvdb->getSerie($data[0]->id);

			//Add to DB
			Serie::create(array(
								'imdbId' 		=> $show->imdbId, 
								'tvdb_id' 		=> $show->id, 
								'name'			=> $show->name, 
								'poster'		=> $show->poster, 
								'status'		=> $show->status, 
								'genres'		=> $show->genres, 
								'overview'	 	=> $show->overview,
								'actors'		=> $show->actors,
								'firstAired'	=> $show->firstAired, 
								'airsDayOfWeek'	=> $show->airsDayOfWeek, 
								'airsTime'		=> $show->airsTime, 
								'rating'		=> $show->rating, 
								'runtime'		=> $show->runtime, 
								'network'		=> $show->network,
							));
            
            $show = Serie::where('name', '=', $name)->first();
		}
		
		return View::make('show')->with(array('show' => $show));
	}
}
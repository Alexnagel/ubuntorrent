<?php
use Moinax\TvDb\Client;

class ShowController extends BaseController {
	
	public function getShow($name)
	{
		$show = $this->getShowModel($name);
		
		return View::make('show')->with(array('show' => $show));
	}

	private function getShowModel($name)
	{
		$show = Serie::where('name', '=', $name)->first();

		if(count($show) == 0)
		{
			$tvdb = new Client('http://thetvdb.com', '6FCFFB713EA09F9E');

			$serverTime = $tvdb->getServerTime();
			// Search for a show
			$data = $tvdb->getSeries($name);
			// Use the first show found
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
		return $show;
	}

	public function getEpisode($show, $season, $episodeNum)
	{
		$episode = Episode::where('show_name', '=', $show)->where('season', '=', $season)->where('episode', '=', $episodeNum)->first();

		if (count($episode) == 0)
		{
			$tvdb = new Client('http://thetvdb.com', '6FCFFB713EA09F9E');

			$serverTime = $tvdb->getServerTime();
			// get series tvdb id
			$seriesId = Serie::where('name', '=', $show)->pluck('tvdb_id');
			if ($seriesId == null)
				$seriesId = $this->getShowModel($show)->tvdb_id;
			// Search for a episode
			$tvdbEpisode = $tvdb->getEpisode($seriesId, $season, $episodeNum);

			// Add to DB
			Episode::create(array(
									'show_name' => $show,
									'episode_title' => $tvdbEpisode->name, 
									'season' => $season, 
									'episode' => $episodeNum, 
									'firstAired' => $tvdbEpisode->firstAired, 
									'imdbId' => $tvdbEpisode->imdbId,
									'overview' => $tvdbEpisode->overview,
									'guest_stars' => (string)implode(", ", $tvdbEpisode->guestStars)
				));
			$episode = Episode::where('show_name', '=', $show)->where('season', '=', $season)->where('episode', '=', $episodeNum)->first();
		}

		return View::make('episode')->with(array('episode' => $episode));
	}
}
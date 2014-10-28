<?php

class Episode extends Eloquent {

	protected $table = "tvdb_episodes_cache";

	protected $fillable = array(
							'show_name', 
							'episode_title', 
							'season', 
							'episode', 
							'firstAired', 
							'imdbId',
							'overview',
							'guest_stars'
						);

	public function getDates()
	{
	    return array('created_at', 'updated_at', 'firstAired');
	}
}
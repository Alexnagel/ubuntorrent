<?php

class Serie extends Eloquent {

	protected $table = "tvdb_series_cache";

	protected $fillable = array(
							'imdbId', 
							'tvdb_id', 
							'name', 
							'poster', 
							'status', 
							'genres', 
							'overview', 
							'actors', 
							'firstAired', 
							'airsDayOfWeek', 
							'airsTime', 
							'rating', 
							'runtime', 
							'network'
						);

	public function getDates()
	{
	    return array('created_at', 'updated_at', 'firstAired');
	}
}
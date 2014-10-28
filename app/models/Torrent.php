<?php

class Torrent extends Eloquent {

	protected $table = "added_torrents";

	protected $fillable = array(
							'show_name', 
							'episode_title', 
							'season', 
							'episode', 
							'date_added', 
							'pub_date',
							'magnet', 
							'processed'
						);

	public function getDates()
	{
	    return array('created_at', 'updated_at', 'date_added', 'pub_date');
	}
}
<?php 
use Transmission\Client;
use Transmission\Transmission;

class TorrentHandler {
	
	public function checkNewShows()
	{
		$torrentRepository 	= App::make('RepositoryInterface');

		$torrents 			= $torrentRepository->getShows(); 

		$client 			= new Client();
		$client->authenticate('transmission', 'Alex');
		
		$transmission_url 	= Config::get('ubuntorrent.transmission.url');
		$transmission 		= new Transmission($transmission_url);

		$transmission->setClient($client);
		$session 			= $transmission->getSession();

		foreach($torrents as $torrent)
		{
			print_r($torrent);
			$session->setDownloadDir('/seagate/Series/' . $torrent['name'] . '/' . $torrent['name'] . '.' . $torrent['season']);
			$session->save();
			$item  = $transmission->add($torrent['link']);
			$item->start(true);
		}
	}
}
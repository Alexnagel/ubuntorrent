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

		print_r($torrents);
		foreach($torrents as $torrent)
		{
			$session->setDownloadDir('/seagate/Series/' . $torrent['name'] . '/' . $torrent['name'] . '.' . $torrent['season']);
			$item  = $transmission->add($torrent['link']);
			$item->start(true);
		}
	}
}
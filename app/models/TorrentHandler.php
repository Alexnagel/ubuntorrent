<?php 
use Transmission\Client;
use Transmission\Transmission;

class TorrentHandler {
	
	public function checkNewShows()
	{
		$torrentRepository 	= App::make('RepositoryInterface');
		$torrentRepository->getShows(); 
	}

	public function addNewTorrents()
	{
		$client 			= new Client();
		$client->authenticate('transmission', 'Alex');
		
		$transmission_url 	= Config::get('ubuntorrent.transmission.url');
		$transmission 		= new Transmission($transmission_url);

		$transmission->setClient($client);
		$session 			= $transmission->getSession();

		$torrents = Torrent::where('processed', '=', false)->get();

		foreach ($torrents as $torrent) 
		{
			try
			{
				$safeShowName  = str_replace(' ', '.', $torrent->show_name);
				$seasonCorrect = sprintf("%02s", $torrent->season);
				$session->setDownloadDir('/seagate/Series/' . $safeShowName . '/' . $safeShowName . '.S' . $seasonCorrect);
				$session->save();

				$item  = $transmission->add($torrent->magnet);
				$item->start(true);

				$torrent->processed = true;
				$torrent->save();
			}
			catch(\RuntimeException $e)
			{
				Log::warning('[TorrentHandler] Torrent: ' . $torrent['name'] . ' could not be added');
			}	
		}
	}
}

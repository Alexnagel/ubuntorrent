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

		echo Setting::where('key', '=', 'last_torrent_check')->pluck('value') . ' == ' . count($torrents);

		if(Setting::where('key', '=', 'torrents_added')->pluck('value') != count($torrents))
		{
			foreach($torrents as $torrent)
			{
				$session->setDownloadDir('/seagate/Series/' . $torrent['name'] . '/' . $torrent['name'] . '.S' . $torrent['season']);
				$session->save();

				$item  = $transmission->add($torrent['link']);
				$item->start(true);
				
				$title = "Season " . $torrent['season'] . " Episode " . $torrent['episode'];
				RecentTorrent::create(array('name' => $torrent['name'], 'title' => $title, 'date_added' => date('d-m-Y')));
			}
			Setting::where('key', '=', 'torrents_added')->update(array('value' => count($torrents)));
			Setting::where('key', '=', 'last_torrent_check')->update(array('value' => date('d-m-Y')));
		}
	}
}

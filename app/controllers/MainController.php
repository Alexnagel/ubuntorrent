<?php

class MainController extends BaseController
{
	public function makeIndex()
	{
		$schedule = new Schedule();
		$recently_added = Torrent::where('processed', '=', true)->take(10)->get();

		return View::make('index', array('schedule' => $schedule->getSchedule(), 'recently_added' => $recently_added));
	}

	public function checkNewShows()
	{
		$torrentHandler = new TorrentHandler();
		$torrentHandler->checkNewShows();
		$torrentHandler->addNewTorrents();
	}

	public function search($search)
	{
		$searchController = new SearchController();
		$results = $searchController->search($search);

		return View::make('search', array('results' => $results, 'searchterm' => $search));
	}

	public function addTorrent($magnet, $dirname)
	{
		$torrents 			= $torrentRepository->getShows(); 

		$client 			= new Client();
		$client->authenticate('transmission', 'Alex');
		
		$transmission_url 	= Config::get('ubuntorrent.transmission.url');
		$transmission 		= new Transmission($transmission_url);

		$transmission->setClient($client);
		$session 			= $transmission->getSession();

		$session->setDownloadDir('/seagate/Series/'. $dirname);
		$session->save();

		$item  = $transmission->add($magnet);
		$item->start(true);
	}
}
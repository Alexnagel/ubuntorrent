<?php

class MainController extends BaseController
{
	public function makeIndex()
	{
		$schedule = new Schedule();

		return View::make('index', array('schedule' => $schedule->getSchedule()));
	}

	public function checkNewShows()
	{
		$torrentHandler = new TorrentHandler();
		$torrentHandler->checkNewShows();
	}

	public function search($search)
	{
		$searchController = new SearchController();
		$results = $searchController->search($search);

		return View::make('search', array('results' => $results));
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
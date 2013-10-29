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
}
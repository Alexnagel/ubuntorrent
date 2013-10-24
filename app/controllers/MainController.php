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
}
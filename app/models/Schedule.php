<?php

class Schedule
{
	private $Repository;

	public function __construct()
	{
		$this->Repository = App::make('RepositoryInterface');
	}

	public function getSchedule()
	{
		return $this->Repository->getSchedule();
	}
}
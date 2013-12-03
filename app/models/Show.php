<?php

class Show {
	public $name;
	public $image;
	public $status;
	public $genre;
	public $summary;
	public $cast;
	public $release_date;
	public $air_date;

	public function __construct($values)
	{
		$this->name			= $values[0];
		$this->image 		= $values[1];
		$this->status		= $values[2];
		$this->statusLabel 	= $this->setStatusLabel($values[2]);
		$this->genre		= $values[3];
		$this->summary		= $values[4];
		$this->cast			= $values[5];
		$this->release_date	= $values[6];
		$this->air_date		= $values[7];
	}

	private function setStatusLabel($status)
	{
		switch (strtolower($status)) {
			case 'continuing': 	$statusClass = "success"; 	break;
			case 'ended': 		$statusClass = "danger"; 	break;
			default: 			$statusClass = "warning"; 	break;
		}
		return $statusClass;
	}
}
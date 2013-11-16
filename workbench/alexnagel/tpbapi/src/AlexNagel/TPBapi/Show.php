<?php namespace AlexNagel\TPBApi;

class Show extends Torrent {
	/**
	 * The season of the show
	 *
	 * @var int $Season
	 */
	protected $Season;
	/**
	 * The episode of the show
	 *
	 * @var int $Episode
	 */
	protected $Episode;

	/**
	 * Torrent constructor for a Show
	 *	Construct from an SimpleXMLElement
	 *
	 * @param \SimpleXMLElement $data The Element with data created by the Crawler
	 * @return void
	 */
	public function __construct($data)
	{
		parent::__construct($data);

		$this->Name 	= $data->name;
		$this->Season 	= $data->season;
		$this->Episode 	= $data->episode;
	}
}
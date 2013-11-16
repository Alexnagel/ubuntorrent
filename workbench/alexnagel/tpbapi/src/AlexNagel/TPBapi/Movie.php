<?php namespace AlexNagel\TPBApi;

class Movie extends Torrent {
	
	/**
	 * The Movie title
	 *
	 * @var string $title
	 */
	protected $Title;

	/**
	 * Torrent constructor for a Movie
	 *	Construct from an SimpleXMLElement
	 *
	 * @param \SimpleXMLElement $data The Element with data created by the Crawler
	 * @return void
	 */
	public function __construct($data)
	{
		parent::__construct($data);

		$this->Title = $data->title;
	}
}
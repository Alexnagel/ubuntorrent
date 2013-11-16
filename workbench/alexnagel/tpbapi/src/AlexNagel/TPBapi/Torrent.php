<?php namespace AlexNagel\TPBApi;

class Torrent {
	
	/**
	 * The name of the torrent item
	 * 	If type is Movie it's the movie title
	 *	If type is Show it's the show title
	 *
	 * @var string $name
	 */
	protected $Name;

	/**
	 * The original torrent name
	 *	No regex done on name, exact copy
	 *
	 * @var string $Original_Name;
	 */
	protected $Original_Name;

	/**
 	 * The amount of seeders for the torrent
 	 *
 	 * @var int $Seeders
 	 */
	protected $Seeders;

	/**
 	 * The amount of leechers for the torrent
 	 *
 	 * @var int $Leechers
 	 */
	protected $Leechers

	/**
	 * The magnet link for the torrent
	 *
	 * @var string $Magnet
	 */
	protected $Magnet;

	/**
	 * Torrent constructor
	 *	Construct from an SimpleXMLElement
	 *
	 * @param \SimpleXMLElement $data The Element with data created by the Crawler
	 * @return void
	 */
	public function __construct($data)
	{
		$this->Original_Name 	= $data->original_name;
		$this->Seeders 			= $data->seeders;
		$this->Leechers 		= $data->leechers;
		$this->Magnet 			= $data->magnet;
	}
}
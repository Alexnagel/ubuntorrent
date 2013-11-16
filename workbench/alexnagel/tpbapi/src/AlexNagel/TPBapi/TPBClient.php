<?php namespace AlexNagel\TPBApi;

use Guzzle\Http\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;

class TPBClient {
	
	const BASE_URL 	= 'http://thepiratebay.sx'; 
	const GET 		= 'get';
	const FORMAT 	= 'xml';

	/**
	 * The base URL to use for ThePirateBay
	 *
	 * @var string $baseUrl
	 */
	protected $baseUrl = '';

	/**
	 * The format to return the data in 
	 *
	 * @var string $format
	 */
	protected $formatData = '';

	protected $params = array(
							'orderBy' 	=> 7,
							'page'		=> 0,
							'category'	=> 0,
						);

	/**
	 * The Client constructor
	 *
	 * @param string $baseUrl The base domain for TPB without trailing slash
	 * @param string $formatData The format in which the Client wil return, XML or JSON
	 * @return void
	 */
	public function __construct($baseUrl = self::BASE_URL)
	{
		$this->baseUrl 		= $baseUrl;
	}

	public function searchShows($term, $sParams = array(), $formatData = self::FORMAT)
	{
		$this->formatData 	= $formatData;


	}

	public function fetch($sParams = array(), $type = 'shows')
	{
		$type = ucfirst($type);
		$searchUrl 	= $this->baseUrl . '/s/'
		$client 	= new GuzzleClient($searchUrl);

		// create a request
		$request 	= $client->get($this->getURL($term, $sParams));
		$response 	= $request->send();

		$crawler 	= new Crawler($response->getBody(true));

		$rows 		= $crawler->filterXPath('//table[@id="searchResult"]/tr');
		$results	= array();

		if(iterator_count($rows) >= 1)
		{
			foreach ($rows as $key => $row) 
			{
				$row_crawler = new Crawler($row);

				$name 		= $row_crawler->filter('a.detLink')->text();
				$magnet 	= $row_crawler->filterXPath('//a[@title="Download this torrent using magnet"]')->attr('href');
				$seeders	= $row_crawler->filterXPath('//td[3]')->text();

				$desc 		= $row_crawler->filter('font.detDesc')->text();

	            preg_match('#Uploaded\s+(?P<time>[^,]+),\s+Size\s+(?P<size>[^,]+),\s+ULed\s+by\s+(?P<user>[^\s]+)#S', $desc, $descParsed);
	            $timestr = preg_replace('/\xC2\xA0/', '.', $descParsed['time']);
	            $dateStr = $this->getDate($timestr);

	            $pregMatches = $this->'regex' . $type($name);

				$swap_key 	= $preg_name[2] . "." . $preg_name[3];
				foreach ($results as $item_key => $item) 
				{
					if($item_key == $swap_key)
	                {
	                    if((int)$item['seeders'] > (int)$seeders)
	                    {
	                        continue 2;
	                    }
	                    $swap_key = $item_key;
	                }
				}

				$season 	= 'Season ' . $preg_name[2];
				$episode 	= "Episode " . $preg_name[3]; 

				$item_data = array(
								'name' 		=> str_replace(".", " ", $preg_name[1]), 
								'season' 	=> $season, 
								'episode' 	=> $episode, 
								'magnet' 	=> $magnet, 
								"seeders" 	=> $seeders, 
								'date' 		=> $dateStr
							);
	            $results[$swap_key] = $item_data;
			}
		}
		else
		{
			return false;
		}
	}

	private function createURL($term, $params = array(), $type = 'show')
	{
		if(count($params) == 0)
			$params = $this->params;
		else
		{
			$params = array_replace($this->params, $params);
		}

		if(preg_match("/\s720p$/i", $term) == false)
		{
			$term .= " 720p";
		}
		if(preg_match("/\s1080p$/i", $term) == false)
		{
			$term .= " 1080p";
		}

		$term_encoded = urlencode($term);
		$params['q'] = $term_encoded;

		$url = '';
		$first = true;
		foreach($params as $key => $value)
		{
			if($first)
			{
				$url .= "?" . $key . "=" . $value;
				$first = false;
			}
			$url .= "&" . $key . "=" . $value;
		}

		return $url;
	}

	private function getDate($timestr)
	{
		if(preg_match("/(t-day|y-day)(.*)/i", $timestr, $matches))
        {
        	if(strtolower($matches[1]) == 't-day')
        	{
        		$timestr = date('d-m') . $matches[2];
        	}
        	else if(strtolower($matches[1]) == 'y-day')
        	{
        		$timestr = date('d-m', strtotime('-1 day')) . $matches[2];
        	}
        }

        $len = strlen($timestr);
		if($timestr[$len-3] == ':')
		{
		    $date 	= date_create_from_format('m-d.G:i', $timestr);
		}
		else
		{
		    $date 	= date_create_from_format('m-d.Y', $timestr);
		}
        $dateStr 		= date_format($date, 'd-m-Y');
        
        return $dateStr;
	}

	/**
	 *
	 *
	 * Thanks to Cas Nouwens
	 */
	private function regexShows($term)
	{
		if(preg_match("/(?:season\s|s)(\d{1,2})(?:e|\sepisode\s)(\d{1,2})/i", $term, $SEmatches))
		{

		}else if(preg_match("/(?:complete)*.?(?:season.?)(\d{1,2}.?\d?|\d.?\d?)*/i", $term, $SEmatches))
		{

		}


	}
}





























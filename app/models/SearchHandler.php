<?php
use Guzzle\Http\Client;
use Symfony\Component\DomCrawler\Crawler;
 
class SearchHandler {
	protected $crawler;

	public function search($term, $type = 'tv-hd')
	{
		// create http client instance
		$client = new Client('http://thepiratebay.se/search/');
 		
		$exploded_term = str_replace(" ", ".*", $term);

		// Add to the search term
		$term = urlencode($term);

		// Search catergory number from string
		$category = 0;
		switch ($type) {
			case 'tv':
				$category = 205;
				break;
			case 'movie':
				$category = 201;
			case 'movie-hd':
				$category = 207;
			case 'tv-hd':
			default:
				$category = 208;
				break;
		}

		// Add should start on page 0 
		// Add should order by most seeds
		// Add search category
		$params = array("page" => 0, "orderBy" => 7, "category" => $category);
		foreach($params as $key => $value)
		{
			$term .= "/" . $value;
		}

		// create a request
		$request = $client->get($term);
		// send request
		$response = $request->send();
		// get the request body
		$result = $response->getBody();

		// create new crawler with dom content
		$this->crawler = new Crawler($response->getBody(true));

		switch ($type) 
		{
			case 'tv-hd':
			case 'tv'	: return $this->searchTV($exploded_term);break;
			case 'movie-hd':
			case 'movie': return $this->searchMovie($rows, $exploded_term);break;
			default 	: return $this->searchTV($exploded_term);break; 
		}
	}
	
	public function searchTV($term)
	{
		$rows = $this->crawler->filterXPath('//table[@id="searchResult"]/tr');

		$results = array();
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

	            preg_match("/(". $term . ").*S(\d{1,2})E(\d{2})?(.+sub)?/i", $name, $preg_name);
				if(empty($preg_name) || count($preg_name) == 5)
					continue;

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
		return $results;
	}

	public function searchMovie($term, $data)
	{

	}

	private function getDate($timestr)
	{
		if(preg_match("/(vandaag|gisteren|t-day|today|y-day|yesterday|mins)(.*)/i", $timestr, $matches))
        {
        	if(strtolower($matches[1]) == 't-day' | strtolower($matches[1]) == 'vandaag' | strtolower($matches[1]) == 'today' | strtolower($matches[1]) == 'mins')
        	{
        		$timestr = date('d-m') . $matches[2];
        	}
        	else if(strtolower($matches[1]) == 'y-day' | strtolower($matches[1]) == 'gisteren' | strtolower($matches[1]) == 'yesterday')
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
        $dateStr = date_format($date, 'd-m-Y');
        
        return $dateStr;
	}

}
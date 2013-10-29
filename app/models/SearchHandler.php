<?php
use Guzzle\Http\Client;
use Symfony\Component\DomCrawler\Crawler;
 
class SearchHandler {
	protected $crawler;

	public function search($term, $type = 'tv')
	{
		// create http client instance
		$client = new Client('http://malaysiabay.org/s/');
 		
		$exploded_term = str_replace(" ", ".*", $term);

		if(preg_match("/\s720p$/i", $term) == false)
		{
			$term .= " 720p";
		}
		$term = "?q=" . urlencode($term);

		$params = array("page" => 0, "orderBy" => 7);
		foreach($params as $key => $value)
		{
			$term .= "&" . $key . "=" . $value;
		}

		// create a request
		$request = $client->get($term);
		$response = $request->send();
		$result = $response->getBody();

		$this->crawler = new Crawler($response->getBody(true));

		switch ($type) 
		{
			case 'tv'	: return $this->searchTV($exploded_term);break;
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
		if(preg_match("/(vandaag|t-day|y-day)(.*)/i", $timestr, $matches))
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

}
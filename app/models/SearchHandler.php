<?php
use Guzzle\Http\Client;
use Symfony\Component\DomCrawler\Crawler;
 
class SearchHandler {

	public function search($term)
	{
		// create http client instance
		$client = new Client('http://malaysiabay.org/s/');
 		
		$exploded_term = str_replace(" ", ".*", $term);

		if(preg_match("/.720p/", $term) === false)
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

		$crawler = new Crawler($response->getBody(true));

		$rows = $crawler->filterXPath('//table[@id="searchResult"]/tr');

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
	            $date 		= date('Y') . '-' . $descParsed['time'] . ':00';

	            preg_match("/(". $exploded_term . ").*(S|season\s?)(\d{1,2})E?(\d{2})?.?(720p|1080p)?/i", $name, $preg_name);
				if(empty($preg_name))
				{
					continue;
				}

				if(count($preg_name) > 4)
				{
					$swap_key 	= $preg_name[3] . "." . $preg_name[4];
				}
				else
				{
					$swap_key 	= $preg_name[3] . ".";
				}
				

				foreach ($results as $item_key => $item) 
				{
					if($item_key == $swap_key)
	                {
	                    if((int)$item['seeders'] > (int)$seeders)
	                    {
	                        continue ;
	                    }
	                    $swap_key = $item_key;
	                }
				}

				$season = $preg_name[3];

				if(strtolower(trim($preg_name[2])) == 's' | strtolower(trim($preg_name[2])) == 'season')
				{
					$season = 'season ' . $preg_name[3];
				}else
					echo $preg_name[2];

				$episode = "none";
				if(count($preg_name) > 4)
				{
					$episode = "episode " . $preg_name[4]; 
				}

				$item_data = array('name' => str_replace(".", " ", $preg_name[1]), 'season' => $season, 'episode' => $episode, 'magnet' => $magnet, "seeders" => $seeders, 'date' => $date);
	            $results[$swap_key] = $item_data;
			}

			$count = count($results);
			$v = true;
			for($i = 0; $i < $count && ($v == True); $i++)
			{
				$v = false;
				for ($j = 0; $j < ($count - $i); $j++)
     			{

     			}
			}
		}
		return $results;
	}
		
}
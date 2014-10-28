<?php
	
class SearchController {
	
	public function search($searchStr)
	{
		$searchResults = array();

		if(Cache::has(strtolower($searchStr)) === false)
		{
			$searchHandler = new SearchHandler();
			$searchResults = $searchHandler->search($searchStr);
			Cache::add(strtolower($searchStr), $searchResults, 21600);
		}
		else
		{
			$searchResults = Cache::get(strtolower($searchStr));
		}

		return $searchResults;
	}
}
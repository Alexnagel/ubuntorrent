<?php
	
class SearchController {
	
	public function search($searchStr)
	{
		$searchHandler = new SearchHandler();
		return $searchHandler->search($searchStr);
	}
}
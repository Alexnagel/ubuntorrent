<?php

class RSSRepository implements RepositoryInterface
{
	public function getSchedule()
	{
		$feed_url 	= file_get_contents(Config::get('ubuntorrent.RSS.schedule_feed'));
		$feed 		= simplexml_load_string($feed_url);

		$feed_items = $feed->xpath('channel/item');
		

		$schedule 		= [];
		$worked_days 	= [];
		foreach($feed_items as $key => $item)
		{
			$schedule_day	= [];
			$day_items 		= [];

			$day 			= $item->pubDate;
			$day_strtime 	= strtotime($item->pubDate);

			if(in_array($day_strtime, $worked_days))
				continue;

			$worked_days[] 	= $day_strtime;

			foreach($feed_items as $c_key => $c_item)
			{	
				$item_strtime = strtotime($c_item->pubDate);

				if($item_strtime == $day_strtime)
				{
					$day_items[] = $this->regexScheduleItem($c_item->description);
					unset($feed_items[$c_key]);
				}else
				{
					continue;
				}
			}

			$string_day 				= date('d-m : l', strtotime($day));
			$schedule_day["DayString"] 	= $string_day;
			$schedule_day['Items'] 		= $day_items;
			$schedule[] 				= $schedule_day;
		}

		return $schedule;
	}

	public function getShows()
	{
		$last_check_day	= Config::get('ubuntorrent.torrent.last_check');
		$current_day 	= date('d-m-Y');
		$feed_url 		= file_get_contents(Config::get('ubuntorrent.RSS.personal_feed'));
		$feed 			= simplexml_load_string($feed_url);

		$feed_items 	= $feed->xpath('channel/item');

		$torrents = [];
		foreach($feed_items as $item)
		{
			$item_date = date('d-m-Y', strtotime($item->pubDate));
			if($item_date <= $current_day && $item_date >= $last_check_day)
			{
				$item_arr 			= $this->regexTorrentItem($item->title);
				$item_arr['link']	= (string)$item->link;
				$torrents[] 		= $item_arr;
			}
		}
		return $torrents;
	}

	private function regexScheduleItem($text)
	{
		$regex 	= "/(.*?)\s?(\d{1}|\d{2})x(\d{2})\s(.*)\sairs(.*)/";

		preg_match($regex, $text, $matches);

		$name 			= $matches[1];
		if(strlen($matches[2]) == 1)
		{
			$season = "0" . $matches[2];
		}else{
			$season = $matches[2];
		}
		$episode_name 	= "Season " . $season . ", Episode " . $matches[3] . " : " . $matches[4];

		return array("Name" => $name, "EpisodeName" => $episode_name);
	}

	private function regexTorrentItem($text)
	{
		$regex = "/(.*?)\s?(\d{1}|\d{2})x(\d{2})\s(.*)\s(.*)/";

		preg_match($regex, $text, $matches);

		if(strlen($matches[2]) == 1)
		{
			$season = "0" . $matches[2];
		}else{
			$season = $matches[2];
		}

		$name 		= str_replace(' ', '.', $matches[1]);
		$episode 	= $matches[3];

		return ['name' => $name, 'season' => $season, 'episode' => $episode];
	}
}

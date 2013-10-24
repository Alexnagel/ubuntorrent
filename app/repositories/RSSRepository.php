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
		$current_day 	= date('d-m-Y', strtotime('18-10-2013'));
		$feed_url 		= file_get_contents(Config::get('ubuntorrent.RSS.personal_feed'));
		$feed 			= simplexml_load_string($feed_url);

		$feed_items 	= $feed->xpath('channel/item');

		$torrents = [];
		foreach($feed_items as $item)
		{
			$item_date = date('d-m-Y', strtotime($item->pubDate));
			if($current_day == $item_date)
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
		$regex 	= "/(.*?)\s?(\d{1})x(\d{2})\s(.*)\sairs(.*)/";

		preg_match($regex, $text, $matches);

		$name 			= $matches[1];
		$episode_name 	= "Season " . $matches[2] . ", Episode " . $matches[3] . " : " . $matches[4];

		return array("Name" => $name, "EpisodeName" => $episode_name);
	}

	private function regexTorrentItem($text)
	{
		$regex = "/(.*?)\s?(\d{1})x(\d{2})\s(.*)\s(.*)/";

		preg_match($regex, $text, $matches);

		$name 		= str_replace(' ', '.', $matches[1]);
		$season 	= $matches[2];
		$episode 	= $matches[3];

		return ['name' => $name, 'season' => $season, 'episode' => $episode];
	}
}
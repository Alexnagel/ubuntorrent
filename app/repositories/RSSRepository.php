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
		$last_added 	= Torrent::orderBy('created_at')->first();
		$feed_url 		= file_get_contents(Config::get('ubuntorrent.RSS.personal_feed'));
		$feed 			= simplexml_load_string($feed_url);

		$feed_items 	= $feed->xpath('channel/item');

		//dd($last_added);
		foreach($feed_items as $item)
		{
			$item_date = strtotime($item->pubDate);

			if($last_added != null && $item_date <= $last_added->pub_date->timestamp)
				break;

			// set the torrent data
			$item_arr 				= $this->regexTorrentItem($item->title);
			$item_arr['date_added'] = \Carbon\Carbon::now();
			$item_arr['pub_date'] 	= \Carbon\Carbon::createFromTimeStamp($item_date);
			$item_arr['magnet']		= (string)$item->link;
			$item_arr['processed']	= false;
			
			// Add to the added torrents table
			Torrent::create($item_arr);
		}
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
		$regex = "/(.*?)\s?(\d{1,2})x(\d{2})\s(.*)\s/";

		preg_match($regex, $text, $matches);

		if(strlen($matches[2]) == 1)
		{
			$season = "0" . $matches[2];
		}else{
			$season = $matches[2];
		}

		$name 			= $matches[1];
		$episode 		= $matches[3];
		$episode_title 	= $matches[4];

		return ['show_name' => $name, 'episode_title' => $episode_title, 'season' => $season, 'episode' => $episode];
	}
}

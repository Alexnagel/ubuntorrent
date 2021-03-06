<?php

namespace Bluelyte\IMDB\Client;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

class Client extends \Goutte\Client
{
    protected $baseUrl = 'http://www.imdb.com';

    /**
     * Returns information for a specified TV show.
     *
     * @param string $id ID as contained in the URL for the TV show of the
     *        form http://www.imdb.com/title/ID/
     * @return array Associative array of metadata about the TV show
     */
    public function getShowInfo($id)
    {
        $showInfo = array();

        $crawler = $this->request('GET', $this->baseUrl . '/title/' . $id . '/');
        $showInfo['title'] = $crawler->filterXPath('//h1/span[@itemprop="name"]')->text();
        $showInfo['trialer'] = $crawler->filterXPath('//a[@itemprop="trailer"]/href')->text();

        $nextEpisode = $crawler->filterXPath('//div[@class="next-episode"]/div');
        if (count($nextEpisode) && preg_match('/S(?P<season>[0-9]+), Ep(?P<episode>[0-9]+)/', $nextEpisode->text(), $match)) {
            $showInfo['latestSeason'] = $match['season'];
            $showInfo['latestEpisode'] = $match['episode'];
        } else {
            $showInfo['latestSeason'] = $crawler->filterXPath('//div[@id="titleTVSeries"]//h4[text()="Season:"]/../span[1]/a[1]')->text();
            $today = time(0, 0, 0);
            do {
                $seasonEpisodes = $this->getSeasonEpisodes($id, $showInfo['latestSeason']);
                $currentEpisodes = array_filter($seasonEpisodes, function($episode) use ($today) {
                    return strtotime($episode['airdate']) < $today;
                });
                if ($currentEpisodes) {
                    $showInfo['latestEpisode'] = max(array_keys($currentEpisodes));
                    break;
                } elseif ($showInfo['latestSeason'] > 1) {
                    $showInfo['latestSeason']--;
                } else {
                    $showInfo['latestEpisode'] = 1;
                }
            } while ($showInfo['latestSeason'] >= 1);
        }

        return $showInfo;
    }

    /**
     * Returns a list of episodes for a specified season of a TV show.
     *
     * @param string $id ID as contained in the URL for the TV show of the
     *        form http://www.imdb.com/title/ID/
     * @param string $season Season for which to return episodes
     * @return array Associative array indexed by episode number of
     *         associative arrays each containing data for an individual
     *         episode within the season
     */
    public function getSeasonEpisodes($id, $season)
    {
        $crawler = $this->request('GET', $this->baseUrl . '/title/' . $id . '/episodes?season=' . $season);
        $divs = $crawler->filterXPath('//div[contains(@class, "eplist")]/div[contains(@class, "list_item")]/div[@class="info"]');
        $episodes = array();
        foreach ($divs as $div) {
            $div = new Crawler($div);
            $number = $div->filterXPath('//meta[@itemprop="episodeNumber"]')->attr('content');
            $title = $div->filterXPath('//strong/a[@itemprop="name"]')->text();
            $airdate = $div->filterXPath('//div[@class="airdate"]')->text();
            $description = $div->filterXPath('//div[@class="item_description"]')->text();
            $episodes[$number] = array_map('trim', array(
                'title' => $title,
                'airdate' => $airdate,
                'description' => $description,
            ));
        }
        return $episodes;
    }
}

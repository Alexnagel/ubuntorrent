<?php

namespace Bluelyte\TPB\Client;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

class Client extends \Goutte\Client
{
    protected $baseUrl = 'http://thepiratebay.se';

    protected $categories = array(
        'audio',
        'video',
        'apps',
        'games',
        'other',
    );

    protected function filterRequest(Request $request)
    {
        $request = new Request(
            str_replace(' ', '%20', $request->getUri()),
            $request->getMethod(),
            $request->getParameters(),
            $request->getFiles(),
            $request->getCookies(),
            $request->getServer(),
            $request->getContent()
        );
        //var_dump($request);
        return $request;
    }

    protected function filterResponse($response)
    {
        //var_dump($response);
        return $response;
    }

    public function search($term, $page = 0, $category = 'all', $sort = true)
    {
        $categories = array_filter($this->categories, function($value) use ($category) {
            return ($category === 'all' || $value === $category);
        });
        $params = array('page' => $page - 1, 'orderby' => 7);
        foreach ($categories as $param) {
            $params[$param] = 1;
        }

        $searchStr = "/s/?q=" . $term . "&page=0&orderBy=7";
        // Get the search form
        $crawler = $this->request('GET', $this->baseUrl . $searchStr);

        // Sort the search results to get the one with the most seeders
        if ($sort) {
            $link = $crawler->filterXPath('//table[@id="searchResult"]//a[text()="SE"]');
            if (count($link)) {
                $crawler = $this->click($link->link());
            } else {
                return array(
                    'start' => 0,
                    'end' => 0,
                    'total' => 0,
                    'results' => array(),
                );
            }
        }



        // Get position within the entire result set
        // Displaying hits from 30 to 60 (approx 1000 found)
        $h2 = $crawler->filterXPath('//h2[contains(., "Displaying hits")]');
        $position = array();
        preg_match('/Displaying hits from (?P<start>[0-9,]+) to (?P<end>[0-9,]+) [^0-9]+(?P<total>[0-9,]+)/', $h2->text(), $position);

        // Parse the data from the table
        $rows = $crawler->filterXPath('//table[@id="searchResult"]/tr');
        $results = array();
        $episodeArr = array();
        foreach ($rows as $row) {
            $rowData = array();
            $row = new Crawler($row);

            $cell = $row->filterXPath('//td[2]');
            $link = $cell->filterXPath('//a[@class="detLink"]');
            $rowData['name'] = $link->text();
            $rowData['magnetLink'] = $cell->filterXPath('//a[@title="Download this torrent using magnet"]')->attr('href');
            $rowData['seeders'] = $row->filterXPath('//td[3]')->text();
            
            $name = preg_match("/.*?(\d{2}).(\d{2}).(.*).(.*)/", $rowData['name']);
            echo "hoi";
            $swap_key = "";
            foreach ($episodeArr as $key => $value) {
                $key_name = str_replace(".", " ", $key);
                if($key_name == $name[1] . " " . $name[2])
                {
                    if($key['seeders'] > $rowData['seeders'])
                    {
                        continue 2;
                    }
                    $swap_key = $key;
                }
            }
            $data =  array('name' => $rowData['name'], 'magnet' => $rowData['magnetLink'], 'seeders' => $rowData['seeders'] );
            $episodeArr[$swap_key] = $data;
        }

        $results = $episodeArr;

        $return = array(
            'start' => $position['start'],
            'end' => $position['end'],
            'total' => $position['total'],
            'results' => $results,
            'data' => $crawler,
            'wtf' =>'d',
        );

        return $return;
    }

    protected function getCategoryLink(Crawler $crawler)
    {
        return array(
            'name' => $crawler->text(),
            'href' => $this->baseUrl . $crawler->attr('href'),
        );
    }
}

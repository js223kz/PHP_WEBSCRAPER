<?php


namespace models;

require_once('models/Scraper.php');

//class that scrapes first site
// value entered by user in StartView
class StartScraper
{
    private $calendarUrl;
    private $movieUrl;
    private $restaurantUrl;

    public function __construct($url)
    {
        $lastCharacter = substr($url, -1);
        if($lastCharacter != '/'){
            $url = $url . '/';
        }
        $this->getUrls($url);
    }

    private function getUrls($url){


        try{
            $scraper = new \models\Scraper();
            $result = $scraper->scrape($url);
            $dom = $scraper->getDOMDocument($result);
        }
        catch(\Exception $e){
            $e->getMessage();
        }

        $items = $dom->query('//ol//li/a');

        foreach($items as $item){
            $trimmed = trim($item->getAttribute('href'), " /");
            $newUrl = $url . $trimmed;
            if($trimmed == 'calendar'){
                $this->calendarUrl = $newUrl;
            }
            if($trimmed == 'cinema'){
                $this->movieUrl = $newUrl;
            }
            if($trimmed == 'dinner'){
                $this->restaurantUrl = $newUrl;
            }
        }
    }

    public function getCalendarUrl(){
        return $this->calendarUrl;
    }
    public function getMovieUrl(){
        return $this->movieUrl;
    }
    public function getRestaurantUrl(){
        return $this->restaurantUrl;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-17
 * Time: 12:45
 */

namespace models;

require_once('models/Scraper.php');
require_once('models/PossibleDate.php');

//class that scrapes restaurants
//for available tables on certain day
//and at certain time
class RestaurantScraper
{
    private $movie;
    private $convertedDay;
    private $convertedTime;
    private $result;

    public function getPossibleBookings($url, $movie){
        $this->movie = $movie;
        $this->convertedDay = $this->convertDay();
        $this->convertedTime = $this->convertTime();
        $availableForBooking = array();

        try{
            $scraper = new \models\Scraper($url);
            $this->result = $scraper->scrape($url);
            $dom = $scraper->getDOMDocument($this->result);

           $checkDays = $dom->query('//p[@class="MsoNormal"]//input[@type="radio"]');

           foreach($checkDays as $day){
               $dayToCheckFor = $day->getAttribute("value")[0].$day->getAttribute("value")[1];
               $startTime =  mb_substr($day->getAttribute("value"),3,2);
               if($dayToCheckFor == $this->convertedDay){
                   if($startTime >= $this->convertedTime + 2){
                       $possibleObject = new \models\PossibleDate($this->movie->getDay(), $day->getAttribute("value"), $this->movie->getName());
                       array_push($availableForBooking, serialize($possibleObject));
                   }
               }
            }
        }
        catch(\Exception $e){
            $e->getMessage();
        }
        return $availableForBooking;
    }

    //convert day to fit restaurantHTML
    private function convertDay(){
        $day = $this->movie->getDay();
        if($day == 'Fredag'){
            return 'fr';
        }
        if($day == 'Lördag'){
            return 'lo';
        }
        if($day == 'Söndag'){
            return 'so';
        }
    }

    //removes :00 from ex 16:00
    private function convertTime(){
        $time = $this->movie->getTime();
        return current(explode(':', $time));
    }

    public function getResult(){
        return $this->result;
    }
}


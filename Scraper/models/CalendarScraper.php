<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-16
 * Time: 12:34
 */

namespace models;

require_once('models/Scraper.php');
require_once('models/Day.php');

//class that scrapes calenders to get possible days to meet
class CalendarScraper
{
    private $days = array();
    public function __construct($url)
    {
        $this->getCalendars($url);
    }

    private function getCalendars($url){

        try{
            $scraper = new \models\Scraper($url);
            $result = $scraper->scrape($url);
            $dom = $scraper->getDOMDocument($result);
        }
        catch(\Exception $e){
            $e->getMessage();
        }

        //get individual calendar urls
        $items = $dom->query('//div[@class = "col s12 center"]/ul//li/a');

        //for every individual calender url create a new day object
        foreach($items as $item){
            $newUrl = $url . '/' .$item->getAttribute('href');
            $this->setCalendars($newUrl);
        }
    }

    /**
     * called in $this->getCalendars()
     * @param $newUrl
     * @throws Exception
     */
    private function setCalendars($newUrl){
        $newDay = new Day();
        try{
            $scraper = new \models\Scraper($newUrl);
            $result = $scraper->scrape($newUrl);
            $dom = $scraper->getDOMDocument($result);
        }
        catch(\Exception $e){
            $e->getMessage();
        }
        $days = $dom->query('//table//thead//tr/th');
        $statuses = $dom->query('//table//tbody//tr/td');

        foreach($days as $day){
            $dayToUpper = strtoupper($day->nodeValue);
            foreach($statuses as $status){
                $statusToUpper = strtoupper($status->nodeValue);
                if($statusToUpper == 'OK'){
                    $newDay->day = $dayToUpper;
                    $newDay->available = $statusToUpper;
                }
            }
        }
        array_push($this->days, $newDay);
    }

    //eliminate elements from array with possible dates
    //that are not unique
    public function getMatchingDays(){

        for($i = 0; $i < count($this->days); $i++){
            if($this->days[$i] == $this->days[$i + 1]){
                unset($this->days[$i]);
            }
        }
        return $this->days;
    }
}

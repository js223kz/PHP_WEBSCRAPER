<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-16
 * Time: 17:07
 */

namespace models;

require_once('models/PossibleDate.php');

//class that scrapes movieSite
class MovieScraper
{
    private $daysToMeet;
    public function __construct($daysToMeet)
    {
        //possible daysToMeet comes from CalenderScraper
        $this->daysToMeet = $daysToMeet;
        $this->changeToSwedish($daysToMeet);
    }

    /**
     * @param $url (passed in from StartController and generated i StartScraper)
     * @return array with possible movies that fits $daysToMeet
     * @throws Exception
     */
    public function getPossibleMovies($url){
        try{
            $scraper = new \models\Scraper($url);
            $result = $scraper->scrape($url);
            $dom = $scraper->getDOMDocument($result);
        }
        catch(\Exception $e){
            $e->getMessage();
        }
        $selectedDays = $this->getDayValue($dom);
        $selectedMovies = $this->getMovieValues($dom);
        $possibleMovies = $this->getMovies($url, $selectedDays, $selectedMovies);

        return $possibleMovies;
    }

    /**
     * called in $this->getPossibleMovies()
     * @param $baseUrl
     * @param $selectedDays
     * @param $selectedMovies
     * @return array array with possible movies that fits $daysToMeet
     * @throws Exception
     */

    private function getMovies($baseUrl, $selectedDays, $selectedMovies){
        $possibleMovies = array();

        //foreach selected day check movies on that day
        //if movie is not sold out ($value["status"] == 0)
        //than create a PossibleDate pobject
        foreach($selectedDays as $key => $day){
            foreach($selectedMovies as $movie){
                $url = $baseUrl . "/check?day=" .$day["value"]. "&movie=" . $movie["value"];
                try{
                    $scraper = new \models\Scraper($url);
                    $result = $scraper->scrape($url);
                    $json = $scraper->getJSON($result);

                    foreach($json as $value){
                        if(!$value["status"] == 0){
                            $possibleDateObject = new \models\PossibleDate($day["day"], $value["time"], $movie["name"]);
                            array_push($possibleMovies, $possibleDateObject);
                        }
                    }
                }
                catch(\Exception $e){
                    $e->getMessage();
                }
            }
        }
        return $possibleMovies;
    }

    private function changeToSwedish(){
        foreach($this->daysToMeet as $possible){
            if($possible->day == 'FRIDAY'){
                $possible->day = 'Fredag';
            }
            if($possible->day == 'SATURDAY'){
                $possible->day = 'Lördag';
            }
            if($possible->day == 'SUNDAY'){
                $possible->day = 'Söndag';
            }
        }
    }

    /**
     * @param $dom domDocument returned from StartScraper
     * @return array with values from dopdown list
     * that fits $daysToMeet
     */
    private function getDayValue($dom){
        $days = $dom->query('//select[@id = "day"]//option');
        $daysValue = array();
        foreach($days as $day){
            foreach($this->daysToMeet as $possible){
                if($possible->day == $day->nodeValue){
                    array_push($daysValue,["value" => $day->getAttribute('value'), "day" => $day->nodeValue] );
                }
            }
        }
        return $daysValue;
    }

    /**
     * @param $dom domDocument returned from StartScraper
     * @return array with values from dopdown list
     */
    private function getMovieValues($dom){
        $movies = $dom->query('//select[@id = "movie"]//option');
        $moviesValue = array();
        foreach($movies as $movie){
            if(!empty($movie->getAttribute('value'))){
                array_push($moviesValue, ["value" => $movie->getAttribute('value'), "name" => $movie->nodeValue]);
            }
        }
        return $moviesValue;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-13
 * Time: 14:52
 */

namespace controllers;

use views\Layout;

require_once('views/StartView.php');
require_once('views/Layout.php');
require_once('models/StartScraper.php');
require_once('models/CalendarScraper.php');
require_once('models/MovieScraper.php');
require_once('models/RestaurantScraper.php');
require_once('models/BookTable.php');

class StartController
{
    private $startView;
    private $parentView;
    private $daysToMeet;
    private $moviesToSee;
    private static $restaurantUrl = "restaurantUrl";

    public function __construct(Layout $commonView)
    {
        $this->parentView = $commonView;
        $this->startView = new \views\StartView();
        $this->checkUserChoice();
    }

    public function checkUserChoice(){
        //if user entered an url and clicked to start scraping
        if($this->startView->startScraping()){
            $scraper = new \models\StartScraper($this->startView->getUrl());
            $restaurantUrl = $scraper->getRestaurantUrl();
            $_SESSION[self::$restaurantUrl] = $restaurantUrl;

            $this->getPossibleDaysToMeet($scraper->getCalendarUrl());
            $this->getPossibleMovies($scraper->getMovieUrl());

            $listView = $this->startView->showPossibleDates($this->moviesToSee);
            $this->parentView->render($listView);
        }
        //if user clicke link with certain movie at a certain time
        else if($this->startView->movieLinkIsClicked()){
            $this->getPossibleRestaurantBookings();
            //session_unset();
        }
        else if($this->startView->userWantsToBookTable()){
            $this->bookATable();
        }
        else{

            $this->parentView->render($this->startView->renderHTML());
        }
    }

    public function getPossibleDaysToMeet($calendarUrl){
        $matchCalendars = new \models\CalendarScraper($calendarUrl);
        $this->daysToMeet = $matchCalendars->getMatchingDays();
    }

    public function getPossibleMovies($movieUrl){
        $movies = new \models\MovieScraper($this->daysToMeet);
        $this->moviesToSee = $movies->getPossibleMovies($movieUrl);
    }

    public function getPossibleRestaurantBookings(){
        $bookings = new \models\RestaurantScraper();
        $possibleTimeToBook = $bookings->getPossibleBookings($_SESSION[self::$restaurantUrl], $this->startView->getMovie());
        $this->parentView->render($this->startView->showChoosenDinnerTime($possibleTimeToBook));
    }

    public function bookATable(){
        $bookTable = new \models\BookTable();
        $bookingForm = $bookTable->getBookingForm($_SESSION[self::$restaurantUrl]);
        $this->parentView->render($this->startView->showBookingForm($bookingForm));
    }
}
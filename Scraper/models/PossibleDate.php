<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-17
 * Time: 11:50
 */

namespace models;

//class to handle possible dates
//wheater it´s a movie or possible
//restaurant booking
class PossibleDate
{
    private $day;
    private $time;
    private $name;

    public function __construct($day, $time, $name)
    {
        $this->day = $day;
        $this->time = $time;
        $this->name = $name;
    }

    public function getDay(){
        return $this->day;
    }
    public function getTime(){
        return $this->time;
    }
    public function getName(){
        return $this->name;
    }
}
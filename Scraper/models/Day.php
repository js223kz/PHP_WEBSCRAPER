<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-16
 * Time: 12:36
 */

namespace models;


class Day
{
    public $day;
    public $available;

    public function setDay($day){
        $this->day = $day;
    }

    public function getDay(){
        return $this->day;
    }

    public function setAvailable($available){
        $this->available = $available;
    }

    public function getAvailable(){
        return $this->available;
    }
}
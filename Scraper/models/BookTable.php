<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-18
 * Time: 09:43
 */

namespace models;


class BookTable
{

    public function getBookingForm($url)
    {
        try {
            $scraper = new \models\Scraper($url);
            $result = $scraper->scrape($url);
            // $dom = $scraper->getDOMDocument($this->result);

        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $result;
    }
}
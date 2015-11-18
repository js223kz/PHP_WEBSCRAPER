<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-13
 * Time: 14:54
 */

namespace views;


require_once('models/PossibleDate.php');

class StartView
{
    private static $submitURL = 'StartView::SubmitURL';
    private static $Url = 'StartView::Url';

    public function renderHTML(){
        return '
        <h1>Kolla möjliga tider</h1>
        <form method="post" action="">
            <fieldset>
                <legend>Ange url:</legend>
                <input type="text" id="' . self::$Url. '"name="' . self::$Url. '"/>
                <input type="submit" name=' . self::$submitURL . ' value="Start"/>
			</fieldset>
		</form>
		';
    }

    public function startScraping(){
        if(isset($_POST[self::$submitURL])){
            return true;
        }
        return false;
    }

    //returns url user entered
    public function getUrl(){
        return $_POST[self::$Url];
    }

   public function movieLinkIsClicked() {
        if (isset($_GET["name"]) ) {
            return true;
        }
        return false;
    }

    //creates new object PossibleDate with values from
    //clicked link values
    public function getMovie(){
       assert(isset($_GET["name"]));
       return new \models\PossibleDate($_GET["day"], $_GET["time"], $_GET["name"]);
    }

    public function showPossibleDates($possibleDates){

        $ret = "<ul>";
        $ret .= "<h1>Dessa filmer kan vi se</h1>";
        foreach($possibleDates as $date){
            $time = $date->getTime();
            $day = $date->getDay();
            $name = $date->getName();
            $ret .= "<li>Filmen $name klockan $time på $day <a name='movielink' href='?name=$name&day=$day&time=$time'>Välj denna och boka bord</a></li>";
            $ret .= "<br>";

        }
        $ret .= "</ul>";
        return $ret;
    }


    public function userWantsToBookTable(){
        if (isset($_GET["booktable"]) ) {
            return true;
        }
        return false;
    }

    public function showChoosenDinnerTime($possibleTimeToBook){

        $ret = "<ul>";
        if($possibleTimeToBook  != null){
            $ret .= "<h1>Vi har lediga platser</h1>";
            foreach($possibleTimeToBook as $possible) {
                $newPossible = unserialize($possible);
                $movieTime = $newPossible->getTime();
                $day = $newPossible->getDay();
                $name = $newPossible->getName();
                $startTime =  mb_substr($movieTime, 3, -2);
                $endTime =  mb_substr($movieTime, -2);
                $ret .= "<li>Det finns ett ledigt bord mellan klockan $startTime - $endTime efter filmen $name på $day <a href='?booktable'>Boka bord</a></li>";
                $ret .= "<br>";
            }

        }else{
            $ret .= "<h1>Tyvärr har vi inga lediga platser. Prova en annan tid!</h1>";
        }
            $ret .= "</ul>";
            return $ret;
    }

    public function showBookingForm($result){
        echo $result;
    }

}
<?php
session_start();
//require_once('ini.php');
require_once('Settings.php');
require_once('controllers/StartController.php');
require_once('views/Layout.php');

$commonView = new \views\Layout();
$start = new \controllers\StartController($commonView);

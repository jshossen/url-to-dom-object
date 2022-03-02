<?php

use Scrapper\App;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

$url = $_GET['url'];
$app = new App($url);
$app->run();
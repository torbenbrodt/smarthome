<?php
date_default_timezone_set("Europe/Berlin");

require_once(__DIR__ . '/Timer.php');

$timer = new Timer(__DIR__ . '/timer.log');
$timer->add($_GET['timer']);


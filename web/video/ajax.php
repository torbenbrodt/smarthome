<?php
require_once(__DIR__ . '/Chromecast.php');

$url = "/chromecast/control?action=" . $_GET['action'];
if (!empty($_GET['duration'])) {
	$url .= '&duration='.$_GET['duration'];
}

$cast = new Chromecast('/tmp/smarthome.ini');
$cast->post($bestHit);


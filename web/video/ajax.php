<?php
require_once(__DIR__ . '/Chromecast.php');
require_once(__DIR__ . '/Otr.php');

if ($_GET['action'] == 'delete') {
	$otr = new Otr('/tmp/smarthome.ini');
	$otr->delete($_GET['epg_id']);
} else {

$url = "/chromecast/control?action=" . $_GET['action'];
if (!empty($_GET['duration'])) {
	$url .= '&duration='.$_GET['duration'];
}

$cast = new Chromecast('/tmp/smarthome.ini');
$cast->post($url);

}

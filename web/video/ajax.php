<?php
$ini_array = parse_ini_file('/tmp/smarthome.ini', true);

// send to api
$auth = base64_encode($ini_array['transmitter']['api_user'].':'.$ini_array['transmitter']['api_pass']);
$context = stream_context_create(['http' => ['header' => "Authorization: Basic $auth", 'method' => 'POST']]);

$url = $ini_array['transmitter']['api_url'] . "/chromecast/control?action=" . $_GET['action'];
if (!empty($_GET['duration'])) {
	$url .= '&duration='.$_GET['duration'];
}

file_get_contents($url, false, $context );


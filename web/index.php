<html>
<head>
	<title>Blackpi Home</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
	.temperature {
	    font-size: 100pt;
	}
        body { background-color: #000; color: #fff}
	</style>
</head>
<body>

<div class="temperature">
<?php
$ini_array = parse_ini_file('/tmp/smarthome.ini', true);

$auth = base64_encode($ini_array['temperature']['api_user'].':'.$ini_array['temperature']['api_pass']);
$context = stream_context_create(['http' => ['header' => "Authorization: Basic $auth"]]);
$homepage = file_get_contents($ini_array['temperature']['api_url'] . "/temperature?sensor=gpio7", false, $context );
if ($homepage) {
    $json = json_decode($homepage);
    echo $json->result[0]->result.'°'; 
}
?>
</div>

</body>
</html>

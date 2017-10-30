<html>
<head>
	<title>Blackpi</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
        .botframe {
            border: 0;
            width: 460px;
            height: 500px;
	}
	.temperature {
	    font-size: 100pt;
	}
	.time {
	    font-size: 18pt;
	}
        body { background-color: #000; color: #fff}
	</style>
	<script type="text/javascript">
function toggleBackground() {
	document.body.style.backgroundColor = 'white';
	setTimeout(function() {
		document.body.style.backgroundColor = 'black';
	}, 600);
}
</script>
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

	<iframe class="botframe" src="https://console.dialogflow.com/api-client/demo/embedded/f9e768a2-3950-41cc-ac53-70c317925373"></iframe>

<div class="time" onclick="toggleBackground()">
<?php
echo date('d.m.Y - H:i');
?>
</div>
</body>
</html>

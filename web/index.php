<html>
<head>
	<title>Blackpi Home</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <style type="text/css">
	.demo-card-wide {
	  width: 440px;
	}
	.mdl-card { margin: 15px 15px;}
	body {min-height: auto;}
	</style>
</head>
<body>

<?php
$ini_array = parse_ini_file('/tmp/smarthome.ini', true);

$auth = base64_encode($ini_array['temperature']['api_user'].':'.$ini_array['temperature']['api_pass']);
$context = stream_context_create(['http' => ['header' => "Authorization: Basic $auth"]]);
$homepage = file_get_contents($ini_array['temperature']['api_url'] . "/temperature?sensor=gpio7", false, $context );
$temperature = 'xx.x';
if ($homepage) {
    $json = json_decode($homepage);
    $temperature = $json->result[0]->result; 
}
?>
<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title" style="color: #fff; height: 180px; background: url('temperature.jpg') center / cover;">
    <h2 class="mdl-card__title-text" style="font-size: 100pt"><?php echo $temperature.'°'; ?></h2>
  </div>
</div>

<?php
$tips = [
    'Kinderzimmer Temperatur auf 25',
    'Kinderzimmer Temperatur auf 20',
    'Wieviel Grad sind es Innen',
    'Temperatur Innen',
    'Schalte Ecklampe an',
    'Schalte die Girlande an',
    'Schalte die Girlande an',
    'Booste Kinderzimmer',
    'Heize das Kinderzimmer',
    'Heize das Kinderzimmer',
    'Chromecast Stop',
    'Suche nach "Test"',
    'Schließe den Tab',
    'Wechsle den Tab'
];
?>

<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title" style="color: #fff; height: 140px; background: url('voice.jpg') center / cover;">
    <h2 class="mdl-card__title-text" style="font-size: 32pt">Spreche mit Black Pi</h2>
  </div>
  <div class="mdl-card__supporting-text" style="font-size: 22pt; text-align: right; line-height: 28pt">
    &raquo; <?php echo $tips[rand(0, count($tips) - 1)]; ?> &laquo;
  </div>
</div>

<?php


?>

</body>
</html>

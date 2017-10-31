<html>
<head>
    <title>Blackpi Video</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <style type="text/css">
	.demo-card-wide > .mdl-card__title {
	  color: #fff;
	  width: 320px;
	  height: 200px;
	}
	</style>
	<script type="text/javascript">
	function stream(url) {
		var snackbarContainer = document.querySelector('#demo-snackbar-example');
		var notification = document.querySelector('.mdl-js-snackbar');
		var data = {
		  message: 'Playback started',
		  timeout: 10000
		};
		notification.MaterialSnackbar.showSnackbar(data);
		location.href = url;
	}
	</script>
</head>
<body>
<p style="text-align:center">
<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onclick="document.location.href='http://www.onlinetvrecorder.com/v2/myepg/setup/tvPilot/'">
  EPG
</button>
<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onclick="document.location.href='stream.php'">
  controls
</button>
<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onclick="document.location.href='stream.php?url=' + prompt('Please enter url')">
  add
</button>
</p>
<?php
require_once(__DIR__ . '/Otr.php');
$otr = new Otr('/tmp/smarthome.ini');
foreach ($otr->getFinished() as $line) {

	echo '<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title" style="background: url(\''.$line->image.'\') center / cover;">
    <h2 class="mdl-card__title-text">'.$line->title.'</h2>
  </div>
  <div class="mdl-card__supporting-text">'.substr($line->text, 0, 170).'..</div>
  <div class="mdl-card__actions mdl-card--border">
  	'.date('D d.M', strtotime($line->timestamp)).'
  	'.$line->icons.'
  </div>
  <div class="mdl-card__menu">
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" onclick="stream(\'stream.php?url='.urlencode($line->url).'\')">
      <i class="material-icons">play_arrow</i>
    </button>
  </div>
</div><br />';

}
?>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>
</body>
</html>

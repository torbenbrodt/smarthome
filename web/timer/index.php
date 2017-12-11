<html>
<head>
	<title>Blackpi Timer</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
	<style type="text/css">
	.demo-card-wide {
		width: 430px;
	}
	.mdl-card { margin: 15px 15px;}
	body {min-height: auto;}
	</style>
        <script type="text/javascript">
        function add_timer(timer_id) {
                var snackbarContainer = document.querySelector('#demo-snackbar-example');
                var notification = document.querySelector('.mdl-js-snackbar');
                var data = {
                  message: 'Timer added',
                  timeout: 10000
                };
                notification.MaterialSnackbar.showSnackbar(data);
		(new Image()).src = 'ajax.php?timer=' + timer_id;
		
		setTimeout(function(){
			window.location.reload(1);
		}, 1000);
        }
	</script>
</head>
<body>

<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title" style="color: #fff; height: 140px; background: url('timer.jpg') center / cover;">
    <h2 class="mdl-card__title-text" style="font-size: 32pt; padding-left: 20px">Timers</h2>
  </div>
  <div class="mdl-card__supporting-text" style="line-height: 130pt; color: #000">
<?php
date_default_timezone_set("Europe/Berlin");

require_once(__DIR__ . '/Timer.php');
$timer = new Timer(__DIR__ . '/timer.log');
foreach($timer->getTimers() as $timer) {
	printf('<div><b style="font-size:116pt">%s</b><br />', $timer->getTime(), $timer->getElapsed());
}
?>
  </div>
  <div class="mdl-card__menu">
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" onclick="add_timer('black')">
      <i class="material-icons">timer</i>
    </button>
  </div>
</div>

<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>
</body>
</html>

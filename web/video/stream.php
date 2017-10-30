<html>
<head>  
        <title>Blackpi Video</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
	<script type="text/javascript">
	function updateSlider(seconds) {
		var seconds = parseInt(document.getElementById('text_01').value) + (parseInt(document.getElementById('text_01').value) * 60);
		document.getElementById('text_01').value = 
			document.getElementById('s2').value + 'm ' +
			document.getElementById('s1').value + 's';
	}
	function chromecastControl(action) {
		var duration = '';
		if (action == 'seekback' || action == 'seekforward') {
			duration = '{"amount":'+(parseInt(document.getElementById('s2').value*60) + parseInt(document.getElementById('s1').value))+
				',"unit":"s"}';
		}	

                var snackbarContainer = document.querySelector('#demo-snackbar-example');
                var notification = document.querySelector('.mdl-js-snackbar');
                var data = {
                  message: 'Sending ' + action,
                  timeout: 10000
                };
                notification.MaterialSnackbar.showSnackbar(data);

		var url = 'ajax.php?action=' + action;
		if (duration) {
			url += '&duration=' + duration;
		}
		(new Image()).src = url;
	}
        </script>
</head>
<body>

<p>
  <div class="mdl-textfield mdl-js-textfield">
    <input class="mdl-textfield__input" type="text" id="text_01" style="font-size: 42pt; text-align: center; margin: 30px 0px i30px 0px" >
  </div>
</p>

<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" style="float: left; margin: 0px 10px 0px 5px" onclick="chromecastControl('seekback')">
  <i class="material-icons">fast_rewind</i>
</button>
<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" style="float: right; margin-right: 5px" onclick="chromecastControl('seekforward')">
  <i class="material-icons">fast_forward</i>
</button>

<p style="padding-top:15px">
<span style="float:left">sec</span>
<input class="mdl-slider mdl-js-slider" type="range" id="s1" min="0" max="60" value="0" step="1" onchange="updateSlider()">
</p>
<p style="padding-top:15px">
<span style="float:left">min</span>
<input class="mdl-slider mdl-js-slider" type="range" id="s2" min="0" max="30" value="0" step="1" onchange="updateSlider()">
</p>

<p style="text-align:center">
<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" onclick="chromecastControl('play')">
  <i class="material-icons">play_arrow</i>
</button>

<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" onclick="chromecastControl('pause')">
  <i class="material-icons">pause</i>
</button>
<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" onclick="chromecastControl('stop')">
  <i class="material-icons">stop</i>
</button>
</p>

<?php
$ini_array = parse_ini_file('/tmp/smarthome.ini', true);
$otr_email = $ini_array['otr']['otr_email'];
$otr_password = $ini_array['otr']['otr_password'];

if (!empty($_GET['url']))  {
$otr_download_url = $_GET['url'];

$context = stream_context_create(['http' => ['header' => 'Cookie: otr_email='.$otr_email.'; otr_password='.$otr_password.';']]);

$content = file_get_contents($otr_download_url, false, $context );
if(!preg_match_all('/a href=\'(http.+download[^\']+)/', $content, $res)) {
	throw new Exception('no download link');
}

$scores = [
#    'HQ.avi',
#    'HQ.cut.avi',
    'mp4',
#    'avi',
#    'cut.avi',
    'cut.mp4',
    'HQ.cut.mp4'
];

$scoredList = [];
foreach ($res[1] as $url) {
	$piece = explode('.mpg.', $url);
	$score = array_search($piece[1], $scores);
	$map[$url] = $score;
}

arsort($map);

$bestHit = key($map);

echo $bestHit;

// send to api
$auth = base64_encode($ini_array['transmitter']['api_user'].':'.$ini_array['transmitter']['api_pass']);
$context = stream_context_create(['http' => ['header' => "Authorization: Basic $auth", 'method' => 'POST']]);
file_get_contents($ini_array['transmitter']['api_url'] . "/chromecast/stream?url=" . $bestHit, false, $context );

}
?>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>
</body>
</html>

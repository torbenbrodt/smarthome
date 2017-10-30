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

<?php
$otr_download_url = 'https://www.onlinetvrecorder.com/v2/?go=list&tab=search&preset=5';

$context = stream_context_create(['http' => ['header' => 'Cookie: otr_email=t.brodt@gmail.com; otr_email=t.brodt@gmail.com; otr_password=4ba89f47ea7c505e9c8d7b2b31cdebb3; otr_password=4ba89f47ea7c505e9c8d7b2b31cdebb3;']]);

$content = file_get_contents($otr_download_url, false, $context );
if(!preg_match_all('/tdstation.+(https:\/\/static[^\']+)/', $content, $station)) {
	throw new Exception('no station');
}

if(!preg_match_all('/(go=download[^\']+)[^>]+>(.+)<\/a/', $content, $url_title)) {
	throw new Exception('no title');
}

if(!preg_match_all('/spanlongtext[^>]+>([^<]+)/', $content, $text)) {
	throw new Exception('no text');
}

if(!preg_match_all('/tdquickformats[^>]+>(.+)<\/td>/siU', $content, $icons)) {
	//TODO: extract just images
	throw new Exception('no icons');
}

if(!preg_match_all('/listimagetd.+(https:\/\/static[^\']+)/', $content, $images)) {
	throw new Exception('no images');
}

if(!preg_match_all('/>(\d{1,2}\.\d{1,2}\.\d{2,4})<\/td>/', $content, $dates)) {
	throw new Exception('no dates');
}


for($i=0; $i<count($images[1]); $i++) {
	$url = 'https://www.onlinetvrecorder.com/v2/?' . $url_title[1][$i];
	$nice_date = date('D d.M', strtotime($dates[1][$i]));

	echo '<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title" style="background: url(\''.$images[1][$i].'\') center / cover;">
    <h2 class="mdl-card__title-text">'.$url_title[2][$i].'</h2>
  </div>
  <div class="mdl-card__supporting-text">'.substr($text[1][$i], 0, 170).'..</div>
  <div class="mdl-card__actions mdl-card--border">
  	'.$nice_date.'
  	'.$icons[1][$i].'
  </div>
  <div class="mdl-card__menu">
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" onclick="stream(\'stream.php?url='.urlencode($url).'\')">
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

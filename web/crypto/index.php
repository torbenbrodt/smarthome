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

<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title" style="color: #fff; height: 140px; background: url('bitcoin.jpg') center / cover;">
    <h2 class="mdl-card__title-text" style="font-size: 32pt">Crypto Currencies</h2>
  </div>

<table class="mdl-data-table" style="width: 100%; font-size: 26pt; line-height: 38pt">
  <tbody>
<?php
require_once(__DIR__ . '/Crypto.php');
$crypto = new Crypto('/tmp/smarthome.ini');
foreach($crypto->getCurrencies() as $currency) {
printf('
    <tr>
      <td class="mdl-data-table__cell--non-numeric">%s</td>
      <td style="color: %s">%s</td>
    </tr>
', $currency->name, $currency->percentage > 0 ? 'green' : 'red', $currency->percentage . '%');
}
?>
  </tbody>
</table>
</div>

</body>
</html>

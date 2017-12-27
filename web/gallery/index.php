<html>
<head>
	<title>Blackpi Gallery</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="512x512" href="android-chrome-512x512.png">
        <link rel="icon" type="image/png" sizes="192x192" href="android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

        <style type="text/css">
        body {
            margin: 0px;
            background-color: #F4F4F4;
            overflow: hidden;
        }
<?php
$iterator = new \RecursiveDirectoryIterator(__DIR__);
$iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);

$images = [];
foreach ($iterator as $file) {
	if ($file->getExtension() == 'jpg') {
		$relpath = basename($file->getPath());
		$images[$relpath][] = $relpath . '/' . $file->getFilename();
	}
}

$this_or_next_month = $images[date('m')];
shuffle($this_or_next_month);

$image = isset($_GET['image']) ? $_GET['image'] : array_shift($this_or_next_month);

$padding = intval(explode(',', $image)[1]);
?>
	@keyframes kenburns {
	    70% {
	    	transform: scale3d(2, 2, 2) translate3d(-<?php echo $padding?>%, 0px, 0px);
	        animation-timing-function: ease-in;
	    }
	    100% {
	        transform: scale3d(1, 1, 1) translate3d(0px, 0px, 0px);
	        animation-timing-function: ease-in;
	    }
	}

	#imageContainer {
	  animation: kenburns 60s infinite;
	  height: 100%;
	}
	</style>
</head>
<body>
<?php
echo '<img id="imageContainer" src="'.$image.'" alt="" />';
?>

</body>
</html>

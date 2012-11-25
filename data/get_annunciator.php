<?php
	header('Content-Type: application/json');
	header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

	$frames_json = file_get_contents('annunciator.json');
	$frames = json_decode($frames_json, true);

	$index = -1;
	if(isset($_GET["index"])) {
		$val = (int)$_GET["index"];
		if($val < 33 && $val >= 0) {
			$index = (int)$_GET["index"];
		}
	} 
	if($index == -1) {
		$time = time();
		$looptime = $time % 660;
		$index = (int)($looptime / 20);
	}

	echo(json_encode($frames[$index]));
?>

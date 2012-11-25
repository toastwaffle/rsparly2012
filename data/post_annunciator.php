<?php
	$frames_json = file_get_contents('annunciator.json');
	$frames = json_decode($frames_json, true);

	$index = -1;
	if(isset($argv[1])) {
		$val = (int)$argv[1];
		if($val < 33 && $val >= 0) {
			$index = $val;
		}
	}
	if($index == -1) {
		$time = time();
		$looptime = $time % 660;
		$index = (int)($looptime / 20);
	}

	$urls = array('http://www.agm.me.uk/parlihack2012/_smsgateway/notify.php',
		'http://rsparly.toastwaffle.com/smsm1/notify.php');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, True);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('frame'=>json_encode($frames[$index])));

	foreach ($urls as $url) {
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_exec($ch);
	}

	curl_close($ch);
?>

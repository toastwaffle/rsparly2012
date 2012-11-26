<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */
function sendMsg($id, $msg, $events) {
	echo "id: $id" . PHP_EOL;
	//echo "data: $msg" . PHP_EOL . PHP_EOL;
	foreach($events as $key => $value) {
		echo "event: $key" . PHP_EOL; 
		echo "data: " . json_encode($value) . PHP_EOL . PHP_EOL;
	}
	echo PHP_EOL;
	ob_flush();
	flush();
}

$json = file_get_contents('http://rsparly.toastwaffle.com/get_annunciator.php');
$data = json_decode($json, TRUE);
//echo(print_r($data));
//$serverTime = time();

sendMsg($data["datetime"], 'server time: ' . date("h:i:s", time()), $data);

?>

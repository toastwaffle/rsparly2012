<?PHP

include_once( 'twilio_config.php' );

function postToTwilio( $inMessage, $inTo ) {
	$session = curl_init();
	curl_setopt( $session, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/' . TWILIO_APIKEY . '/SMS/Messages.json' );
	curl_setopt( $session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
	curl_setopt( $session, CURLOPT_HEADER, false );
	curl_setopt( $session, CURLOPT_USERPWD, TWILIO_USERPWD );
	curl_setopt( $session, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $session, CURLOPT_POST, 1);
	curl_setopt( $session, CURLOPT_POSTFIELDS, 'From=' . urlencode( TWILIO_FROM ) . "&" . 'To=' . urlencode( $inTo ) . "&" . 'Body=' . urlencode( $inMessage ) );
	$data = curl_exec( $session );
	$result = curl_getinfo( $session, CURLINFO_HTTP_CODE );
	curl_close( $session );

	return json_decode($result==200);
}

function callToTwilio( $inTo, $inUrl = 'http://www.agm.me.uk/parlihack2012/_smsgateway/twilio_voice_callback3.php' ) {
	$session = curl_init();
	curl_setopt( $session, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/' . TWILIO_APIKEY . '/Calls' );
	curl_setopt( $session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
	curl_setopt( $session, CURLOPT_HEADER, false );
	curl_setopt( $session, CURLOPT_USERPWD, TWILIO_USERPWD );
	curl_setopt( $session, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $session, CURLOPT_POST, 1);
	curl_setopt( $session, CURLOPT_POSTFIELDS, 'From=' . urlencode( TWILIO_FROM ) . "&" . 'To=' . urlencode( $inTo ) . "&" . 'Url=' . urlencode( $inUrl ) );
	$data = curl_exec( $session );
	$result = curl_getinfo( $session, CURLINFO_HTTP_CODE );
	curl_close( $session );

	return json_decode($result==200);
}

?>
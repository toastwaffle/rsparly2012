<?PHP

include_once( 'twilio.php' );
include_once( 'database.php' );

$theSQL = 'SELECT `phonenum` FROM `bell_list` WHERE `onoff` = "1"';
$theResult = mysql_query( $theSQL );

while ( $theRow = mysql_fetch_assoc( $theResult ) ) {
	postToTwilio( 'The division bell is sounding! Text "BELL OFF" to this number to turn off this notification.', $theRow['phonenum'] );
}

$theSQL = 'SELECT `phonenum` FROM `bell_voicelist` WHERE `onoff` = "1"';
$theResult = mysql_query( $theSQL );

while ( $theRow = mysql_fetch_assoc( $theResult ) ) {
	callToTwilio( $theRow['phonenum'] );
}


?>
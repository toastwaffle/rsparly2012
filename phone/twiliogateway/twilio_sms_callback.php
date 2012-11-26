<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<?PHP

include_once( 'twilio_config.php' );
include_once( 'database.php' );

// Commands

function commandBellOn( $inNumber ) {
	$theSQL = 'REPLACE INTO `bell_list` ( `phonenum`, `onoff` ) VALUES ( "' . $inNumber . '", "1" )';
	mysql_query( $theSQL );
	echo '<Sms>You will now be notified of the division bell. Text "BELL OFF" to this number to turn it off again.</Sms>';
}

function commandBellOff( $inNumber ) {
	$theSQL = 'REPLACE INTO `bell_list` ( `phonenum`, `onoff` ) VALUES ( "' . $inNumber . '", "0" )';
	mysql_query( $theSQL );
	echo '<Sms>You will no longer be notified of the division bell.</Sms>';
}

function commandBell( $inNumber, $inOnOff = 'ON' ) {

	echo $inNumber . '-' . $inOnOff;

	if ( $inOnOff == 'ON' ) {
		commandBellOn( $inNumber );
	}
	else {
		commandBellOff( $inNumber );
	}
}

function commandNotify( $inNumber, $inTerm ) {
	$theSQL = 'REPLACE INTO `notify` ( `phonenum`, `term` ) VALUES ( "' . $inNumber . '", "' . mysql_real_escape_string( $inTerm ) . '" )';
	mysql_query( $theSQL );
	echo '<Sms>Search term "' . htmlspecialchars( $inTerm ) . '" added.</Sms>';
}

function commandCancel( $inNumber, $inTerm ) {
	$theSQL = 'SELECT * FROM `notify` WHERE `phonenum` = "' . $inNumber . '" AND `term` = "' . mysql_real_escape_string( $inTerm ) . '"';
	$theResult = mysql_query( $theSQL );
	if ( mysql_num_rows( $theResult ) > 0 ) {
		$theSQL = 'DELETE FROM `notify` WHERE `phonenum` = "' . $inNumber . '" AND `term` = "' . mysql_real_escape_string( $inTerm ) . '"';
		mysql_query( $theSQL );
		echo '<Sms>Search term "' . htmlspecialchars( $inTerm ) . '" removed.</Sms>';
	}
	else {
		echo '<Sms>Search term "' . htmlspecialchars( $inTerm ) . '" not found.</Sms>';
	}
}

function commandError( $inNumber, $inRequest ) {
	echo '<Sms>Sorry, I did not understand that request.</Sms>';
}

// Main

if ( ( $_POST['AccountSid'] == TWILIO_APIKEY ) && ( $_POST['To'] == TWILIO_FROM ) ) {

	$theRequest = explode( ' ', strtoupper( $_POST['Body'] ) );
	$theRequestWordcount = count( $theRequest );
	
	if ( $theRequestWordcount >= 2 ) {
		switch ( $theRequest[0] ) {
			case 'BELL' :
				commandBell( $_POST['From'], $theRequest[1] );
				break;
			case 'NOTIFY' :
				commandNotify( $_POST['From'], substr( strtoupper( $_POST['Body'] ), 7 ) );
				break;
			case 'CANCEL' :
				commandCancel( $_POST['From'], substr( strtoupper( $_POST['Body'] ), 7 ) );
				break;
			default:
				commandError( $_POST['From'], $_POST['Body'] );
		}

	}

}

?>
</Response>

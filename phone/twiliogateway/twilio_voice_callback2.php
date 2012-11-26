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
	$theSQL = 'REPLACE INTO `bell_voicelist` ( `phonenum`, `onoff` ) VALUES ( "' . $inNumber . '", "1" )';
	mysql_query( $theSQL );
	echo '<Say voice="woman">You will now be called when the division bell sounds.</Say>';
}

function commandBellOff( $inNumber ) {
	$theSQL = 'REPLACE INTO `bell_voicelist` ( `phonenum`, `onoff` ) VALUES ( "' . $inNumber . '", "0" )';
	mysql_query( $theSQL );
	echo '<Say voice="woman">You will no longer be called when the division bell sounds.</Say>';
}

// Main

	if ( isset( $_POST['Digits'] ) ) {
		switch ( $_POST['Digits'] ) {
			case '1' :
				commandBellOn( $_POST['From'], $theRequest[1] );
				break;
			case '2' :
				commandBellOff( $_POST['From'], $theRequest[1] );
				break;
		}

	}

?>
</Response>

<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<?PHP

	include_once( 'database.php' );

	$theSQL = 'SELECT `when`, `who`, `where`, `what`, `description` from `updates` ORDER BY `when` DESC LIMIT 1';
	$theResult = mysql_query( $theSQL );
	if ( $theRow = mysql_fetch_assoc( $theResult ) ) {
		$theMessage = 'Currently there is a ';
		if ( $theRow['what'] ) {
			$theMessage .= $theRow['what'];
		}
		if ( ( $theRow['what'] ) or ( $theRow['description'] ) ) {
			$theMessage .= ' on ';
		}
		if ( $theRow['description'] ) {
			$theMessage .= $theRow['description'];
		}
		
		$theWho = '';
		if ( $theRow['who'] ) {
			$theWho .= $theRow['who'];
			if ( $theRow['where'] ) {
				$theWho .= ' of ' . $theRow['where'];
			}
			$theWho .= ' speaking.';
		}
	}

	echo '<Say voice="man">' . $theMessage . '</Say>' . "\n";	
	if ( $theWho ) {
		echo '<Say voice="man">' . $theWho . '</Say>' . "\n";	
	}
	echo '<Pause length="1"/>';

	echo '<Gather timeout="10" numDigits="1" action="http://********/twilio_voice_callback2.php">' . "\n";	
		$theSQL = 'SELECT * FROM `bell_voicelist` WHERE `phonenum` = "' . $_POST['From'] . '"';
		$theResult = mysql_query( $theSQL );
		if ( ( $theRow = mysql_fetch_assoc( $theResult ) ) && ( $theRow['onoff'] == '1' ) ) {
			echo '<Say voice="woman">You are subscribed to division bell notifications.</Say>' . "\n";	
			echo '<Pause length="1"/>' . "\n";
			echo '<Say voice="woman">Press 2 to unsubscribe.</Say>' . "\n";	
		}
		else {
			echo '<Say voice="woman">Press 1 to subscribe to division bell notifications.</Say>' . "\n";	
		}
	echo '</Gather>' . "\n";	

?>
</Response>
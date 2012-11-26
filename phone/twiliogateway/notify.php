<?PHP

include_once( 'database.php' );
include_once( 'twilio.php' );


// Main

$theData = json_decode( stripslashes( $_POST['frame'] ), TRUE );

if ( isset($theData['person']) && isset($theData['person']['name']) && isset($theData['activity']) && isset($theData['topic']) ) {

	$theDesc = strtoupper( $theData['topic'] );

	// Check the update is a change

	$theSQL = 'SELECT * FROM `updates` WHERE `who` = "' . mysql_real_escape_string( $theData['person']['name'] ) . '" AND `what` = "' . mysql_real_escape_string( $theData['activity'] ) . '" AND `description` = "' . mysql_real_escape_string( $theDesc ) . '" ); ';
	$theResult = mysql_query( $theSQL );
	
	if ( mysql_num_rows( $theResult ) == 0 ) {	
		
		// Save the update

		$theSQL = 'INSERT INTO `updates` ( `when`, `who`, `where`, `what`, `description` ) VALUES ( "' . date( 'Y-m-d H:i:s' ) . '" ,  "' . mysql_real_escape_string( $theData['person']['name'] ) . '",  "",  "' . mysql_real_escape_string( $theData['activity'] ) . '",  "' . mysql_real_escape_string( $theDesc ) . '" ); ';
		$theResult = mysql_query( $theSQL );
		

		// Send the search notifications
		
		$theSQL = 'SELECT * FROM `notify`';
		$theResult = mysql_query( $theSQL );
		
		while ( $theRow = mysql_fetch_assoc( $theResult ) ) {
			if ( strpos( $theDesc, $theRow['term'] ) !== FALSE ) {
				postToTwilio( '"' . htmlspecialchars( $theDesc ) . '" is being spoken about. This notification has now been canceled.', $theRow['phonenum'] );
				$theSQL2 = 'DELETE FROM `notify` WHERE `phonenum` = "' . $theRow['phonenum'] . '" AND `term` = "' .  mysql_real_escape_string( $theRow['term'] ) . '"';
				mysql_query( $theSQL2 );
			}
		}

		// Tidy the update database

		$theSQL = 'DELETE FROM `updates` WHERE `when` < "' . date( 'Y-m-d H:i:s', strtotime( '-1 day' ) ) . '"';
		$theResult = mysql_query( $theSQL );


	}

}

?>
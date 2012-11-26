<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<?PHP

include_once( 'twilio_config.php' );
include_once( 'database.php' );

// Commands

$theSQL = 'REPLACE INTO `bell_voicelist` ( `phonenum`, `onoff` ) VALUES ( "' . $_POST['To'] . '", "0" )';
mysql_query( $theSQL );
echo '<Say voice="woman">You will no longer be called when the division bell sounds.</Say>';

?>
</Response>

<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<Say>The division bell is sounding.</Say>
<Pause length="1"/>
<Say>The division bell is sounding.</Say>
<Pause length="1"/>
<Gather timeout="10" numDigits="1" action="http://********/twilio_voice_callback4.php" method="POST">
<Say voice="woman">Press 2 to unsubscribe.</Say>
</Gather>
</Response>

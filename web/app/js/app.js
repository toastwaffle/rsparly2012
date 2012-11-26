$(document).ready(function() {
	if (!!window.EventSource) {
		$('message').text("EventSource available");
		var source = new EventSource('stream.php');
		var search_person = null;
		var last_person_notification = null;

		source.addEventListener('message', function(e) {
			//var data = JSON.parse(e.data);
			//console.log(e.data);
			$('#message').text(e.data);
			//console.log(e.id);
			//console.log(e.msg);
		}, false);

		source.addEventListener('activity', function(e) {
			$("#activity").text(e.data);
		}, false);

		source.addEventListener('topic', function(e) {
			$("#topic").text(e.data);
		}, false);

		source.addEventListener('person', function(e) {
			eval("data="+e.data);
			$("#person").text(data["name"]);
			console.log("Person:" +data['name']);
			console.log("Search:"+search_person);
			if (search_person != null) {
				console.log("Search has been set");
				if (data["name"].indexOf(search_person) != -1 && last_person_notification == null) {
					console.log("Found a match yay, now to push a notification.");
					// Assume that we have permission already setup to create notifications
					if (window.webkitNotifications.checkPermission() == 0) {
						n = window.webkitNotifications.createNotification('', data["name"], data["person_id"]);
						last_person_notification = data.person_id
						n.ondisplay = function() { $("#notification").text("The notification has been displayed"); };
						n.onclose = function() { 
							$("#notification").text("The notification has been closed"); 
							last_person_notification = null;
						};
						n.show();
					} else {
						console.log("no permission");
					}
				}
			}
		}, false);

		source.addEventListener('datetime', function(e) {
			$("#when").text(e.data);
		}, false);

		source.addEventListener('bg-colour', function(e) {
			data = JSON.parse(e.data);
			console.log(data);
			//colour_str = data[0] . "," data[1] . ",
			$('body').css("background", "rgb(" + data[0] + "," + data[1] + "," + data[2] + ");");
		}, false);

		source.addEventListener('open', function(e) {
			//$("#message").text("Connection opened, awaiting an update.");
		}, false);

		source.addEventListener('error', function(e) {
			//console.log(e);
			//source.close();
			//$("#stop").text("Some error reported when updating due to an error, refresh to get more updates");
		}, false);

		$('#person_search').on('submit', function(event) {
			console.log("Search form submitted");
			if (window.webkitNotifications) {
				if(window.webkitNotifications.checkPermission() == 0) { // 0 is PERMISSION_ALLOWED
					console.log("Notictaiontons enabled");
					search_person = $('#person').val();
					console.log("Notification set");
				} else {
					console.log("Notification requested");
					window.webkitNotifications.requestPermission();
					console.log("After notification request");
				}
			} else {
				console.log("This browser doesn't support notifications.");
				$("#message").text("Sorry this browser doesn't support notifications.");
			}
			event.preventDefault();
		});

		$("#stop").click(function() {
			source.close();
			$("#stop").text("Stopped updating, refresh to get more updates");
		});
	} else {
		// Result to xhr polling :(
		set_message("EventSource ain't available grr");
	}
});

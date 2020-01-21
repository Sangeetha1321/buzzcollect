function load_notifications() 
{
	
	$.ajax({
		url: global_base_url + "home/load_notifications",
		beforeSend: function () { 
		$('#loading_spinner_notification').fadeIn(10);
		$("#ajspinner_notification").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_notification').fadeOut(10);
		$("#ajspinner_notification").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			$('#notifications-scroll').html(msg);
		}

	});
	console.log("Done");
}

function load_notifications_unread() 
{
	$.ajax({
		url: global_base_url + "home/load_notifications_unread",
		beforeSend: function () { 
		$('#loading_spinner_notification').fadeIn(10);
		$("#ajspinner_notification").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_notification').fadeOut(10);
		$("#ajspinner_notification").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			$('#notifications-scroll').html(msg);
			return false;
		}

	});
	console.log("Done");
}

function load_timers() 
{
	
	$.ajax({
		url: global_base_url + "/home/load_timers",
		beforeSend: function () { 
		$('#loading_spinner_timer').fadeIn(10);
		$("#ajspinner_timer").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_timer').fadeOut(10);
		$("#ajspinner_timer").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			$('#timer-scroll').html(msg);
		}

	});
	console.log("Done");
}


function start_timer() 
{
	
	$.ajax({
		url: global_base_url + "/time/add_timer_ajax/" + global_hash,
		dataType: 'json',
		beforeSend: function () { 
		$('#loading_spinner_timer').fadeIn(10);
		$("#ajspinner_timer").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_timer').fadeOut(10);
		$("#ajspinner_timer").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			if(msg.error) {
				$('#timer-scroll').html(msg.error_msg);
			} else {
				$('#timer-count').fadeIn(100);
				var val = parseInt($('#timer-count').html());
				val = val+1;
				$('#timer-count').html(val);
				load_timers();
			}
		}

	});
	console.log("Done");
}


function stop_timer(id) 
{
	
	$.ajax({
		url: global_base_url + "/time/stop_timer_ajax/" +id+"/" + global_hash,
		dataType: 'json',
		beforeSend: function () { 
		$('#loading_spinner_timer').fadeIn(10);
		$("#ajspinner_timer").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_timer').fadeOut(10);
		$("#ajspinner_timer").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			if(msg.error) {
				$('#timer-scroll').html(msg.error_msg);
			} else {
				var val = parseInt($('#timer-count').html());
				val = val-1;
				$('#timer-count').html(val);
				if(val <= 0) {
					$('#timer-count').fadeOut(100);
				}
				load_timers();
			}
		}

	});
	console.log("Done");
}

function load_projects() 
{
	
	$.ajax({
		url: global_base_url + "/home/load_projects",
		beforeSend: function () { 
		$('#loading_spinner_projects').fadeIn(10);
		$("#ajspinner_projects").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_projects').fadeOut(10);
		$("#ajspinner_projects").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			$('#projects-scroll').html(msg);
		}

	});
	console.log("Done");
}

function load_notification_url(id) 
{
	window.location.href= global_base_url + "home/load_notification/" + id;
	return;
}

function load_mail_url(id) 
{
	window.location.href= global_base_url + "mail/index/" + id;
	return;
}


$(document).ready(function() {
    $('.dropdown-menu #start_timer_button').click(function(e) {
	    e.stopPropagation();
	});
	$('.dropdown-menu .stop_timer_button').click(function(e) {
	    e.stopPropagation();
	});
	$('.dropdown-menu #noti-click-unread').click(function(e) {
	    e.stopPropagation();
	});
	//$('#email-scroll').niceScroll();
	//$('#notifications-scroll').niceScroll({touchbehavior: false, zindex: 9999999999});
	//$('#projects-scroll').niceScroll();
	//$('#timer-scroll').niceScroll();
	
});

function load_emails() 
{
	$('#email-box').toggle();
	$.ajax({
		url: global_base_url + "/home/load_emails",
		beforeSend: function () { 
		$('#loading_spinner_email').fadeIn(10);
		$("#ajspinner_email").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_email').fadeOut(10);
		$("#ajspinner_email").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			$('#email-scroll').html(msg);
		}

	});
	console.log("Done");
}
function changeStatus(ticketid, id) {
	$('#status-button-update').fadeIn(100);
	$.ajax({
		url: global_base_url + "tickets/change_status",
		type: "GET",
		data: {
			status : id,
			ticketid : ticketid
		},
		dataType : 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(id == 1) {
				$('#status-button').removeClass();
				$('#status-button').addClass("btn btn-info btn-xs dropdown-toggle");
				$('#status-button').html('New  <span class="caret"></span>');
			} else if(id == 2) {
				$('#status-button').removeClass();
				$('#status-button').addClass("btn btn-primary btn-xs dropdown-toggle");
				$('#status-button').html('In Progress  <span class="caret"></span>');
			} else if(id == 3) {
				$('#status-button').removeClass();
				$('#status-button').addClass("btn btn-danger btn-xs dropdown-toggle");
				$('#status-button').html('Closed  <span class="caret"></span>');
			}
			//$('#status-button-update').html(msg);
			$('#status-button-update').fadeOut(500);
		}
	})
}
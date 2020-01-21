 $(function() {
	$( "#progressslider" ).slider({
		range: "min",
		value: $('#progressamountval').val(),
		min: 0,
		max: 100,
		slide: function( event, ui ) {
			$("#progressamountval").val(ui.value);
			$( "#progressamount" ).html( ui.value + "%");
		}
	});

	/* Get list of usernames */

  $('#file-search').autocomplete({
  	delay : 300,
  	minLength: 2,
  	source: function (request, response) {
  		 var taskid = $('#taskid').val();
         $.ajax({
             type: "GET",
             url: global_base_url + "tasks/get_files/" + taskid,
             data: {
             		query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             }
         });
    },
    select: function(event, ui) {
        event.preventDefault();
        $("#file-search").val(ui.item.label);
        $("#file-search-hidden").val(ui.item.value);
        $('#file-link').html('<a href="'+global_base_url+'files/view_file/'+ui.item.value+'" target="_blank">View '+ui.item.label+'</a>');
    },
    focus: function(event, ui) {
        event.preventDefault();
        $("#file-search").val(ui.item.label);
        $("#file-search-hidden").val(ui.item.value);
        $('#file-link').html('<a href="'+global_base_url+'files/view_file/'+ui.item.value+'" target="_blank">View '+ui.item.label+'</a>');
    }
  });
});


function changeStatus(id) {
	$('#status-button-update').fadeIn(100);
	var taskid = $('#taskid').val();
	$.ajax({
		url: global_base_url + "tasks/change_status",
		type: "GET",
		data: {
			status : id,
			taskid : taskid
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
				$('#status-button').addClass("btn btn-success btn-xs dropdown-toggle");
				$('#status-button').html('Completed  <span class="caret"></span>');
			} else if(id == 4) {
				$('#status-button').removeClass();
				$('#status-button').addClass("btn btn-warning btn-xs dropdown-toggle");
				$('#status-button').html('On Hold  <span class="caret"></span>');
			} else if(id == 5) {
				$('#status-button').removeClass();
				$('#status-button').addClass("btn btn-danger btn-xs dropdown-toggle");
				$('#status-button').html('WIP <span class="caret"></span>');
			}
			//$('#status-button-update').html(msg);
			$('#status-button-update').fadeOut(500);
		}
	})
}

function update_task() 
{
	$('#updatedetails-button-update').fadeIn(100);
	var taskid = $('#taskid').val();
	var start_date = $('#start_date').val();
	var due_date = $('#due_date').val();
	var complete = $('#progressamountval').val();
	var sync = 0;
	if($('#sync').is(':checked')) {
		sync = 1;
	} else {
		sync = 0;
	}
	$.ajax({
		url: global_base_url + "tasks/update_details",
		type: "GET",
		data: {
			taskid : taskid,
			start_date : start_date,
			due_date : due_date,
			complete : complete,
			sync : sync
		},
		dataType : 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(complete == 100) {
				changeStatus(3);
			}
			$( "#progressslider" ).slider({value:msg.complete});
			$("#progressamountval").val(msg.complete);
			$( "#progressamount" ).html(msg.complete + "%");
			$('#updatedetails-button-update').fadeOut(500);
		}
	})
}

function remind_user(id) 
{
	$('#remind-user-' + id).fadeOut(100);
	$.ajax({
		url: global_base_url + "tasks/remind_user",
		type: "GET",
		data: {
			id : id,
			hash : global_hash
		},
		dataType : 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			$('#remind-user-' + id).removeClass("btn-warning");
			$('#remind-user-' + id).addClass("btn-success");
			$('#remind-user-' + id).html('<span class="glyphicon glyphicon-ok"></span>');
			$('#remind-user-' + id).fadeIn(500);
		}
	})
}

function editObjective(id) {
	$.ajax({
		url: global_base_url + "tasks/edit_objective/" + id,
		type: "GET",
		data: {
		},
		success:function(msg) {
			$('#editObjective').html(msg);
		}
	})
}
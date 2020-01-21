var prev = 0;

function load_mail(id) 
{
	if(prev > 0) {
		// White
		$('#mail-box-msg-' + prev).css('background', '#FFF');
	}

	$('#mail-box-msg-' +id).css('background', '#EEE');
	prev = id;
	
	$.ajax({
		url: global_base_url + "mail/view_mail/" + id,
		beforeSend: function () { 
		$('#loading_spinner_mail').fadeIn(10);
		$("#ajspinner_mail").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_mail').fadeOut(10);
		$("#ajspinner_mail").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			if($('#mail-box-msg-' +id).hasClass('mail-unread-alert')) {
				$('#mail-box-msg-' +id).delay(10000).queue(function(next){
				    $(this).removeClass('mail-unread-alert');
				    next();
				});
			}
			$('#mail-view').html(msg);
			CKEDITOR.replace('mail-reply-textarea', { height: '100'});
		}
	});
}

function compose(username = "") 
{
	if(prev > 0) {
		// White
		$('#mail-box-msg-' + prev).css('background', '#FFF');
	}
	prev = 0;
	
	$.ajax({
		url: global_base_url + "mail/compose/",
		beforeSend: function () { 
		$('#loading_spinner_mail').fadeIn(10);
		$("#ajspinner_mail").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_mail').fadeOut(10);
		$("#ajspinner_mail").removeClass("spin");
	 	},
		data: {
			username: username
		},
		success: function(msg) {
			$('#mail-view').html(msg);
			CKEDITOR.replace('mail-reply-textarea', { height: '100'});
		}
	});
}
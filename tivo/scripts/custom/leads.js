$(document).ready(function() {
	$('#fields').on("change", ".field_type", function() {
		var id = $(this).attr("name");
		id = parseInt(id.replace("field_type_", ""));

		var type = $(this).val();

		if(type >=3) {
			$('#field_options_' +id).css("display", "block");
		} else {
			$('#field_options_' +id).css("display", "none");
		}
	});
});

function remove_field(id) 
{
	$('#field-area-'+id).remove();
	var count = $('#field_count').val();
	count--;
	$('#field_count').val(count);
}

function add_form_field() 
{
	var count = $('#field_count').val();
	count++;
	var newid = count;
	var html = '<div id="field-area-'+newid+'" class="field-area">'
				+'<div class="form-group">'
				    +'<div class="col-md-4 ui-front">'
				       +'<input type="text" name="field_title_'+newid+'" class="form-control" placeholder="Field Title">'
				    +'</div>'
				    +'<div class="col-md-4 ui-front">'
				       +'<select name="field_type_'+newid+'" id="field_type_'+newid+'" class="form-control field_type">'
				       +'<option value="1">Input Field</option>'
				       +'<option value="2">Textarea</option>'
				       +'<option value="3">Checkbox</option>'
				       +'<option value="4">Radio</option>'
				       +'<option value="5">Dropdown</option>'
				       +'</select>'
				    +'</div>'
				    +'<div class="col-md-4 ui-front">'
				       +'<select name="field_require_'+newid+'" class="form-control">'
				       +'<option value="0">Not Required</option>'
				       +'<option value="1">Required</option>'
				       +'</select>'
					+'</div>'
				+'</div>'
				+'<div class="form-group">'
				        +'<div class="col-md-4 ui-front">'
				           +'<input type="text" name="field_desc_'+newid+'" class="form-control" placeholder="Field Help Text">'
				        +'</div>'
				        +'<div class="col-md-4 ui-front">'
				           +'<input type="text" name="field_options_'+newid+'" id="field_options_'+newid+'" class="form-control field_options" placeholder="Option1,Option2,Option3">'
				        +'</div>'
				        +'<div class="col-md-4 ui-front">'
				          +'<button type="button" onclick="remove_field('+newid+')" class="btn btn-danger">Remove</button>'
				        +'</div>'
				+'</div>'
				+'</div>';
	$('#fields').append(html);
	$('#field_count').val(count);
}
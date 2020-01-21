<?php foreach($notes->result() as $note) : ?>
	<?php if($note->type == 1) : ?>
		<?php $todos = $this->notes_model->get_note_todos($note->ID); ?>
		<?php foreach($todos->result() as $r) : ?>
        	<div class="form-group note-todos clearfix small-text" id="todo-fullarea-<?php echo $r->ID ?>">
        	<div class="col-md-10" id="todo-area-<?php echo $r->ID ?>">
            <label class="notelabel" for="todo_cb_<?php echo $r->ID ?>" style="<?php if($r->status == 1) echo "text-decoration: line-through;" ?>" id="todo_text_<?php echo $r->ID ?>"><?php echo $r->todo ?>
            </label>
            <p class="tiny-text fadedtext">
            <?php if($r->completed > 0 && $r->status == 1) : ?>
            		<?php $time = $this->common->convert_time_raw($r->completed - $r->timestamp); ?>
                	Finished in <?php echo $this->common->get_time_string($time) ?><br />
                <?php endif; ?>
               <a href="javascript:void(0)" onclick="delete_todo(<?php echo $r->ID ?>)"><?php echo lang("ctn_746") ?></a></p>
        	</div>
            <div class="col-md-2">
                <input type="checkbox" id="todo_cb_<?php echo $r->ID ?>" name="todo_status_<?php echo $r->ID ?>" class="todo_cb" value="<?php echo $r->ID ?>" <?php if($r->status) echo"checked" ?>>
              
            </div>
            </div>
        <?php endforeach; ?>
        <button class="btn btn-primary btn-xs" onclick="load_add_todo(<?php echo $note->ID ?>)"><span class="glyphicon glyphicon-plus"></span></button>
	<?php else : ?>
		<h3 class="note-title"><?php echo $note->title ?></h3>
		<div class="note-content"><?php echo $note->body ?></div>
	<?php endif; ?>
	<hr>
<?php endforeach; ?>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="addnotearea">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-plus"></span> <?php echo lang("ctn_620") ?></h4>
      </div>
      <div class="modal-body">
      	<div class="form-horizontal">
	            <div class="form-group" id="note-todo">
	                  <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1427") ?></label>
	                    <div class="col-md-8 ui-front">
	                      <div id="todolist">
	                       <p><input type="text" placeholder="<?php echo lang("ctn_1428") ?> ..." name="todo_1" id="todo_input" class="form-control"></p>
	                     </div>
	                    </div>
	            </div>
	      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="button" class="btn btn-primary" value="<?php echo lang("ctn_1429") ?>" onclick="add_todo_item()">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

	var noteid = 0;
function load_add_todo(id) 
{
	noteid = id;
	// Update
	$('#addModal').modal('show');
	
}

function add_todo_item() 
{
	var todo = $("#todo_input").val();
	$.ajax({
		url: global_base_url + "notes/add_todo_item/" + noteid + "/" + global_hash,
		type: "GET",
		data: {
			todo : todo
		},
		dataType: 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
			}
			if(msg.success) {
				$('#todo_input').val("");
				// reload todo list
				$('#addModal').modal('hide');
				//get_notes();
			}
		}
	});
}
$(document).ready(function() {
	$('#addModal').on('hidden.bs.modal', function (e) {
		get_notes();
})
		$('.todo_cb').on("change", function() {
		// Get ID
		var id = $(this).val();

		var data = 0;
		if($(this).is(":checked")) {
			data = 1;
		}

		// Update
		$.ajax({
			url: global_base_url + "notes/update_todo_item/" + id + "/" + global_hash + "/1",
			type: "GET",
			data: {
				value : data
			},
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					alert(msg.error_msg);
				}
				$("#todo-area-" + id).html(msg.display)
			}
		});

		
		
	});
});
</script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo lang("ctn_750") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="Add To List" data-toggle="modal" data-target="#addModal">
</div>
</div>


    <h3 class="panel-title"><?php echo $note->title ?>
    	
    </h3>

    <hr>

  	<?php if($note->type == 1) : ?>
  		<div id="todolist">
  				<div class="form-group note-todos-title clearfix">
  					<div class="col-md-8">
  						<strong><?php echo lang("ctn_1448") ?></strong>
  					</div>
  					<div class="col-md-4">
  						<strong><?php echo lang("ctn_818") ?></strong>
  					</div>
  				</div>
            <?php foreach($todos->result() as $r) : ?>
            	<div class="form-group note-todos clearfix" id="todo-fullarea-<?php echo $r->ID ?>">
            	<div class="col-md-8" id="todo-area-<?php echo $r->ID ?>">
                <label class="notelabel" for="todo_cb_<?php echo $r->ID ?>" style="<?php if($r->status == 1) echo "text-decoration: line-through;" ?>" id="todo_text_<?php echo $r->ID ?>"><?php echo $r->todo ?>
                </label>
                <?php if($r->completed > 0 && $r->status == 1) : ?>
                		<?php $time = $this->common->convert_time_raw($r->completed - $r->timestamp); ?>
	                	<p class="small-text fadedtext"><?php echo lang("ctn_1482") ?> <?php echo $this->common->get_time_string($time) ?></p>
	                <?php endif; ?>
            	</div>
                <div class="col-md-4">
	                <input type="checkbox" id="todo_cb_<?php echo $r->ID ?>" name="todo_status_<?php echo $r->ID ?>" class="todo_cb" value="<?php echo $r->ID ?>" <?php if($r->status) echo"checked" ?>>
                  <div class="pull-right">
                    <button class="btn btn-warning btn-xs" onclick="delete_todo(<?php echo $r->ID ?>)"><span class="glyphicon glyphicon-trash"></span></a></button> 
                  </div>
	            </div>
                </div>
            <?php endforeach; ?>

        </div>

  	<?php else : ?>
	    <?php echo $note->body ?>
	<?php endif; ?>
<hr>
<?php echo $this->common->get_user_display(array("avatar" => $note->avatar, "username" => $note->username, "online_timestamp" => $note->online_timestamp));?> - <?php echo lang("ctn_757") ?>: <?php echo date($this->settings->info->date_format, $note->timestamp) ?> <?php if($note->last_updated_timestamp > 0) : ?>- <?php echo lang("ctn_756") ?>: <?php echo date($this->settings->info->date_format, $note->last_updated_timestamp) ?><?php endif; ?> - <a href="<?php echo site_url("notes/edit_note/" . $note->ID) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("notes/delete_note/" . $note->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a>


</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-plus"></span> <?php echo lang("ctn_620") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("notes/add_todo_note/" . $note->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group" id="note-todo">
                  <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1474") ?></label>
                    <div class="col-md-8 ui-front">
                      <div id="todolist">
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #1 ..." name="todo_1" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #2 ..." name="todo_2" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #3 ..." name="todo_3" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #4 ..." name="todo_4" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #5 ..." name="todo_5" class="form-control"></p>
                     </div>
                     <input type="hidden" name="todo_count" value="5" id="todo_count">
                     <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1476") ?>" id="addAnother">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1429") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	$('.todo_cb').on("change", function() {
		// Get ID
		var id = $(this).val();

		var data = 0;
		if($(this).is(":checked")) {
			data = 1;
		}

		// Update
		$.ajax({
			url: global_base_url + "notes/update_todo_item/" + id + "/" + global_hash,
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


  $('#addAnother').click(function() {
    var count = $('#todo_count').val();
    count++;
    var html = '<p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #'+count+' ..." class="form-control" name="todo_'+count+'"></p>';
    $('#todolist').append(html);
    $('#todo_count').val(count);
  });

});

function delete_todo(id)
{
  $.ajax({
      url: global_base_url + "notes/delete_todo_item/" + id + "/" + global_hash,
      type: "GET",
      data: {
      },
      dataType: 'json',
      success: function(msg) {
        if(msg.error) {
          alert(msg.error_msg);
        }
        $("#todo-fullarea-" + id).fadeOut(100);
      }
    });
}

  </script>
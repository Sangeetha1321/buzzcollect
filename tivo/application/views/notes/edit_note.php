<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo lang("ctn_750") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<div id="saving" style="<?php if($note->type == 1) : ?>display: none;<?php endif; ?>"></div>
<div class="form-group" id="autosave" style="<?php if($note->type == 1) : ?>display: none;<?php endif; ?>">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1150") ?></label>
        <div class="col-md-8 ui-front">
            <input type="checkbox" name="enable_autosave" id="autosave" value="1" checked>
            <span class="help-block"><?php echo lang("ctn_1151") ?></span>
        </div>
</div>
<?php echo form_open(site_url("notes/edit_note_pro/" . $note->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_751") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="title" value="<?php echo $note->title ?>" id="title">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_490") ?></label>
        <div class="col-md-8 ui-front">
            <select name="type" class="form-control" id="note-type">
                <option value="0"><?php echo lang("ctn_752") ?></option>
                <option value="1" <?php if($note->type == 1) echo "selected" ?>><?php echo lang("ctn_1473") ?></option>
            </select>
        </div>
</div>
<div class="form-group" id="note-editor" style="<?php if($note->type == 1) : ?>display: none;<?php endif; ?>">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_752") ?></label>
        <div class="col-md-8 ui-front">
           <textarea name="note" id="notearea"><?php echo $note->body ?></textarea>
        </div>
</div>
<div class="form-group" id="note-todo" style="<?php if($note->type == 0) : ?>display: none;<?php endif; ?>">
                  <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1474") ?></label>
                    <div class="col-md-8 ui-front">
                      <div id="todolist">
                        <?php foreach($todos->result() as $r) : ?>
                            <p><div class="input-group">
                            <input type="text" name="todo_l_<?php echo $r->ID ?>" class="form-control" value="<?php echo $r->todo ?>">
                            <span class="input-group-addon" id="basic-addon1"><input type="checkbox" name="todo_status_<?php echo $r->ID ?>" value="1" <?php if($r->status) echo"checked" ?>></span>
                            </div></p>
                        <?php endforeach; ?>
                        <p><div class="input-group">
                       <input type="text" placeholder="<?php echo lang("ctn_1475") ?>" name="todo_1" class="form-control">
                       <span class="input-group-addon" id="basic-addon1"><input type="checkbox" name="todo_s_1" value="1"></span>
                        </div></p>
                     </div>
                     <input type="hidden" name="todo_count" value="1" id="todo_count">
                     <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1476") ?>" id="addAnother">
                    </div>
            </div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_753") ?></label>
        <div class="col-md-8 ui-front">
            <select name="projectid" class="form-control" id="projectid">
            <?php foreach($projects->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $note->projectid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div> 
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1477") ?></label>
        <div class="col-md-8 ui-front">
            <input type="checkbox" name="personal" value="1" <?php if($note->personal) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_1478") ?></span>
        </div>
</div> 
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1479") ?></label>
        <div class="col-md-8 ui-front">
            <input type="checkbox" name="pinned" value="1" <?php if($note->pinned) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_1480") ?></span>
        </div>
</div> 


<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_754") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {


  $('#note-type').change(function() {
    var val = $("#note-type").val();
    if(val == 1) {
      $('#note-editor').css("display", "none");
      $('#note-todo').css("display", "block");

      $('#autosave').css("display", "none");
      $('#savinf').css("display", "none");
    } else {
      $('#note-editor').css("display", "block");
      $('#autosave').css("display", "block");
      $('#savinf').css("display", "block");
      $('#note-todo').css("display", "none");
    }
  });

  $('#addAnother').click(function() {
    var count = $('#todo_count').val();
    count++;
    var html = '<p><div class="input-group">'
                      +'<input type="text" placeholder="<?php echo lang("ctn_1475") ?>" name="todo_'+count+'" class="form-control">'
                    +'<span class="input-group-addon" id="basic-addon1"><input type="checkbox" name="todo_s_'+count+'" value="1"></span>'
                    +'</div></p>';
    $('#todolist').append(html);
    $('#todo_count').val(count);
  });


    function autosave() 
    {
        var autosave = $('#autosave').prop("checked");
        if(autosave) {
            $.ajax({
            url: global_base_url + "notes/edit_note_ajax/<?php echo $note->ID ?>",
            type: "POST",
            data: {
                csrf_test_name : global_hash,
                title : $("#title").val(),
                note : CKEDITOR.instances.notearea.getData(),
                projectid : $('#projectid').val()
            },
            success: function(msg) {
                $('#saving').html("<?php echo lang("ctn_1152") ?>");
                console.log("autosave");
            }
        });
            
        }
    }

    setInterval(function() {
        autosave();
    }, 1000 *30);
CKEDITOR.replace('notearea', { height: '350'});
});
</script>
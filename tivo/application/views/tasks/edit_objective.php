<?php echo form_open(site_url("tasks/edit_objective_pro/" . $objective->ID), array("class" => "form-horizontal")) ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_838") ?></h4>
  </div>
  <div class="modal-body">
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_839") ?></label>
            <div class="col-md-8 ui-front">
               <input type="text" class="form-control" name="title" value="<?php echo $objective->title ?>" />
            </div>
    </div>
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_840") ?></label>
            <div class="col-md-8 ui-front">
               <textarea name="description" id="objective-area-e"><?php echo $objective->description ?></textarea>
            </div>
    </div>
	<?php if($objective->unit == 'Page'){$target = $objective->totalPages;}else{$target = $objective->totalArticles;}?>
	<input type="hidden" id="target" class="form-control" name="target" value="<?php echo $target; ?>">
	<div class="form-group" style="<?php if($objective->complete){echo "display:none";}else{echo "display:block";} ?>">
			<label for="p-in" class="col-md-4 label-heading"><?php echo "Target Completion Count"; ?></label>
			<div class="col-md-8 ui-front">
			<input type="text" id="subtask_edit_target_complete" class="form-control" name="subtask_edit_target_complete" value="<?php echo $objective->productivity_count; ?>">
			</div>
	</div>
	<input type="hidden" id="subtask_edit_target_percentage" class="form-control" name="subtask_edit_target_percentage" value="<?php echo $objective->productivity_percentage; ?>">
	<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) : ?>		
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_841") ?></label>
            <div class="col-md-8 ui-front">
               <?php foreach($task_members->result() as $r) : ?>
               	<div class="task-objective-user">
               	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="user-icon" /> <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a>
               	<input id="anyone_check" type="checkbox" name="user_<?php echo $r->ID ?>" value="1" <?php if(in_array($r->userid, $objective_members_ids)) : ?> checked<?php endif; ?>>
               	</div>
               <?php endforeach; ?>
            </div>
    </div>
	<?php endif; ?>
	<?php 
		/* print_r($project_tasks_chaptername); */
		$chapters = $project_tasks_chaptername[0]['chaptername']; 
		$chapters_list = explode(",",$chapters);
		?>
		<div class="form-group">
			<label for="p-in" class="col-md-4 label-heading"><?php echo "Chapters"; ?></label>
			<div class="col-md-8 ui-front">
				<select multiple name="chapt_list[]" id="chapt_list" class="form-control">
					<option value="">Select Chapters</option>
					<?php foreach($chapters_list as $chl) : ?>
					<option value="<?php echo $chl; ?>" <?php $chap = explode(",",$objective->chapters); if(in_array($chl, $chap)){echo "selected";}  ?>><?php echo $chl; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	<?php 
	if($objective->started != 1) :  ?>
	<div class="form-group" id="start">
            <label for="p-in" class="col-md-4 label-heading"><?php echo "Task Start" ?></label>
            <div class="col-md-8 ui-front">
            <input type="checkbox" id="started" name="started" value="1" <?php if($objective->started) : ?>checked<?php endif; ?> />
			
			<?php $default_timezone = date_default_timezone_set('Asia/Kolkata'); ?>
			<input type="hidden" name="start_date" value="<?php echo date("Y-m-d"); ?>" />
            <input type="hidden" name="start_time" value="<?php echo date('H:i:s'); ?>" />
			</div>
    </div>
	<?php endif; ?>
	<?php 
	if($objective->started == 1) :  ?>
    <div class="form-group" id="completed">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_842") ?></label>
            <div class="col-md-8 ui-front">
            <input id="complete" type="checkbox" name="complete" value="1" <?php if($objective->complete) : ?>checked<?php endif; ?> />
			
			<?php $default_timezone = date_default_timezone_set('Asia/Kolkata'); ?>
			<input type="hidden" name="completed_datetime" value="<?php echo date("Y-m-d H:i:s"); ?>" />
			<input type="hidden" name="completed_date" value="<?php echo date("Y-m-d"); ?>" />
            <input type="hidden" name="completed_time" value="<?php echo date('H:i:s'); ?>" /> 
			</div>
    </div>
	<?php endif; ?>
	<?php if(($this->common->has_permissions(array("admin", "project_admin"), $this->user)) && ($objective->complete == 1)) { ?>
		<div class="form-group">
			<label for="p-in" class="col-md-4 label-heading"><?php echo "Work again" ?></label>
			<div class="col-md-8 ui-front">
				<input type="radio" id="hldrwip" name="hldrwip" value="rework" <?php if($objective->task_status == 'rework' && $objective->complete != 1) : ?>checked<?php endif; ?> /> REWORK
				<?php $default_timezone = date_default_timezone_set('Asia/Kolkata'); ?>
			<input type="hidden" name="rework_datetime" value="<?php echo date("Y-m-d H:i:s"); ?>" />
          <!--  <input type="hidden" name="rework_time" value="<?php echo date('H:i:s'); ?>" /> -->
			</div>
		</div>	
	<?php } ?>
	<?php if($objective->complete != 1) {  ?>
	<div class="form-group" id="holdorwip">
		<label for="p-in" class="col-md-4 label-heading"><?php echo "Hold/WIP" ?></label>
		<div class="col-md-8 ui-front">
			<input type="radio" id="hldrwip" name="hldrwip" value="On Hold" <?php if($objective->task_status == 'On Hold') : ?>checked<?php endif; ?> /> On Hold 
			<input type="radio" id="hldrwip" name="hldrwip" value="WIP" <?php if($objective->task_status == 'WIP') : ?>checked<?php endif; ?> /> WIP
			<?php $default_timezone = date_default_timezone_set('Asia/Kolkata'); ?>
			<input type="hidden" name="onhold_date" value="<?php echo date("Y-m-d"); ?>" />
            <input type="hidden" name="onhold_time" value="<?php echo date('H:i:s'); ?>" />
		</div>
    </div>
	<?php } else {} ?>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
    <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_838") ?>">
    <?php echo form_close() ?>
  </div>
  </div>

<script type="text/javascript">
CKEDITOR.replace('objective-area-e', { height: '100'});

$('#complete').change(function() {
    if($(this).is(':checked')){
		$("#holdorwip").css("display", "none");
	}
	else{
		$("#holdorwip").css("display", "block");
	}
	$('#hldrwip').prop('checked', false);
});

$('#hldrwip').click(function() {
	$("#completed").css("display", "none");
	$('#complete').prop('checked', false);
});	

$('#started').change(function() {
    if($(this).is(':checked')){
		$("#holdorwip").css("display", "none");
	}
	else{
		$("#holdorwip").css("display", "block");
	}
});
$('#hldrwip').click(function() {
	$("#start").css("display", "none");
});


$(document).ready(function() {
	var target = "<?php echo $target; ?>";
	$("#subtask_edit_target_complete").keyup(function() {
		if ((parseInt($(this).val())) > (parseInt(target))) {
			alert('Should enter value less than or equal to the Target.');
			$(':input[type="submit"]').prop('disabled', true);
		}
		else{
			$(':input[type="submit"]').prop('disabled', false);
			var target_complete = $('#subtask_edit_target_complete').val();
			var bla =((target_complete/target)*100).toFixed(2);
			$('#subtask_edit_target_percentage').val(bla);	
		}	
	});
	});
</script>
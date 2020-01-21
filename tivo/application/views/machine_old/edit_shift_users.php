<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1625") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("machine/edit_shift_users_pro/" . $shift_users->id), array("class" => "form-horizontal")) ?>
	<div class="form-group">
	<label for="team_name" class="col-md-4 label-heading"><?php echo "Team Name"; ?></label>
	<div class="col-md-8 ui-front">
		<select name="team_name" class="form-control">
		<?php foreach($shift_allocation->result() as $r) : ?>
			<option value="<?php echo $r->team_name ?>" <?php if($r->team_name == $shift_users->team_name) echo "selected" ?>><?php echo $r->team_name ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	</div>
	<div class="form-group">
	<label for="member_name" class="col-md-4 label-heading"><?php echo "Member Name"; ?></label>
	<div class="col-md-8 ui-front">
		<input type="text" class="form-control" name="member_name" value="<?php echo $shift_users->member_name ?>">
	</div>
	</div>
	<div class="form-group">
	<label for="shift_type" class="col-md-4 label-heading"><?php echo "Shift Type"; ?></label>
	<div class="col-md-8 ui-front">
		<input type="text" class="form-control" name="shift_type" value="<?php echo $shift_users->shift_type ?>">
	</div>
	</div>
	<input type="hidden" class="form-control" name="current_date" value="<?php echo $current_date; ?>">
      
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1625") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1625") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("machine/edit_shifts_pro/" . $shifts->id), array("class" => "form-horizontal")) ?>
	<div class="form-group">
	<label for="shifts" class="col-md-4 label-heading"><?php echo lang("ctn_1623") ?></label>
	<div class="col-md-8 ui-front">
		<input type="text" class="form-control" name="shifts" value="<?php echo $shifts->shift_type ?>">
	</div>
	</div>
	<div class="form-group">
	<label for="shift_timing" class="col-md-4 label-heading"><?php echo lang("ctn_1624") ?></label>
	<div class="col-md-8 ui-front">
		<input type="text" class="form-control" name="shift_timing" value="<?php echo $shifts->shift_timing ?>">
	</div>
	</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1625") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
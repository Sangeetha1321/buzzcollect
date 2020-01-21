<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1629") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("machine/edit_team_name_pro/" . $team_name->id), array("class" => "form-horizontal")) ?>
	<div class="form-group">
	<label for="team_name" class="col-md-4 label-heading"><?php echo lang("ctn_1626") ?></label>
	<div class="col-md-8 ui-front">
		<input type="text" class="form-control" name="team_name" value="<?php echo $team_name->team_name ?>">
	</div>
	</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1629") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
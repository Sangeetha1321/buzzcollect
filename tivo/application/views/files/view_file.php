<div class="white-area-content">
<!-- 
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_463") ?></div>
    <div class="db-header-extra"> 
</div>
</div> -->

<?php
	/* $url = base_url().$this->settings->info->upload_path_relative.'/'.$file->upload_file_name; */
	$url = 'http://' . $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.'uploadfiles/Input/'.$file->upload_file_name;
				if(!empty($file->file_url)) {
					$url = $file->file_url;
				}
?>

<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_493") ?></td><td><?php echo lang("ctn_494") ?></td></tr>
<tr><td><?php echo lang("ctn_500") ?></td><td><?php if($file->projectid > 0) : ?><?php echo ucfirst($file->project_name); ?><?php else : ?><?php echo lang("ctn_46") ?><?php endif; ?></td></tr>
<tr><td><?php echo "File Format" ?></td><td><?php echo ucfirst($file->format_used); ?></td></tr>
<tr><td><?php echo "Broadcast Day Start Time" ?></td><td><?php echo ucfirst($file->broadcast_day_st_time); ?></td></tr>
<tr><td><?php echo "Date Range" ?></td><td><?php if($file->period == 0){ echo "Entire"; } else{ echo "Custom"; } ?></td></tr>
<?php if($file->period == 1){ ?>
<tr><td><?php echo "Start DateTime" ?></td><td><?php echo $file->st_date; ?></td></tr>
<tr><td><?php echo "End DateTime" ?></td><td><?php echo $file->end_date; ?></td></tr>
<?php } ?>
<tr>
	<td><?php echo "Status" ?></td>
	<td>
		<span class="label label-default" style="<?php echo 'background: #'.$file->file_status_color.';color:#000000;' ?>">
			<?php echo $file->file_status; ?>
		</span>
	</td>
</tr>
<tr><td><?php echo lang("ctn_495") ?></td><td><?php echo ucfirst($file->file_name); ?></td></tr>
<tr><td><?php echo lang("ctn_496") ?></td><td><a href="<?php echo $url ?>"><?php echo $url ?></a></td></tr>
<tr><td><?php echo lang("ctn_497") ?></td><td><?php echo $file->file_type ?></td></tr>
<tr><td><?php echo lang("ctn_498") ?></td><td><?php echo $file->file_size ?> kb</td></tr>
<tr><td><?php echo lang("ctn_499") ?></td><td><?php echo date($this->settings->info->date_format, $file->timestamp) ?></td></tr>
<tr><td><?php echo lang("ctn_501") ?></td><td><a href="<?php echo site_url("profile/" . $file->username) ?>"><?php echo $file->username ?></a></td></tr>
</table>

<h4><span class="glyphicon glyphicon-pushpin"></span> <?php echo lang("ctn_502") ?></h4>

<?php foreach($notes->result() as $r) : ?>
<div class="panel panel-default">
<div class="panel-body">

<div class="user-info-block">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?> <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> - <?php echo date($this->settings->info->date_format, $r->timestamp) ?>
</div>

<div class="file-note-body">
<?php echo $r->note ?>
</div>

<div class="file-note-body small-text">
<?php if($this->user->info->ID == $r->userid || ($this->common->has_team_permissions(array("admin"), $team_member)) || ($this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user)) ) : ?>
<a href="<?php echo site_url("files/delete_file_note/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_503") ?></a>
<?php endif; ?>
</div>

</div>
</div>
<?php endforeach; ?>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<h4><?php echo lang("ctn_504") ?></h4>
 <?php echo form_open(site_url("files/add_file_note/" . $file->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <div class="col-md-12">
            <textarea name="note" id="file-note"></textarea>
        </div>
</div>
<input type="submit" class="form-control btn btn-primary" value="<?php echo lang("ctn_505") ?>">
<?php echo form_close() ?>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
</div>
<script type="text/javascript">
CKEDITOR.replace('file-note', { height: '100'});
</script>
<div class="task-blob">
<table class="table">
<?php foreach($tasks->result() as $r) : ?>
<tr><td width="30">
<a href="<?php echo site_url("tasks/view/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" class="task-icon" data-toggle="tooltip" data-placement="left" title="<?php echo $r->project_name ?>"></a>
</td><td><p class="task-blob-title"><a href="<?php echo site_url("tasks/view/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
<div class="progress" style="margin-bottom: 0px !important;">
  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $r->complete ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $r->complete ?>%; min-width: 2em; font-size: 8px;">
    <?php echo $r->complete ?>%
  </div>
</div>
</td></tr>
<?php endforeach; ?>
</table>
<div class="align-center">
<?php if($this->common->has_permissions(array("admin"), $this->user)) { ?>
	<a href="<?php echo site_url("tasks") ?>" class="btn btn-primary btn-xs"><?php echo "All Titles"; ?></a>
<?php } else { ?>
	<a href="<?php echo site_url("tasks") ?>" class="btn btn-primary btn-xs"><?php echo "Your Titles"; ?></a>
<?php } ?>
</div>
</div>
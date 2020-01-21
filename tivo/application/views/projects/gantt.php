<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1376") ?></div>
    <div class="db-header-extra form-inline"> <a href="<?php echo site_url("projects/view/" . $project->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_1367") ?></a>

</div>
</div>

<h3><?php echo $project->name ?></h3>

<div class="row">
<div class="col-md-4">
<table class="table table-bordered small-text table-condensed">
<tr class="table-header"><td colspan="3"><?php echo lang("ctn_1370") ?></td></tr>
<tr><td><?php echo lang("ctn_823") ?></td><td><?php echo lang("ctn_1377") ?></td><td><?php echo lang("ctn_547") ?></td></tr>
<?php foreach($tasks->result() as $r) : ?>
<tr><td><a href="<?php echo site_url("tasks/view/" . $r->ID) ?>"><?php echo $r->name ?></a></td><td><?php echo date($this->settings->info->date_picker_format, $r->start_date) ?></td><td><?php echo date($this->settings->info->date_picker_format, $r->due_date) ?></td></tr>
<?php endforeach; ?>
</table>
</div>
<div class="col-md-8">
<div class="table-responsive">
<table class="table table-bordered small-text table-condensed">
<tr class="table-header">
<?php foreach($months as $month) : ?>
<td colspan="<?php echo $month['days'] ?>" align="center"><?php echo $month['display'] ?> - <?php echo $month['year'] ?></td>
<?php endforeach; ?>
</tr>
<tr>
<?php foreach($dates as $date) : ?>
<td><?php echo $date['obj']->format("d") ?></td>
<?php endforeach; ?>
</tr>
<?php foreach($tasks->result() as $task) : ?>
<tr>
<?php 

	if($task->status ==1) {
		$color = "info";
	} elseif($task->status == 2) {
		$color = "active";
	} elseif($task->status == 3) {
		$color = "success";
	} elseif($task->status == 4) {
		$color = "warning";
	} elseif($task->status == 5) {
		$color = "danger";
	}
	
	$days = 0;
	$start_cols = $this->common->getDatesFromRange(date($this->settings->info->date_picker_format, $start_date_range), date($this->settings->info->date_picker_format, $task->start_date), 
			$this->settings->info->date_picker_format, "Y-m-d");
	$task_cols = $this->common->getDatesFromRange(date($this->settings->info->date_picker_format, $task->start_date), date($this->settings->info->date_picker_format, $task->due_date),
			$this->settings->info->date_picker_format, "Y-m-d");
	$end_cols = $this->common->getDatesFromRange(date($this->settings->info->date_picker_format, $task->due_date), date($this->settings->info->date_picker_format, $end_date_range),
			$this->settings->info->date_picker_format, "Y-m-d");

	array_pop($start_cols);
	array_shift($end_cols);
	$task_col_span = count($task_cols);

?>
<?php foreach($start_cols as $col) : ?>
<td></td>
<?php endforeach; ?>
<td class="<?php echo $color ?>" colspan="<?php echo $task_col_span ?>"><?php echo $task->name ?></td>
<?php foreach($end_cols as $col) : ?>
<td></td>
<?php endforeach; ?>

</tr>
<?php endforeach; ?>


</table>
</div>
</div>
</div>



</div>

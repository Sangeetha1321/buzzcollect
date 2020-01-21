<?php $prioritys = array(1 => "<span class='label label-info'>".lang("ctn_531")."</span>", 2 => "<span class='label label-primary'>".lang("ctn_532")."</span>", 3=> "<span class='label label-warning'>".lang("ctn_533")."</span>", 4 => "<span class='label label-danger'>".lang("ctn_534")."</span>"); ?>
<div class="white-area-content">
<div class="row">
<div class="col-md-3">
	<div class="dashboard-window clearfix" style="background: #62acec; border-left: 5px solid #5798d1;">
		<div class="d-w-icon">
			<span class="glyphicon glyphicon-folder-close giant-white-icon"></span>
		</div>
		<div class="d-w-text">
			 <span class="d-w-num">
			 <?php 
			/*  if($this->common->has_permissions(array("admin"), $this->user)) { */
				 foreach($project_total->result() as $r){
				 echo $r->total_projects; }
			/*  } else { 
				echo $projects_count;
			 }  */
			 ?>
			 </span><br /><?php 
			 /* if($this->common->has_permissions(array("admin"), $this->user)) { */
				 echo "Total Sources"; 
			 /* } else { 
				echo lang("ctn_535");
			 } */
			 ?> 
		</div>
	</div>
</div>
<!--
<div class="col-md-3">
	<div class="dashboard-window clearfix" style="background: #5cb85c; border-left: 5px solid #4f9f4f;">
		<div class="d-w-icon">
			<span class="glyphicon glyphicon-tasks giant-white-icon"></span>
		</div>
		<a href="#project_status" id="target_pro_stat">
			<div class="d-w-text">
				 <span class="d-w-num">
					 <?php 
					 if($this->common->has_permissions(array("admin"), $this->user)) {
						 foreach($titles_total->result() as $r){
						 echo $r->total_titles; }
					 } else { 
						echo $tasks_count;
					 } 
					 ?>
				 </span><br />
				 <?php 
					 if($this->common->has_permissions(array("admin"), $this->user)) {
						 echo "Total Titles"; 
					 } else { 
						echo lang("ctn_536");
					 }
				 ?>
			</div>
		</a>
	</div>
</div>
<?php if($this->settings->info->enable_time) : ?>
	<div class="col-md-3">
		<div class="dashboard-window clearfix" style="background: #f0ad4e; border-left: 5px solid #d89b45;">
			<div class="d-w-icon">
				<span class="glyphicon glyphicon-time giant-white-icon"></span>
			</div>
			<div class="d-w-text">
				 <span class="d-w-num"><?php echo $time_count ?></span><br /><?php echo lang("ctn_537") ?>
			</div>
		</div>
	</div>
<?php endif; ?>
-->
<div class="col-md-3">
	<div class="dashboard-window clearfix" style="background: #07db55; border-left: 5px solid #07b547;">
		<div class="d-w-icon">
			<span class="glyphicon glyphicon-user giant-white-icon"></span>
		</div>
		<div class="d-w-text">
			 <span class="d-w-num"><?php echo $online_count ?></span><br /><?php echo lang("ctn_139") ?>
		</div>
	</div>
</div>
</div>
<hr>

<div class="row">
<div class="col-md-12">
<?php if($this->settings->info->enable_finance && $this->common->has_permissions(array("admin", "project_admin", "finance_worker", "finance_manage"), $this->user)) : ?>
<!-- <div class="block-area align-center">
<h4 class="home-label"><?php echo lang("ctn_538") ?></h4>
<div class="finance-blob">
<p class="finance-blob-unit"><?php echo $this->settings->info->fp_currency_symbol ?><span id="num1">0</span></p>
<?php echo lang("ctn_539") ?>
</div>
<div class="finance-blob">
<p class="finance-blob-unit"><?php echo $this->settings->info->fp_currency_symbol ?><span id="num2">0</span></p>
<?php echo lang("ctn_540") ?>
</div>
<div class="finance-blob">
<p class="finance-blob-unit"><?php echo $this->settings->info->fp_currency_symbol ?><span id="num3">0</span></p>
<?php echo lang("ctn_541") ?>
</div>
<canvas id="myChart" class="graph-height"></canvas>
</div> -->
<?php endif; ?>

<div class="content-separator block-area">
<h4 class="home-label"><?php echo lang("ctn_766") ?></h4>
<?php foreach($client_projects->result() as $r) : ?>
<div class="fp-project">
	<p><a href="<?php echo site_url("projects/view/" . $r->ID) ?>">
	<!-- <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>"  data-toggle="tooltip" data-placement="bottom" title="<?php echo strip_tags($r->description) ?>" width="149" height="65"> --> 
	<img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo "default.png" ?>"  data-toggle="tooltip" data-placement="bottom" title="<?php echo strip_tags($r->description) ?>" width="149" height="65">
	</a></p>
	<p><a href="<?php echo site_url("projects/view/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
	<!-- <div class="progress" style="height: 15px;">
	    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $r->complete ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $r->complete ?>%" title="<?php echo $r->complete ?>%" data-toggle="tooltip" data-placement="bottom">
	        <span class="sr-only">
				<?php echo $r->complete ?>% <?php echo lang("ctn_790") ?>
			</span>
	    </div>
	</div> -->
	<?php if($this->common->has_permissions(array("project_client"), $this->user)) : ?>
		<p>
		<a href="<?php echo site_url("tasks/client/" . $r->ID) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_1204") ?></a>
		</p>
	<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<br>
<div class="white-area-content">
	<div class="db-header clearfix">
		<h4 class="home-label"><?php echo "Source Status"; ?></h4>
	</div>
	<div class="source_status"> 
		<div class="row">
		<?php  foreach($src_status->result() as $prostat){ ?>
			<div class="col-md-3">
				<div class="dashboard-window clearfix" style="background: <?php echo '#'.$prostat->color.';'; ?> border-left: 5px solid <?php echo '#'.$prostat->color.';'; ?>">
					<!-- <div class="d-w-icon">
					<span class="glyphicon glyphicon-folder-close giant-white-icon"></span>
					</div> -->
					<div class="d-w-text source_mon">
						<span class="d-w-num"><?php echo $prostat->status_count; ?></span>
						<br><?php echo $prostat->name; ?>  
					</div>
				</div>
			</div>
		<?php } ?>  
		</div>
	</div>
</div>

<br>
<div class="white-area-content">
	<div class="db-header clearfix">
		<div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo "File Monitoring"; ?></div>
	</div>
	<div class="file_monitoring"> 
		<div class="row">
		<?php  foreach($project_status->result() as $prostat){ ?>
			<div class="col-md-3">
				<div class="dashboard-window clearfix" style="background: <?php echo '#'.$prostat->status_color.';'; ?> border-left: 5px solid <?php echo '#'.$prostat->status_color.';'; ?>">
					<!-- <div class="d-w-icon">
					<span class="glyphicon glyphicon-folder-close giant-white-icon"></span>
					</div> -->
					<div class="d-w-text monitor_files">
						<span class="d-w-num"><?php echo $prostat->status_count; ?></span>
						<br><?php echo $prostat->status_name; ?>  
					</div>
				</div>
			</div>
		<?php } ?>  
		</div>
	</div>
</div>

<?php if($this->settings->info->enable_invoices && $this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) : ?>
<!-- <div class="content-separator block-area">
<h4 class="home-label"><?php echo lang("ctn_542") ?></h4>
<div class="table-responsive">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_543") ?></td><td width="60"><?php echo lang("ctn_544") ?></td><td width="60"><?php echo lang("ctn_545") ?></td><td width="40"><?php echo lang("ctn_546") ?></td><td><?php echo lang("ctn_547") ?></td></tr>
<?php foreach($invoices->result() as $r) : ?>
	<?php
	if($r->status == 1) {
		$status = "<label class='label label-danger'>".lang("ctn_548")."</label>";
	} elseif($r->status == 2) {
		$status = "<label class='label label-success'>".lang("ctn_549")."</label>";
	} elseif($r->status == 3) {
		$status = "<label class='label label-default'>".lang("ctn_550")."</label>";
	} elseif($r->status == 4) {
		$status = "<label class='label label-warning'>".lang("ctn_1430")."</label>";
	}
	?>
<tr><td><a href="<?php echo site_url("invoices/view/" . $r->ID . "/" . $r->hash) ?>"><?php echo $r->title ?></a></td><td><?php echo $r->symbol ?><?php echo number_format($r->total, 2) ?></td><td><?php echo $status ?></td><td> <?php echo $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp)) ?></td><td><?php echo date($this->settings->info->date_format, $r->due_date) ?></td></tr>
<?php endforeach; ?>
</table>
</div>
</div> -->
<?php endif; ?>

<?php if($this->settings->info->enable_invoices && $this->common->has_permissions(array("invoice_client"), $this->user)) : ?>
<div class="content-separator block-area">
	<h4 class="home-label"><?php echo lang("ctn_551") ?></h4>
	<div class="table-responsive">
		<table class="table small-text table-bordered table-striped table-hover">
			<tr class="table-header">
				<td><?php echo lang("ctn_543") ?></td><td width="60"><?php echo lang("ctn_544") ?></td>
				<td width="60"><?php echo lang("ctn_545") ?></td><td width="40"><?php echo lang("ctn_546") ?></td>
				<td><?php echo lang("ctn_547") ?></td>
			</tr>
			<?php foreach($client_invoices->result() as $r) : ?>
				<?php
				if($r->status == 1) {
					$status = "<label class='label label-danger'>".lang("ctn_548")."</label>";
				} elseif($r->status == 2) {
					$status = "<label class='label label-success'>".lang("ctn_549")."</label>";
				} elseif($r->status == 3) {
					$status = "<label class='label label-default'>".lang("ctn_550")."</label>";
				} elseif($r->status == 4) {
					$status = "<label class='label label-warning'>".lang("ctn_1430")."</label>";
				}
				?>
			<tr>
				<td><a href="<?php echo site_url("invoices/view/" . $r->ID . "/" . $r->hash) ?>">
				<?php echo $r->title ?></a></td>
				<td><?php echo $r->symbol ?><?php echo number_format($r->total, 2) ?></td>
				<td><?php echo $status ?></td>
				<td> <?php echo $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp)) ?></td>
				<td><?php echo date($this->settings->info->date_format, $r->due_date) ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
<?php endif; ?>

<?php if($this->settings->info->enable_tickets && $this->common->has_permissions(array("ticket_client"), $this->user)) : ?>
<div class="content-separator block-area">
<h4 class="home-label"><?php echo lang("ctn_552") ?></h4>
<div class="table-responsive">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_543") ?></td><td><?php echo lang("ctn_553") ?></td><td><?php echo lang("ctn_545") ?></td><td><?php echo lang("ctn_554") ?></td><td><?php echo lang("ctn_555") ?></td></tr>
<?php $statuses = array(1=>lang("ctn_556"), 2 => lang("ctn_557"), 3 => lang("ctn_558"));?>
<?php foreach($client_tickets->result() as $r) : ?>
<tr><td><?php echo $r->title ?></td><td><?php echo $prioritys[$r->priority] ?></td><td><?php echo $statuses[$r->status] ?></td><td><?php echo date($this->settings->info->date_format, $r->last_reply_timestamp) ?></td><td><a href="<?php echo site_url("tickets/view/" . $r->ID) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_555") ?></a></td></tr>
<?php endforeach; ?>
</table>
</div>
</div>
<?php endif; ?>
<!--
<div class="content-separator block-area">
<?php echo form_open(site_url("home/index/"), array("class" => "form-horizontal","id" => "project_status","name" => "project_status")) ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="form-group">	
		<label for="teamgroup-in" class="col-md-4 label-heading"><?php echo "Select Project"; ?></label>
		<div class="col-md-8">
		<select name="project_group" id="project_group">	
		<?php if($this->common->has_permissions(array("admin"), $this->user)) { ?>
		<option value=""><?php echo "Select any Project"; ?></option> 
		<?php if(isset($project_list)){ foreach($project_list as $pro) { ?>
			<option value="<?php echo $pro->ID; ?>" <?php if ($project_group == $pro->ID) echo 'selected="selected"'; ?> >
				<?php echo $pro->name; ?>
			</option> 	
		<?php }} ?>	
		<?php } else{ 
		/* $string_version = implode(',',$project_list);  */
		$a = 0;
		$len = count($projects_list);
		$val = "";
		for ($i=0; $i<count($projects_list); $i++) {
			if (@is_array($projects_list[$i])) {
				foreach($projects_list[$i] as $key => $value){
				if ($a == $len-1) {
					/* $val .= "'".$value."'"; */
					$val .= $value;
				}
				else{
					/* $val .= "'".$value."',"; */
					$val .= $value.',';
				}
				
				}
			}
			$a++;
		} 
		?>
		<option value="<?php echo $val; ?>"><?php echo "Select any Project"; ?></option>
		<?php if(isset($project_list)){ foreach($project_list as $pro) { ?>
			<option value="<?php echo $pro->ID; ?>" <?php if ($project_group == $pro->ID) echo 'selected="selected"'; ?> >
				<?php echo $pro->name; ?>
			</option> 	
		<?php }} ?>	
		<?php }  ?>
				 
		</select>
		<input style="display:none;" type="submit" class="btn btn-primary" value="<?php echo "Submit"; ?>">
		</div>	
	</div>	
</div>
<?php echo form_close() ?>
<h4 class="home-label"><?php echo "Title's count based on Status and project"; ?>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('home/export')?>" title="Export Csv">Export Csv</a>
</h4>
<div class="table-responsive">
	<table class="table small-text table-bordered table-striped table-hover">
		<tr class="table-header">
			<td><?php echo "Project Name"; ?></td>
			<td><?php echo "Status"; ?></td>
			<td><?php echo "Count"; ?></td>
		</tr>
	<?php foreach($projectwise_status as $r) : ?>
		<tr>
			<td><a href="<?php echo site_url("projects/view/" . $r['ID']); ?>"><?php echo $r['project_name']; ?></a></td>
			<td> 
				<table class="table small-text table-bordered table-striped table-hover">	
				<tr>
					<?php 
						$str = $r['tasks_status']; 
						$arr = explode(',', $str);
						$vals = array_count_values($arr);
						$i = 0;
						$len = count($vals);
						foreach($vals as $key => $val) : 
					?>
					<td><?php echo "<b>".$key.": </b>". $val; ?></td>
					<?php endforeach; $i++; ?>
				</tr>
				</table>
			</td>
			<td><?php echo $r['tasks_status_count']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>
</div> -->
</div>
<div class="col-md-3" style="display:none;">
<?php if( ($this->settings->info->enable_tasks && $this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) || ($this->settings->info->enable_notes && $this->common->has_permissions(array("admin", "project_admin", "notes_manage"), $this->user)) ) : ?>
<div class="panel panel-default">
<div class="panel-body" id="scroll-window">

<p><?php if($this->settings->info->enable_tasks && $this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user) ) : ?><button type="button" class="btn btn-flat btn-xs" id="tasks-btn" onclick="get_tasks()">Titles</button><?php endif; ?> <?php if($this->settings->info->enable_notes && $this->common->has_permissions(array("admin", "project_admin", "notes_manage", "notes_worker"), $this->user)) : ?><button type="button" id="notes-btn" class="btn btn-flat btn-xs" onclick="get_notes()">Notes</button><?php endif; ?></p>

<div id="ajax_tasks">

</div>

</div>
</div>
<?php endif; ?>

<?php if($this->settings->info->enable_tickets && $this->common->has_permissions(array("admin", "project_admin", "ticket_worker", "ticket_manage"), $this->user)) : ?>
<!-- <div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_563") ?></h4>
<table class="table">
<?php foreach($tickets->result() as $r) : ?>
<tr><td width="30">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
</td><td><p class="task-blob-title"><a href="<?php echo site_url("tickets/view/" . $r->ID) ?>"><?php echo $r->title ?></a></p>
<p><?php echo $prioritys[$r->priority] ?> <label class="label label-primary label-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_564") ?> <?php echo date($this->settings->info->date_format, $r->last_reply_timestamp) ?>"><span class="glyphicon glyphicon-time"></span></label></p>
</td></tr>
<?php endforeach; ?>
</table>
</div>
</div> -->
<?php endif; ?>

<?php if($this->settings->info->enable_services && $this->settings->info->dashboard_services) : ?>
<!-- <div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_1215") ?></h4>
<table class="table">
<?php foreach($services->result() as $r) : ?>
<tr><td><a href="<?php echo site_url("services/view_service/" . $r->ID) ?>"><?php echo $r->title ?></a></td><td><?php echo $r->symbol ?><?php echo number_format($r->cost, 2) ?></td></tr>
<?php endforeach; ?>
</table>
</div>
</div> -->
<?php endif; ?>

<?php if($this->settings->info->enable_time && $this->common->has_permissions(array("admin", "project_admin", "time_worker", "time_manage"), $this->user)) : ?>
<!-- <div class="panel panel-default">
<div class="panel-body" id="projectTypesChartArea">
<h4 class="home-label"><?php echo lang("ctn_565") ?></h4>
<canvas id="projectTypesChart"></canvas>
</div>
</div>
-->
<?php endif; ?>
<!-- 
<div class="panel panel-default">
<div class="panel-body" id="projectTypesChartArea">
<h4 class="home-label"><?php echo lang("ctn_566") ?></h4>
<table class="table">
<?php foreach($activity->result() as $r) : ?>
<tr><td width="30">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
</td><td><p class="task-blob-title"><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> <?php echo $r->message ?></p>
<p class="task-blob-date"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
</td></tr>
<?php endforeach; ?>
</table>
</div>
</div>
-->


<!--
<div class="content-separator block-area">
<h4 class="home-label"><?php echo "Member's Productivity - Weekly"; ?></h4>
<div class="table-responsive" id="daily_mem_productivity">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo "Member"; ?></td>
	<td><?php echo "Team"; ?></td>
	<td><?php echo "Productivity"; ?></td>
	
</tr>
<?php 
foreach($members_weekly_productivity->result() as $mwp) :
$userid = $mwp->userid;
$username = $mwp->username;
$avatar = $mwp->avatar;
$online_timestamp = $mwp->online_timestamp;
$team_name = $mwp->team_name;
$percent = $mwp->percent;
?>
<tr>
	<td> 
		<?php echo $this->common->get_user_display(array("username" => $username, "avatar" => $avatar, "online_timestamp" => $online_timestamp)) ?>
		<span><?php echo $username; ?></span>
	</td>
	<td>
	<?php echo $team_name; ?>
	</td>
	<td>
		<?php 
			echo round($percent,2)."%"; 			
		?>
	</td>
</tr>
<?php
endforeach; ?>
</table>
</div>
</div>
-->
<!--
<div class="content-separator block-area">

<?php echo form_open(site_url("home/index/"), array("class" => "form-horizontal","id" => "date_submit","name" => "date_submit")) ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">
		<label for="date-in" class="col-md-5 label-heading"><?php echo "From Date"; ?></label>
		<div class="col-md-7">
			<input name="from_date" type="text" id="from_date" class="form-control datepicker" value="<?php if(isset($from_date)&&($from_date!='')){echo $from_date;} ?>">
		</div>
		</div>
		<div class="form-group">
		<label for="date-in" class="col-md-5 label-heading"><?php echo "To Date"; ?></label>
		<div class="col-md-7">
			<input name="to_date" type="text" id="to_date" class="form-control datepicker" value="<?php if(isset($to_date)&&($to_date!='')){echo $to_date;} ?>">
		</div>
		</div>
		<div class="form-group">	
		<label for="teamgroup-in" class="col-xs-3 col-md-5 label-heading"><?php echo "Team"; ?></label>
		<div class="col-xs-9 col-md-7">
		<select style="width: 100%;" name="team_group" id="team_group">	
				<?php if($this->common->has_permissions(array("admin"), $this->user)) { ?>	
				<option value="">All teams</option>				
				<?php if(isset($team_list)){ foreach($team_list as $team) { ?>
					<option value="<?php echo $team->team_name; ?>" <?php if ($team_group == $team->team_name) echo 'selected="selected"'; ?> >
						<?php echo $team->team_name; ?>
					</option>	
				<?php }}
					} else {
				?>	
					<?php if(isset($team_list)){ foreach($team_list as $team) { ?>
					<option value="<?php echo $team->team_name; ?>" <?php if ($team_group == $team->team_name) echo 'selected="selected"'; ?> >
						<?php echo $team->team_name; ?>
					</option>	
				<?php }} ?>
				<?php } ?>					
		</select>
		</div>	
		</div>	
			<input style="width:100%;display:none;" type="submit" class="btn btn-primary" value="<?php echo "Generate Productivity"; ?>">
			<?php echo form_close() ?>
</div>
</form>

<h4 class="home-label"><?php echo "Member's Productivity - Monthly"; ?></h4>

<div class="table-responsive" id="daily_mem_productivity">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo "Member"; ?></td>
	<td><?php echo "Team"; ?></td>
	<td><?php echo "Productivity"; ?></td>
	
</tr>
<?php 
foreach($members_monthly_productivity->result() as $mmp) :
$userid = $mmp->userid;
$username = $mmp->username;
$avatar = $mmp->avatar;
$online_timestamp = $mmp->online_timestamp;
$team_name = $mmp->team_name;
$percent = $mmp->percent;
?>
<tr>
	<td> 
		<?php echo $this->common->get_user_display(array("username" => $username, "avatar" => $avatar, "online_timestamp" => $online_timestamp)) ?>
		<span><?php echo $username; ?></span>
	</td>
	<td>
	<?php echo $team_name; ?>
	</td>
	<td>
		<?php 
			echo round($percent,2)."%"; 			
		?>
	</td>
</tr>
<?php
endforeach; ?>
</table>
</div>
</div>


<div class="content-separator block-area">
<h4 class="home-label"><?php echo "Member's Productivity - Daily"; ?></h4>
<div class="table-responsive" id="daily_mem_productivity">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo "Member"; ?></td>
	<td><?php echo "Team"; ?></td>
	<td><?php echo "Productivity"; ?></td>
	
</tr>
<?php /* foreach($all_prod_members->result() as $apm) : 
$apmcount = 0;
$apmsum = 0;
foreach($members_daily_productivity->result() as $mdp) :
if($mdp->userid == $apm->userid){
$apmsum+= $mdp->productivity_percentage;
$userid = $mdp->userid;
$username = $mdp->username;
$avatar = $mdp->avatar;
$online_timestamp = $mdp->online_timestamp;
$apmcount++;
}
endforeach; 
if($apmsum > 0){ */
foreach($members_daily_productivity->result() as $mdp) :
$userid = $mdp->userid;
$username = $mdp->username;
$avatar = $mdp->avatar;
$online_timestamp = $mdp->online_timestamp;
$team_name = $mdp->team_name;
$percent = $mdp->percent;
?>
<tr>
	<td> 
		<?php echo $this->common->get_user_display(array("username" => $username, "avatar" => $avatar, "online_timestamp" => $online_timestamp)) ?>
		<span><?php echo $username; ?></span>
	</td>
	<td>
	<?php echo $team_name; ?>
	</td>
	<td>
		<?php 
			/* $daily_users_productivity = $apmsum/$apmcount; 
			echo round($daily_users_productivity,2)."%"; */
			echo round($percent,2)."%"; 			
		?>
	</td>
</tr>
<?php
/* } */
endforeach; ?>
</table>
</div>
</div> -->
<?php if($this->common->has_permissions(array("admin"), $this->user)) { ?>
<!-- <div class="content-separator block-area" style="float: left; width: 100%;">
	<h4 class="home-label"><?php echo "Overall Status"; ?>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('home/overall')?>" title="Export Csv">Export</a>
	</h4>
	<div class="table-responsive" style="float: left; width: 100%;">
		<table class="table small-text table-bordered table-striped table-hover">
			<tr class="table-header">
				<td><?php echo "Status"; ?></td>
				<td><?php echo "Count"; ?></td>
			</tr>
			<?php foreach($projectstatus_overall->result() as $r) : ?>
			<tr>
				<td> <?php echo $r->tasks_status; ?></td>
				<td> <?php echo $r->tasks_status_count; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div> -->
<?php } ?>
</div>
<!--
<div class="row">
<div class="col-sm-12">
	<div class="white-area-content" style="margin: 21px;">
	<div class="db-header clearfix">
		<div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> 
		<?php echo "Shift Details"; ?>
		<small>
		<?php $day = date('w')-1; 
			$week_start = date('M d', strtotime('-'.$day.' days'));
			$week_end = date('M d', strtotime('+'.(6-$day).' days')); 
			echo '('.$week_start.' - '.$week_end.')';
		?></small>
		</div>
		<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('machine/exportallshiftdetails')?>" title="Export Csv">
			Export Csv
		</a>
	</div>
	<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover">
	<tr class="table-header">
		<?php foreach($shift_details as $r) : ?>
			<td><?php echo $r['shift_type']; ?></td>
		<?php endforeach; ?>
	</tr>
	<tr>
	<?php foreach($shift_details as $r) : ?>
		<td><table class="table table-bordered table-striped table-hover">
		
			<?php 
			$mem_nom = $r['member_name']; 
			$mem_array = explode(',', $mem_nom);
			$team_nom = $r['team_name']; 
			$team_array = explode(',', $team_nom);
			$c = array_combine($mem_array, $team_array);
			?>
			<?php $count = 0;
			foreach($c as $k => $v) { ?>
			<tr>
				<td><?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; 
				//echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($mem))))."<br>";?>
				</td>
			</tr>
			<?php $count++; } ?>
			<tr><td><b><?php echo "Total Members: " .$count;?></b></td></tr>
			</table></td>
	<?php endforeach; ?>
	</tr> 
	</table>
	</div>
	</div>
</div>
</div>
<div class="row">
<div class="col-sm-12">

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
	<div class="content-separator block-area">
	<div id="monthly_percent"></div>
	</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
	<div class="content-separator block-area">
	<div id="daily_percent"></div>
	</div>
	</div>
</div>
</div>
-->
<!-- <div class="row">
<div class="col-sm-12">
	<div class="content-separator block-area">
	<h4 class="home-label"><?php echo lang("ctn_559") ?></h4>
	<div class="table-responsive">
	<table class="table small-text table-bordered table-striped table-hover">
	<tr class="table-header"><td width="30"><?php echo lang("ctn_560") ?></td><td><?php echo lang("ctn_543") ?></td><td><?php echo lang("ctn_561") ?></td><td><?php echo lang("ctn_555") ?></td></tr>
	<?php foreach($notifications->result() as $r) : ?>
	<tr><td> <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?></td><td><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> <?php echo $r->message ?></td><td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td><td><a href="<?php echo site_url("home/load_notification/" . $r->ID) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_555") ?></a></td></tr>
	<?php endforeach; ?>
	</table>
	</div>
	</div>
</div>
</div>-->

</div>
</div>

<script type="text/javascript">
Highcharts.chart('daily_percent', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: "<?php if($team_group == ''){echo "All teams";}else{echo $team_group;}?> - Daily",
        align: 'center',
        verticalAlign: 'middle',
        y: 40
    },
	subtitle: {
        text: 'Productivity<br>Percentage',
		align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}%</b>'
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        },
		series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y}%)'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Productivity',
        innerSize: '50%',
        data: [
        <?php 
		$daily = 0; $len = count($members_daily_productivity->result());
		foreach($members_daily_productivity->result() as $mdp) :
				$username = $mdp->username;
				$team_name = $mdp->team_name;
				$percent = $mdp->percent; 
				?>
            ['<?php echo $username; ?>', <?php echo round($percent,2); ?>]<?php if($daily != $len - 1){ echo ",";} ?>
        <?php $daily++; 
		endforeach; 
		?>
        ]
    }]
});

/* Highcharts.chart('weekly_percent', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: "Weekly",
        align: 'center',
        verticalAlign: 'middle',
        y: 40
    },
	subtitle: {
        text: 'Productivity<br>Percentage',
		align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}%</b>'
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        },
		series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y}%)'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Productivity',
        innerSize: '50%',
        data: [
        <?php 
		$weekly = 0; $len1 = count($members_weekly_productivity->result());
		foreach($members_weekly_productivity->result() as $mwp) :
				$username1 = $mwp->username;
				$team_name1 = $mwp->team_name;
				$percent1 = $mwp->percent; 
				?>
            ['<?php echo $username1; ?>', <?php echo round($percent1,2); ?>]<?php if($weekly != $len1 - 1){ echo ",";} ?>
        <?php $weekly++; 
		endforeach; 
		?>
        ]
    }]
}); */

Highcharts.chart('monthly_percent', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: "<?php if($team_group == ''){echo "All teams";}else{echo $team_group;}?> - Monthly",
        align: 'center',
        verticalAlign: 'middle',
        y: 40
    },
	subtitle: {
        text: 'Productivity<br>Percentage',
		align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}%</b>'
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        },
		series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y}%)'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Productivity',
        innerSize: '50%',
        data: [
        <?php 
		$monthly = 0; $len2 = count($members_monthly_productivity->result());
		foreach($members_monthly_productivity->result() as $mmp) :
				$username2 = $mmp->username;
				$team_name2 = $mmp->team_name;
				$percent2 = $mmp->percent; 
				?>
            ['<?php echo $username2; ?>', <?php echo round($percent2,2); ?>]<?php if($monthly != $len2 - 1){ echo ",";} ?>
        <?php $monthly++; 
		endforeach; 
		?>
        ]
    }]
});
/* Highcharts.chart('monthly_percent', {
    chart: {
        type: 'funnel'
    },
    title: {
        text: 'Monthly Productivity Percentage'
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y}%)',
                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                softConnector: true
            },
            center: ['40%', '50%'],
            neckWidth: '30%',
            neckHeight: '25%',
            width: '80%'
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Productivity',
        data: [
		<?php 
		$monthly = 0; $len2 = count($members_monthly_productivity->result());
		foreach($members_monthly_productivity->result() as $mmp) :
				$username2 = $mmp->username;
				$team_name2 = $mmp->team_name;
				$percent2 = $mmp->percent; 
				?>
            ['<?php echo $username2; ?>', <?php echo round($percent2,2); ?>]<?php if($monthly != $len2 - 1){ echo ",";} ?>
        <?php $monthly++; 
		endforeach; 
		?>
		]
    }]
}); */
</script>
<script type="text/javascript">
	var ctx = $("#myChart");
	var data = {
	    labels: ["<?php lang("ctn_567") ?>", "<?php lang("ctn_568") ?>", "<?php lang("ctn_569") ?>", "<?php lang("ctn_570") ?>", "<?php lang("ctn_571") ?>", "<?php lang("ctn_572") ?>", "<?php lang("ctn_573") ?>", "<?php lang("ctn_574") ?>", "<?php lang("ctn_575") ?>", "<?php lang("ctn_576") ?>", "<?php lang("ctn_577") ?>", "<?php lang("ctn_578") ?>"],
	    datasets: [
	    	{
	            label: "<?php echo lang("ctn_579") ?>",
	            fill: true,
	            lineTension: 0.2,
	            backgroundColor: "rgba(32,113,210,0.4)",
	            borderColor: "rgba(32,113,210,0.9)",
	            borderCapStyle: 'butt',
	            borderDash: [],
	            borderDashOffset: 0.0,
	            borderJoinStyle: 'miter',
	            pointBorderColor: "rgba(75,192,192,1)",
	            pointBackgroundColor: "#fff",
	            pointBorderWidth: 1,
	            pointHoverRadius: 5,
	            pointHoverBackgroundColor: "rgba(75,192,192,1)",
	            pointHoverBorderColor: "rgba(220,220,220,1)",
	            pointHoverBorderWidth: 2,
	            pointRadius: 1,
	            pointHitRadius: 10,
	            data: [<?php foreach($expense as $i) : ?>
	            	<?php echo $i['count'] ?>,
	            <?php endforeach; ?>],
	            spanGaps: false,
	        },
	        {
	            label: "<?php echo lang("ctn_580") ?>",
	            fill: true,
	            lineTension: 0.2,
	            backgroundColor: "rgba(29,210,142,0.5)",
	            borderColor: "rgba(29,210,142,0.9)",
	            borderCapStyle: 'butt',
	            borderDash: [],
	            borderDashOffset: 0.0,
	            borderJoinStyle: 'miter',
	            pointBorderColor: "rgba(75,192,192,1)",
	            pointBackgroundColor: "#fff",
	            pointBorderWidth: 1,
	            pointHoverRadius: 5,
	            pointHoverBackgroundColor: "rgba(75,192,192,1)",
	            pointHoverBorderColor: "rgba(220,220,220,1)",
	            pointHoverBorderWidth: 2,
	            pointRadius: 1,
	            pointHitRadius: 10,
	            data: [<?php foreach($income as $i) : ?>
	            	<?php echo $i['count'] ?>,
	            <?php endforeach; ?>],
	            spanGaps: false,
	        },
	    ]
	};
	Chart.defaults.global.defaultFontFamily = "'Open Sans'";
	Chart.defaults.global.defaultFontSize = 8;
	var options = { title : { text: "" }};
	var myLineChart = new Chart(ctx, {
	    type: 'line',
	    data: data,
	    options: {
	    	defaultFontSize: 8,
	    	responsive: true,
	    	hover : {
	    		mode : "single"
	    	},
	    	legend : {
	    		display : false,
	    		labels : {
	    			boxWidth: 15,
	    			padding: 10,
	    			fontSize: 11,
	    			usePointStyle : false
	    		}
	    	},
	    	animation : {
	    		duration: 2000,
	    		easing: "easeOutElastic"
	    	},
	    	scales : {
	    		yAxes : [{
	    			display: true,
	    			title : {
	    				fontSize: 11
	    			},
	    			gridLines : {
	    				display : true
	    			}
	    		}],
	    		xAxes : [{
	    			display : true,
	    			scaleLabel : {
	    				display : false
	    			},
	    			ticks : {
	    				display : true
	    			},
	    			gridLines : {
	    				display : true,
	    				drawTicks : false,
	    				tickMarkLength: 5,
	    				zeroLineWidth: 0,
	    			}
	    		}]
	    	}
	    }
	});

	    var data = {
    labels: [
       <?php foreach($time_projects as $project) : ?>
        "<?php echo $project['title'] ?>",
        <?php endforeach; ?>
    ],
    datasets: [
        {
            data: [
            <?php 
                $max = 0;
                $max_title = "";
            ?>
            <?php foreach($time_projects as $project) : ?>
            <?php if($project['hours'] > $max) {
                $max = $project['hours'];
                $max_title = $project['title'];
            }
            ?>
            <?php echo round($project['hours'],2) ?>,
            <?php endforeach; ?>
            ],
            backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#55f225",
                "#f24c25",
                "#f225b1",
                "#8125f2",
                "#39ded8",
                "#4e7f39",
                "#7f3941",
                "#7f3965",
                "#62397f",
                "#39507f",
                "#397f6f",
                "#397f44",
                "#607f39",
                "#7f6c39",
                "#7f3939",
                "#321a20",
                "#3c3a3c",
                "#7e777d",
                "#b5ba9e",
                "#84d588",
                "#84ced5",
                "#2c57b9",
                "#dbc6ec",
                "#ecc6eb",
            ],
            hoverBackgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#55f225",
                "#f24c25",
                "#f225b1",
                "#8125f2",
                "#39ded8",
                "#4e7f39",
                "#7f3941",
                "#7f3965",
                "#62397f",
                "#39507f",
                "#397f6f",
                "#397f44",
                "#607f39",
                "#7f6c39",
                "#7f3939",
                "#321a20",
                "#3c3a3c",
                "#7e777d",
                "#b5ba9e",
                "#84d588",
                "#84ced5",
                "#2c57b9",
                "#dbc6ec",
                "#ecc6eb",
            ]
        }]
};
	
var myPieChart = new Chart($("#projectTypesChart"),{
    type: 'pie',
    data: data,
    options : {
    	responsive: true,
    	legend : {
	    		display : false,
	    		labels : {
	    			boxWidth: 15,
	    			padding: 10,
	    			fontSize: 11,
	    			usePointStyle : false
	    		}
	    	},
    }
});

	$(document).ready(function() {
		var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
		var decimal_places = 2;
		$('#num1').animateNumber(
		  {
		    number: <?php echo $total_revenue ?>,
		    easing: 'easeInQuad', // require jquery.easing
		    numberStep: function(now, tween) {
                var floored_number = now,
                    target = $(tween.elem);

                if (decimal_places > 0) {
                  // force decimal places even if they are 0
                  floored_number = floored_number.toFixed(decimal_places);

                  // replace '.' separator with ','
                  //floored_number = floored_number.toString().replace('.', ',');
                }

                target.text(floored_number);
            }
		  },
		  1500
		);
		$('#num2').animateNumber(
		  {
		    number: <?php echo $total_expense ?>,
		    easing: 'easeInQuad', // require jquery.easing
		    numberStep: function(now, tween) {
                var floored_number = now,
                    target = $(tween.elem);

                if (decimal_places > 0) {
                  // force decimal places even if they are 0
                  floored_number = floored_number.toFixed(decimal_places);

                  // replace '.' separator with ','
                  //floored_number = floored_number.toString().replace('.', ',');
                }

                target.text(floored_number);
            }
		  },
		  1500
		);
		$('#num3').animateNumber(
		  {
		    number: <?php echo $profit ?>,
		    easing: 'easeInQuad', // require jquery.easing
		    numberStep: function(now, tween) {
                var floored_number = now,
                    target = $(tween.elem);

                if (decimal_places > 0) {
                  // force decimal places even if they are 0
                  floored_number = floored_number.toFixed(decimal_places);

                  // replace '.' separator with ','
                  //floored_number = floored_number.toString().replace('.', ',');
                }

                target.text(floored_number);
            }
		  },
		  1500
		);

		<?php if(!$load_tn) : ?>
			get_tasks();
		<?php else : ?>
			get_notes();
		<?php endif; ?>


	});

	function get_notes() 
	{
		$('#notes-btn').addClass("btn-flat-active");
		$('#tasks-btn').removeClass("btn-flat-active");
		$.ajax({
			url: global_base_url + "home/get_ajax_notes",
			type: 'GET',
			success: function(msg) {
				$('#ajax_tasks').html(msg);
			}
		});
	}

	function get_tasks() 
	{
		$('#notes-btn').removeClass("btn-flat-active");
		$('#tasks-btn').addClass("btn-flat-active");
		$.ajax({
			url: global_base_url + "home/get_ajax_tasks",
			type: 'GET',
			success: function(msg) {
				$('#ajax_tasks').html(msg);
			}
		});
	}

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

<script type="text/javascript">
$(document).ready(function(){
/* $("#team_group").change(function () {
	$("#date_submit").submit();
}); */
$("#from_date").datepicker({
	numberOfMonths: 2,
	dateFormat: 'yy-mm-dd',
	onSelect: function (selected) {
		var dt = new Date(selected);
		dt.setDate(dt.getDate() + 1);
		$("#to_date").datepicker("option", "minDate", dt);
	}
});
$("#to_date").datepicker({
	numberOfMonths: 2,
	dateFormat: 'yy-mm-dd',
	onSelect: function (selected) {
		var dt = new Date(selected);
		dt.setDate(dt.getDate() - 1);
		$("#from_date").datepicker("option", "maxDate", dt);
		$("#date_submit").submit();
	}
});

/* 
$("#to_date").datepicker({
       onSelect : function (dateText, inst) {
          $("#date_submit").submit();
  }
});  */
$(function() {
    $('#project_group').change(function() {
        this.form.submit();
    });
});
$(function() {
    $('#team_group').change(function() {
        this.form.submit();
    });
});
/* $("#date_submit").submit();
$("#project_status").submit(); */
/* window.onload = function(){
 $('#project_group').val('Bdops').trigger('change');
}); */
});
</script>

<div class="white-area-content">
	<div class="row">
		<div class="col-md-3">
			<div class="dashboard-window clearfix" style="background: #62acec; border-left: 5px solid #5798d1;">
				<div class="d-w-icon">
					<span class="glyphicon glyphicon-send giant-white-icon"></span>
				</div>
				<div class="d-w-text">
					 <span class="d-w-num"><?php echo number_format($stats->total_members) ?></span><br /><?php echo lang("ctn_136") ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dashboard-window clearfix" style="background: #5cb85c; border-left: 5px solid #4f9f4f;">
				<div class="d-w-icon">
					<span class="glyphicon glyphicon-wrench giant-white-icon"></span>
				</div>
				<div class="d-w-text">
					 <span class="d-w-num"><?php echo number_format($stats->new_members) ?></span><br /><?php echo lang("ctn_137") ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dashboard-window clearfix" style="background: #f0ad4e; border-left: 5px solid #d89b45;">
				<div class="d-w-icon">
					<span class="glyphicon glyphicon-folder-close giant-white-icon"></span>
				</div>
				<div class="d-w-text">
					 <span class="d-w-num"><?php echo number_format($stats->active_today) ?></span><br /><?php echo lang("ctn_138") ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dashboard-window clearfix" style="background: #d9534f; border-left: 5px solid #b94643;">
				<a href="<?php echo base_url() ?>/team/users">
					<div class="d-w-icon">
						<span class="glyphicon glyphicon-user giant-white-icon"></span>
					</div>
					<div class="d-w-text">
						 <span class="d-w-num"><?php echo number_format($online_count) ?></span><br /><?php echo lang("ctn_139") ?>
					</div>
				</a>	
			</div>
		</div>
	</div>
	<hr/>


<div class="row">
<div class="col-md-12">
	<!-- <div class="block-area align-center">
		<h4 class="home-label"><?php echo lang("ctn_140") ?></h4>
		<canvas id="myChart" class="graph-height"></canvas>
	</div> -->
	<div class="content-separator block-area">
		<h4 class="home-label"><?php echo "Today's Title on Schedule"; ?>
			<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/todaysexport')?>" title="Export Csv">Export Csv</a>
		</h4>
		<div class="table-responsive">
			<table class="table small-text table-bordered table-striped table-hover">
				<tr class="table-header">
					<td><?php echo "Title"; ?></td>
					<td><?php echo "User"; ?></td>
					<td><?php echo "Team"; ?></td>
					<td><?php echo "Total Pages/Articles"; ?></td>
				</tr>
				<?php foreach($current_tasks->result() as $r) : ?>
				<tr>
					<td> <?php echo $r->title; ?></td>
					<td> <?php echo $r->user; ?></td>
					<td> <?php echo $r->team; ?></td>
					<td> <?php if($r->totalPages !=0 ){ echo $r->totalPages; } else{ /* echo $r->totalArticles; */ } ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	<div class="content-separator block-area">
		<h4 class="home-label"><?php echo "Idle Members"; ?>
			<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/idlemem_export')?>" title="Export Csv">Export Csv</a>
		</h4>
		<div class="table-responsive">
			<table class="table small-text table-bordered table-striped table-hover">
				<tr class="table-header">
					<td><?php echo "Employee Id"; ?></td>
					<td><?php echo "User Name"; ?></td>
					<td><?php echo "Email"; ?></td>
					<td><?php echo "Team Name"; ?></td>
				</tr>
				<?php
				foreach($idle_member->result() as $r) : ?>
				<tr>
					<td><?php echo $r->employee_id; ?></td>
					<td><?php echo $r->username; ?></td>
					<td><?php echo $r->email; ?></td>
					<td><?php echo $r->team_name; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

	<div class="content-separator block-area">
	<?php echo form_open(site_url("admin/index/"), array("class" => "form-horizontal","id" => "month_submit")) ?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">
			<label for="start_date" class="col-md-5 label-heading"><?php echo "Start Date"; ?></label>
			<div class="col-md-7">
				<input name="start_date" type="text" id="start_date" class="form-control datepicker" value="<?php if(isset($start_date)&&($start_date!='')){echo $start_date;} ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="end_date" class="col-md-5 label-heading"><?php echo "End Date"; ?></label>
			<div class="col-md-7">
				<input name="end_date" type="text" id="end_date" class="form-control datepicker" value="<?php if(isset($end_date)&&($end_date!='')){echo $end_date;} ?>">
			</div>
		</div>
	</div>
	<?php echo form_close() ?>

	<h4 class="home-label"><?php echo "Tasks completed after <b>Rework</b>"; ?>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/tasks_rework')?>" title="Export Csv">Export Csv
	</a>
	</h4>
	<div class="table-responsive">
	<table class="table small-text table-bordered table-striped table-hover">
	<tr class="table-header">
		<td><?php echo "Project"; ?></td>
		<td><?php echo "Title"; ?></td>
		<td><?php echo "Task"; ?></td>
		<td><?php echo "User Name"; ?></td>
		<td><?php echo "Team Name"; ?></td>
		<td><?php echo "Duration"; ?></td>
	</tr>
	<?php
	foreach($tasks_rework_tbl->result() as $r) : ?>
	<tr>
	<td><?php echo $r->project; ?></td>
	<td><?php echo $r->title; ?></td>
	<td><a href="<?php echo site_url("tasks/view/" . $r->taskid) ?>" title="<?php echo $r->task; ?>"><?php echo $r->task; ?></a></td>
	<td><?php echo $r->username; ?></td>
	<td><?php echo $r->team_name; ?></td>
	<td><?php echo $r->duration; 
	/* $time1 = strtotime($r->rework_date . $r->rework_time);
	$time2 = strtotime($r->completed_date . $r->completed_time);
		$diff= abs($time2 - $time1);
	$hours = round(($diff/3600),2);
	$days = round($hours/24);
		$remain=floor($diff/(60*60*24));
	$remain_hrs = round(($diff-$remain*60*60*24)/(60*60));			
		if( $days < 1 )
		{
			if( $hours > 1 ){echo "$hours hours";}else{echo "$hours hour";}
		}
		else
		{
			if( $days > 1 ){
				echo "$days days $remain_hrs ";
				if( $remain_hrs > 1 ){echo "hours";}else{echo "hour";}
			}
			else{
				echo "$days day $remain_hrs ";
				if( $remain_hrs > 1 ){echo "hours";}else{echo "hour";}
			}
		} 
	 */?></td>
	</tr>
	<?php endforeach; ?>
	</table>
	</div>
	</div>

	<div class="content-separator block-area">
	<h4 class="home-label"><?php echo "Revenue Report"; ?>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/revenue_export')?>" title="Export Csv">Export Csv
	</a>
	</h4>
	<div class="table-responsive">
	<table class="table small-text table-bordered table-striped table-hover">
	<tr class="table-header">
		<td><?php echo "Project Details"; ?></td>
		<td><?php echo "Team Name"; ?></td>
		<td><?php echo "Reporting"; ?></td>
	</tr>
	<?php $total_rev = 0; /* echo "<pre>"; print_r($projectrevenue->result()); echo "</pre>"; */
	foreach($projectrevenue->result() as $pr){ ?>
	<tr> 
		<td>
		<table class="table table-bordered" style="margin: 0px;">
			<tr> 
				<td><?php echo "<b>Project Name:</b> ".$pr->project_name; ?></td>
				<td><?php echo "<b>Project Status:</b> ".$pr->category; ?></td>
				<td><?php echo "<b>Process Name:</b> ".$pr->process_name; ?></td>
			</tr>
		</table>
		</td> 
		<td><?php echo $pr->team_name; ?></td>
		<td><?php echo $pr->reporting; ?></td>
	</tr>
	<tr>
	<td width="100%">
	<button class="accordion"><span class="glyphicon glyphicon-plus"></span> View Title Details</button>	
		<div class="panel" id="acc_panel">
		<div class="table-responsive">
		<table class="table small-text table-bordered table-striped table-hover" id="view_title_details">
		<tr class="table-header">
			<td><?php echo "Title Name"; ?></td>
			<td><?php echo "Start Date"; ?></td>
			<td><?php echo "Due Date"; ?></td>
			<td><?php echo "Unit"; ?></td>
			<td><?php echo "Unit Count"; ?></td>
			<td><?php echo "Revenue"; ?></td>
		</tr>
		<?php $sum = 0;
		foreach($titlerevenue->result() as $tr){
		if($pr->ID == $tr->projectid){ ?>
			<tr>
			<td><?php echo $tr->name; ?></td>
			<td><?php echo date('d/m/Y', $tr->start_date); ?></td>
			<td><?php echo date('d/m/Y', $tr->due_date); ?></td>
			<td><?php echo $tr->unit; ?></td>
			<td><?php 
			if($tr->parallel == 1){
				if($pr->process_name == 'Geofacets Table conversion' || $pr->process_name == 'Geofacets Figure conversion'){
					echo $tr->totalTablesEditable + $tr->totalTablesScanned + $tr->totalFiguresEditable + $tr->totalFiguresScanned + $tr->parallel_process_totalTablesEditable + $tr->parallel_process_totalTablesScanned + $tr->parallel_process_totalFiguresEditable + $tr->parallel_process_totalFiguresScanned;
				}
				else{
					if($tr->unit == 'Page'){echo $tr->totalPages + $tr->parallel_process_totalPages;}
					elseif($tr->unit == 'Article'){echo $tr->totalArticles + $tr->parallel_process_totalArticles;} 
					elseif($tr->unit == 'table'){echo $tr->totalTablesEditable + $tr->totalTablesScanned + $tr->parallel_process_totalTablesEditable + $tr->parallel_process_totalTablesScanned;} 
					elseif($tr->unit == 'figure'){echo $tr->totalFiguresEditable + $tr->totalFiguresScanned + $tr->parallel_process_totalFiguresEditable + $tr->parallel_process_totalFiguresScanned;} 
					elseif($tr->unit == 'image'){echo $tr->totalImages + $tr->parallel_process_totalImages;} 
					elseif($tr->unit == 'Question'){echo "0";}
					else{echo "0";}
				}
			}
			else{
				if($pr->process_name == 'Geofacets Table conversion' || $pr->process_name == 'Geofacets Figure conversion'){
					echo $tr->totalTablesEditable + $tr->totalTablesScanned + $tr->totalFiguresEditable + $tr->totalFiguresScanned;
				}
				else{
					if($tr->unit == 'Page'){echo $tr->totalPages;}
					elseif($tr->unit == 'Article'){echo $tr->totalArticles;} 
					elseif($tr->unit == 'table'){echo $tr->totalTablesEditable + $tr->totalTablesScanned;} 
					elseif($tr->unit == 'figure'){echo $tr->totalFiguresEditable + $tr->totalFiguresScanned;} 
					elseif($tr->unit == 'image'){echo $tr->totalImages;} 
					elseif($tr->unit == 'Question'){echo "0";}
					else{echo "0";}
				}
			}
			?>
			</td>
			<td>$
			<?php 
			if($tr->parallel == 1){
				if($pr->process_name == 'Geofacets Table conversion' || $pr->process_name == 'Geofacets Figure conversion'){
					echo $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned + $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned + $tr->parallel_process_totalTablesPriceEditable + $tr->parallel_process_totalTablesPriceScanned + $tr->parallel_process_totalFiguresPriceEditable + $tr->parallel_process_totalFiguresPriceScanned;
				}
				else{
					if($tr->unit == 'Page'){echo $tr->totalPagesPrice + $tr->parallel_process_totalPagesPrice;}
					elseif($tr->unit == 'Article'){echo $tr->totalArticlesPrice + $tr->parallel_process_totalArticlesPrice;} 
					elseif($tr->unit == 'table'){echo $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned + $tr->parallel_process_totalTablesPriceEditable + $tr->parallel_process_totalTablesPriceScanned;} 
					elseif($tr->unit == 'figure'){echo $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned + $tr->parallel_process_totalFiguresPriceEditable + $tr->parallel_process_totalFiguresPriceScanned;} 
					elseif($tr->unit == 'image'){echo $tr->totalImagesPrice + $tr->parallel_process_totalImagesPrice;} 
					elseif($tr->unit == 'Question'){echo "";}
					else{echo "0";}
				}
			}
			else{
				if($pr->process_name == 'Geofacets Table conversion' || $pr->process_name == 'Geofacets Figure conversion'){
					echo $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned + $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned;
				}
				else{
					if($tr->unit == 'Page'){echo $tr->totalPagesPrice;}
					elseif($tr->unit == 'Article'){echo $tr->totalArticlesPrice;} 
					elseif($tr->unit == 'table'){echo $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned;} 
					elseif($tr->unit == 'figure'){echo $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned;} 
					elseif($tr->unit == 'image'){echo $tr->totalImagesPrice;} 
					elseif($tr->unit == 'Question'){echo "";}
					else{echo "0";}
				}				
			}
			/* if($tr->totalPagesPrice != '0.00'){echo $tr->totalPagesPrice;}
			else{echo $tr->totalArticlesPrice;}  */
			?>
			</td>
			</tr>
			<?php 
			if($tr->parallel == 1){
				if($pr->process_name == 'Geofacets Table conversion' || $pr->process_name == 'Geofacets Figure conversion'){
					$revenue_total = $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned + $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned + $tr->parallel_process_totalTablesPriceEditable + $tr->parallel_process_totalTablesPriceScanned + $tr->parallel_process_totalFiguresPriceEditable + $tr->parallel_process_totalFiguresPriceScanned;
				}
				else{
					if($tr->unit == 'Page'){$revenue_total = $tr->totalPagesPrice + $tr->parallel_process_totalPagesPrice;}
					elseif($tr->unit == 'Article'){$revenue_total = $tr->totalArticlesPrice + $tr->parallel_process_totalArticlesPrice;} 
					elseif($tr->unit == 'table'){$revenue_total = $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned + $tr->parallel_process_totalTablesPriceEditable + $tr->parallel_process_totalTablesPriceScanned;} 
					elseif($tr->unit == 'figure'){$revenue_total = $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned + $tr->parallel_process_totalFiguresPriceEditable + $tr->parallel_process_totalFiguresPriceScanned;} 
					elseif($tr->unit == 'image'){$revenue_total = $tr->totalImagesPrice + $tr->parallel_process_totalImagesPrice;} 
					elseif($tr->unit == 'Question'){$revenue_total = 0;}
					else{$revenue_total = 0;}
				}
			}
			else{
				if($pr->process_name == 'Geofacets Table conversion' || $pr->process_name == 'Geofacets Figure conversion'){
					$revenue_total = $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned + $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned;
				}
				else{
					if($tr->unit == 'Page'){$revenue_total = $tr->totalPagesPrice;}
					elseif($tr->unit == 'Article'){$revenue_total = $tr->totalArticlesPrice;} 
					elseif($tr->unit == 'table'){$revenue_total = $tr->totalTablesPriceEditable + $tr->totalTablesPriceScanned;} 
					elseif($tr->unit == 'figure'){$revenue_total = $tr->totalFiguresPriceEditable + $tr->totalFiguresPriceScanned;} 
					elseif($tr->unit == 'image'){$revenue_total = $tr->totalImagesPrice;} 
					elseif($tr->unit == 'Question'){$revenue_total = 0;}
					else{$revenue_total = 0;}
				}			
			}
			/* if($tr->totalPagesPrice != '0.00'){
					$revenue_total = $tr->totalPagesPrice;
				  }else{
					$revenue_total = $tr->totalArticlesPrice;
				  }  */
			$sum+= $revenue_total;
			?>
		<?php } } ?>
		</table>
		<div id="revenue_title_total">
			<p>Total revenue of project <b><?php echo $pr->project_name; ?>:</b> <span style="float:right;"><b>$<?php echo number_format($sum, 2); ?></b><span></p>
		</div>
		</div>
		</div>
	</td>
	<td><?php echo "Total revenue "; ?><b><?php echo '('.$pr->project_name.')'; ?></b></td>
	<td><b>$<?php echo number_format($sum, 2); ?></b></td>
	</tr> 	
	<?php $total_rev+= $sum; } ?>	
	<span style="float:right;padding:21px 0 10px 0;">Total revenue: <b>$<?php echo number_format($total_rev, 2); ?></b></span>
	</table>
	</div>
	</div>

	<div class="content-separator block-area">
	<h4 class="home-label"><?php echo "Load Details"; ?>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/load_export')?>" title="Export Csv">Export Csv
	</a>
	</h4>
	<div class="table-responsive">
	<table class="table small-text table-bordered table-striped table-hover">
	<tr class="table-header">
		<td><?php echo "Project Name"; ?></td>
		<td><?php echo "Title Count"; ?></td>
		<td><?php echo "Due date"; ?></td>
		<td><?php echo "Title status count"; ?></td>
	</tr>
	<?php
	foreach($load_details->result() as $r) : ?>
	<tr>
		<td><?php echo $r->project_name; ?></td>
		<td><?php echo $r->title_count; ?></td>
		<td><?php echo $r->due_date; ?></td>
		<td>
			<table class="table small-text table-bordered table-striped table-hover">	
				<tr>
				<?php $str = $r->title_status; 
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
	</tr>
	<?php endforeach; ?>
	</table>
	</div>
	</div>

	<div class="block-area">
	<?php echo lang("ctn_326") ?> <b><?php echo date($this->settings->info->date_format, $this->user->info->online_timestamp); ?></b>
	</div>

</div>
<!--
<div class="col-md-4">
	<div class="block-area">
	<h4 class="home-label"><?php echo lang("ctn_141") ?></h4>
	<?php foreach($new_members->result() as $r) : ?>
		<div class="new-user">
		<?php
			if($r->joined + (3600*24) > time()) {
				$joined = lang("ctn_144");
			} else {
				$joined = date($this->settings->info->date_format, $r->joined);
			}

			if($r->oauth_provider == "twitter") {
				$ava = "images/social/twitter.png";
			} elseif($r->oauth_provider == "facebook") {
				$ava = "images/social/facebook.png";
			} elseif($r->oauth_provider == "google") {
				$ava = "images/social/google.png";
			} else {
				$ava = $this->settings->info->upload_path_relative . "/default.png";
			}

		?>
	<img src="<?php echo base_url() ?><?php echo $ava ?>" width="25" class="new-member-avatar" /> <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> <div class="new-member-joined"><?php echo $joined ?></div>
	</div>
	<?php endforeach; ?>
	</div>
</div> -->

</div>
</div>

<script type="text/javascript">
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
	  $(this).find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    } else {
      panel.style.display = "block";
	  $(this).find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
    }
  });
}

$("#start_date").datepicker({
	numberOfMonths: 2,
	dateFormat: 'yy-mm-dd',
	onSelect: function (selected) {
		var dt = new Date(selected);
		dt.setDate(dt.getDate() + 1);
		$("#end_date").datepicker("option", "minDate", dt);
	}
});
$("#end_date").datepicker({
	numberOfMonths: 2,
	dateFormat: 'yy-mm-dd',
	onSelect: function (selected) {
		var dt = new Date(selected);
		dt.setDate(dt.getDate() - 1);
		$("#start_date").datepicker("option", "maxDate", dt);
		$("#month_submit").submit();
	}
});
$('#sidebarCollapse').on('click', function () {
	$('#sidebar').toggleClass('active');
	$(this).toggleClass('active');
});
</script>
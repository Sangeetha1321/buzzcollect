<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo "File Monitoring"; ?></div>
</div>
 
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

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


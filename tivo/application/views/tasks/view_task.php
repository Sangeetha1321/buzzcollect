<div class="white-area-content">
	<?php if($task->template) : ?>
	<div class="alert alert-danger" role="alert"><?php echo lang("ctn_1504") ?></div>
	<?php endif; ?>
	<div class="db-header clearfix">
		<div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_820") ?></div>
		<div class="db-header-extra"> 
		<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
		<a href="<?php echo site_url("tasks/add") ?>" class="btn btn-primary btn-sm">
		<?php echo lang("ctn_821") ?>
		</a>
		<?php endif; ?>
		</div>
	</div>
	<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
</div>

<?php 
if($task->status == 1) {
$statusbtn = "btn-info";
$statusmsg = lang("ctn_830");
} elseif($task->status == 2) {
$statusbtn = "btn-primary";
$statusmsg = lang("ctn_831");
} elseif($task->status == 3) {
$statusbtn = "btn-success";
$statusmsg = lang("ctn_832");
} elseif($task->status == 4) {
$statusbtn = "btn-warning";
$statusmsg = lang("ctn_833");
} elseif($task->status == 5) {
$statusbtn = "btn-danger";
$statusmsg = lang("ctn_834");
} elseif($task->status == 6) {
$statusbtn = "btn-success";
$statusmsg = lang("ctn_1633");
} elseif($task->status == 7) {
$statusbtn = "btn-danger";
$statusmsg = lang("ctn_1634");
} elseif($task->status == 8) {
$statusbtn = "btn-success";
$statusmsg = lang("ctn_1635");
}
?>

<input type="hidden" id="taskid" value="<?php echo $task->ID ?>" />

<div class="row content-separator">
<div class="col-md-8">
	<div class="white-area-content">
		<div class="db-header clearfix">
			<div class="task-header-title"> <span class="glyphicon glyphicon-pushpin"></span> <?php echo $task->name ?> </div>
			<div class="db-header-extra"> 
			<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
			<button id="status-button-update" type="button" class="btn btn-default btn-xs"> <span class="glyphicon glyphicon-refresh spin"></span></button>
			  <div class="btn-group">
				<div class="btn-group">
				<button type="button" class="btn <?php echo $statusbtn ?> btn-xs dropdown-toggle" data-toggle="dropdown" id="status-button">
				  <?php echo $statusmsg ?> <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="javascript: void(0);" onclick="changeStatus(1)"><?php echo lang("ctn_830") ?></a></li>
				  <li><a href="javascript: void(0);" onclick="changeStatus(2)"><?php echo lang("ctn_831") ?></a></li>
				  <li><a href="javascript: void(0);" onclick="changeStatus(3)"><?php echo lang("ctn_832") ?></a></li>
				<!--  <li><a href="javascript: void(0);" onclick="changeStatus(4)"><?php echo lang("ctn_833") ?></a></li>
				  <li><a href="javascript: void(0);" onclick="changeStatus(5)"><?php echo lang("ctn_834") ?></a></li> -->
				</ul>
			  </div>
			  <a href="<?php echo site_url("tasks/edit_task/" . $task->ID) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_851") ?></a> <a href="<?php echo site_url("tasks/delete_task/" . $task->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_508") ?>')"><?php echo lang("ctn_850") ?></a>
			  </div>
			<?php endif; ?>
			</div>
		</div>

		<?php echo $task->description ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1589") ?></p>
		<p class="project-info-bit"><?php echo $task->input ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1590") ?></p>
		<p class="project-info-bit"><?php echo $task->output ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1591") ?></p>
		<p class="project-info-bit"><?php echo $task->complexity ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1583") ?></p>
		<p class="project-info-bit"><?php echo $task->isbn ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1584") ?></p>
		<p class="project-info-bit"><?php echo $task->eisbn ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1585") ?></p>
		<p class="project-info-bit"><?php echo $task->volume_issue ?></p>
		</div>
		<?php if($task->unit == 'Page') { ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1592") ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerPage ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1594") ?></p>
		<p class="project-info-bit"><?php echo $task->totalPages ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1596") ?></p>
		<p class="project-info-bit"><?php echo $geo_total = $task->totalPagesPrice ?></p>
		</div>
		<?php } ?>
		<?php if($task->unit == 'Article'){ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1593") ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerPage ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1595") ?></p>
		<p class="project-info-bit"><?php echo $task->totalPages ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1597") ?></p>
		<p class="project-info-bit"><?php echo $geo_total = $task->totalPagesPrice ?></p>
		</div>
		<?php } ?>
		<?php if(isset($task->geofacets_unit) && $task->geofacets_unit == 'figure' && $task->unit == 'table'){ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Table Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerTableEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Tables Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Tables Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesPriceEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Table Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerTableScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Tables Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Tables Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesPriceScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Figure Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerFigureEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Figures Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Figures Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresPriceEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Figure Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerFigureScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Figures Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Figures Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresPriceScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Revenues" ?></p>
		<p class="project-info-bit"><?php echo $geo_total = $task->totalTablesPriceEditable + $task->totalTablesPriceScanned + $task->totalFiguresPriceEditable + $task->totalFiguresPriceScanned ?></p>
		</div>
		<?php } ?>
		<?php if((!isset($task->geofacets_unit)) && ($task->unit == 'table' || $task->unit == 'figure')){ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Table Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerTableEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Tables Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Tables Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesPriceEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Table Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerTableScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Tables Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Tables Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalTablesPriceScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Figure Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerFigureEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Figures Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Figures Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresPriceEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Figure Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerFigureScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Figures Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Figures Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->totalFiguresPriceScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Revenues" ?></p>
		<p class="project-info-bit"><?php echo $geo_total = $task->totalTablesPriceEditable + $task->totalTablesPriceScanned + $task->totalFiguresPriceEditable + $task->totalFiguresPriceScanned ?></p>
		</div>
		<?php } ?>
		<?php if($task->unit == 'image'){ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Image"; ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerPage ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Images"; ?></p>
		<p class="project-info-bit"><?php echo $task->totalPages ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Images"; ?></p>
		<p class="project-info-bit"><?php echo $geo_total = $task->totalPagesPrice ?></p>
		</div>
		<?php } ?>
		<?php if($task->unit == 'Question'){ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Unit Price per Question"; ?></p>
		<p class="project-info-bit"><?php echo $task->unitPricePerPage ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Total Questions"; ?></p>
		<p class="project-info-bit"><?php echo $task->totalPages ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Revenue: Questions"; ?></p>
		<p class="project-info-bit"><?php echo $geo_total = $task->totalPagesPrice ?></p>
		</div>
		<?php } ?>
		
		<?php if($task->parallel == 1) { ?>
		<div id="parallel_process_view_block">
		<h5><b><?php echo "Parallel process Details"; ?></b></h5>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Name"; ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_name; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Team"; ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_team; ?></p>
			</div>
			<?php if($task->cost_applicable == 1) { ?>
				<div class="project-info project-block">
				<p class="project-info-title"><?php echo "Parallel process Unit"; ?></p>
				<p class="project-info-bit"><?php echo $task->parallel_process_unit; ?></p>
				</div>
				<?php if($task->parallel_process_unit == 'Page') { ?>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Parallel process Unit Price per Page"; ?></p>
					<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerPage; ?></p>
					</div>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Parallel process Total Pages"; ?></p>
					<p class="project-info-bit"><?php echo $task->parallel_process_totalPages; ?></p>
					</div>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Parallel process Revenue: Pages"; ?></p>
					<p class="project-info-bit"><?php echo $process_total = $task->parallel_process_totalPagesPrice; ?></p>
					</div>
				<?php } ?>
				<?php if($task->parallel_process_unit == 'Article') { ?>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Parallel process Unit Price per Article"; ?></p>
					<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerArticle; ?></p>
					</div>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Parallel process Total Articles"; ?></p>
					<p class="project-info-bit"><?php echo $task->parallel_process_totalArticles; ?></p>
					</div>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Parallel process Revenue: Articles"; ?></p>
					<p class="project-info-bit"><?php echo $process_total = $task->parallel_process_totalArticlesPrice; ?></p>
					</div>
				<?php } ?>
			<?php if($task->parallel_process_unit == 'table' || $task->parallel_process_unit == 'figure') { ?>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Unit Price per Table Editable" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerTableEditable; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Total Tables Editable" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalTablesEditable; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Revenue: Tables Editable" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalTablesPriceEditable; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Unit Price per Table Scanned" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerTableScanned; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Total Tables Scanned" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalTablesScanned; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Revenue: Tables Scanned" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalTablesPriceScanned; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Unit Price per Figure Editable" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerFigureEditable; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Total Figures Editable" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalFiguresEditable; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Revenue: Figures Editable" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalFiguresPriceEditable; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Unit Price per Figure Scanned" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerFigureScanned; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Total Figures Scanned" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalFiguresScanned; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Revenue: Figures Scanned" ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalFiguresPriceScanned; ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Total Revenues" ?></p>
			<p class="project-info-bit"><?php echo $process_total = $task->parallel_process_totalTablesPriceEditable + $task->parallel_process_totalTablesPriceScanned + $task->parallel_process_totalFiguresPriceEditable + $task->parallel_process_totalFiguresPriceScanned; ?></p>
			</div>
			<?php } ?>
			<?php if($task->parallel_process_unit == 'image') { ?>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Unit Price per Image"; ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_unitPricePerImage ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Total Images"; ?></p>
			<p class="project-info-bit"><?php echo $task->parallel_process_totalImages ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Parallel process Revenue: Images"; ?></p>
			<p class="project-info-bit"><?php echo $process_total = $task->parallel_process_totalImagesPrice ?></p>
			</div>
			<?php } ?>
				<?php if(isset($geo_total)){ ?>
					<div class="project-info project-block">
					<p class="project-info-title"><?php echo "Total"; ?></p>
					<p class="project-info-bit"><?php echo $geo_total + $process_total; ?></p>
					</div>
				<?php } ?>
		<?php } ?>
		</div>
		<?php } ?>
		
		<?php if($task->vendor_assignment == 1) { ?>
		<div id="vendor_view_block">
			<h5><b><?php echo "Vendor Details"; ?></b></h5>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Name"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_name ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Process Name"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_process_name ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Unit"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_unit ?></p>
			</div>
			<?php if($task->vendor_unit == 'Page') { ?>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Unit Price per Page"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_unitPricePerPage ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Total Pages"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_totalPages ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Revenue: Pages"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_totalPagesPrice ?></p>
			</div>
			<?php } ?>
			<?php if($task->vendor_unit == 'Article') { ?>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Unit Price per Article"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_unitPricePerArticle ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Total Articles"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_totalArticles ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Revenue: Articles"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_totalArticlesPrice ?></p>
			</div>
			<?php } ?>
		<?php if($task->vendor_unit == 'table' || $task->vendor_unit == 'figure') { ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Unit Price per Table Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_unitPricePerTableEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Total Tables Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalTablesEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Revenue: Tables Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalTablesPriceEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Unit Price per Table Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_unitPricePerTableScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Total Tables Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalTablesScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Revenue: Tables Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalTablesPriceScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Unit Price per Figure Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_unitPricePerFigureEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Total Figures Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalFiguresEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Revenue: Figures Editable" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalFiguresPriceEditable ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Unit Price per Figure Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_unitPricePerFigureScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Total Figures Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalFiguresScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Revenue: Figures Scanned" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalFiguresPriceScanned ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Vendor Total Revenues" ?></p>
		<p class="project-info-bit"><?php echo $task->vendor_totalTablesPriceEditable + $task->vendor_totalTablesPriceScanned + $task->vendor_totalFiguresPriceEditable + $task->vendor_totalFiguresPriceScanned ?></p>
		</div>
		<?php } ?>
		<?php if($task->vendor_unit == 'image') { ?>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Unit Price per Image"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_unitPricePerImage ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Total Images"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_totalImages ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Revenue: Images"; ?></p>
			<p class="project-info-bit"><?php echo $task->vendor_totalImagesPrice ?></p>
			</div>
			<?php } ?>
			
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Start Date"; ?></p>
			<p class="project-info-bit"><?php echo date($this->settings->info->date_picker_format,$task->vendor_start_date); ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Due Date"; ?></p>
			<p class="project-info-bit"><?php echo date($this->settings->info->date_picker_format,$task->vendor_due_date); ?></p>
			</div>
			<div class="project-info project-block">
			<p class="project-info-title"><?php echo "Vendor Status"; ?></p>
			<p class="project-info-bit"><?php if($task->vendor_status == 1){ echo "Vendor In progress"; } elseif($task->vendor_status == 2){ echo "Vendor YTS"; } elseif($task->vendor_status == 3){ echo "Vendor Completed"; }else{} ?></p>
			</div>
		</div>
		<?php } ?>
	</div>

<div class="white-area-content content-separator">
	<div class="db-header clearfix">
		<div class="task-header-title"> <span class="glyphicon glyphicon-list-alt"></span> <?php echo lang("ctn_852") ?></div>
		<div class="db-header-extra"> 
		<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
		<button data-toggle="modal" data-target="#addObjectiveModal" class="btn btn-primary btn-xs"><?php echo lang("ctn_853") ?></button>
		<?php endif; ?> 
		</div>
	<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
	<select id="default_tasks" name="taskOption"  <?php /*  if(isset($_COOKIE['tasks_dft_'.$task->ID])){ if($_COOKIE['tasks_dft_'.$task->ID] != 0){ echo "disabled";} } */  ?>>
	  <option value="0">Select task</option>
	  <option value="1" <?php if(isset($_COOKIE['tasks_dft_'.$task->ID])){ if($_COOKIE['tasks_dft_'.$task->ID] == 1){echo "selected";} } ?>>PDF to XML Conversion</option>
	  <option value="2" <?php if(isset($_COOKIE['tasks_dft_'.$task->ID])){ if($_COOKIE['tasks_dft_'.$task->ID] == 2){echo "selected";} } ?>>Word to XML Conversion</option>
	  <option value="3" <?php if(isset($_COOKIE['tasks_dft_'.$task->ID])){ if($_COOKIE['tasks_dft_'.$task->ID] == 3){echo "selected";} } ?>>XML to XML Conversion</option>
	</select> 
	<?php endif; ?>
	</div>
	
	<div class="row" id="default_ato" style="display:none;">
		   <?php echo form_open(site_url("tasks/add_default_task_objective/" . $task->ID), array("class" => "form-horizontal")) ?>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_839") ?></label>
				<div class="col-md-8 ui-front">
				   <input id="input_ato" type="text" class="form-control" name="title" value=""/>
				</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
				<div class="col-md-8 ui-front">
				<input type="text" id="subtask_complexity" class="form-control" name="subtask_complexity" value="">
				</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
				<div class="col-md-8 ui-front">
				<input type="text" id="subtask_unit" class="form-control" name="subtask_unit" value="">
				</div>
			</div>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
			<input id="default_pdf_xml_ato" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_853") ?>">
			<?php echo form_close() ?>
	</div>
	<div class="row" id="default_wtx" style="display:none;">
		   <?php echo form_open(site_url("tasks/add_wtx_task_objective/" . $task->ID), array("class" => "form-horizontal")) ?>
			<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_839") ?></label>
					<div class="col-md-8 ui-front">
					   <input id="input_wtx" type="text" class="form-control" name="title" value=""/>
					</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
				<div class="col-md-8 ui-front">
				<input type="text" id="subtask_complexity" class="form-control" name="subtask_complexity" value="">
				</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
				<div class="col-md-8 ui-front">
				<input type="text" id="subtask_unit" class="form-control" name="subtask_unit" value="">
				</div>
			</div>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
			<input id="default_word_xml_wtx" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_853") ?>">
			<?php echo form_close() ?>
	</div>
	<div class="row" id="default_xtx" style="display:none;">
		   <?php echo form_open(site_url("tasks/add_xtx_task_objective/" . $task->ID), array("class" => "form-horizontal")) ?>
			<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_839") ?></label>
					<div class="col-md-8 ui-front">
					   <input id="input_xtx" type="text" class="form-control" name="title" value=""/>
					</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
				<div class="col-md-8 ui-front">
				<input type="text" id="subtask_complexity" class="form-control" name="subtask_complexity" value="">
				</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
				<div class="col-md-8 ui-front">
				<input type="text" id="subtask_unit" class="form-control" name="subtask_unit" value="">
				</div>
			</div>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
			<input id="default_xml_xml_xtx" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_853") ?>">
			<?php echo form_close() ?>
	</div>
	<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
	<?php /* echo form_open(site_url("tasks/deleteselectedTasks"), array("class" => "form-horizontal","id" => "delete_tasks_form")) */ ?>
	<?php echo form_open(site_url("tasks/deleteselectedTasks/" . $this->security->get_csrf_hash()), array("class" => "form-horizontal","id" => "delete_tasks_form")) ?>
	<?php if (isset($_GET['msg'])) { ?>
		<p class="alert alert-success"><?php echo $_GET['msg']; ?></p>
	<?php } ?>
	<?php if(count($objectives->result()) != 0) { ?>
	<div class="row">
		<div class="col-md-12">
				<input id="chk_all" name="chk_all" type="checkbox"  /> Select all
		</div>
	</div>
	<?php } ?>
	<?php endif; ?>
	<?php 
	/* $hide_seek = 1; */
	foreach($objectives->result() as $r) : ?>
	<?php if($r->complete) : 
		/* $hide_seek--; */
	endif;
	?>
	<?php 
		$users = $this->task_model->get_task_objective_members($r->ID); 
		$users_arr = $users->result_array(); 
		$curr_username = $this->user->info->username;
		$user_role = $this->user->info->user_role;
	?>
	<?php
	/* 		if ((in_array($curr_username, array_column($users_arr, 'username'))) && ($user_role == 7)) 
			{ 
			echo "found"; 
			} 
			else
			{ 
			echo "not found"; 
			}  */
	?>
	<?php if($user_role == 7){
	if (in_array($curr_username, array_column($users_arr, 'username'))) {	?>
	<div class="row">
		<div class="col-md-12">
			<div class="col-xs-12">
			<div class="pull-right hide_seek<?php if(!$r->complete){ /* echo $hide_seek; */}?>" style="display:none;">
			<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
  			
			<?php if(!$r->complete) : ?>
			<!--
			<input type="button" class="start_btn btn btn-primary btn-xs" value="<?php //echo "Start" ?>" /> 
			<a href="<?php //echo site_url("tasks/complete_objective/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-xs" >
			<?php //echo lang("ctn_854") ?></a>	
			-->			
			<?php endif; ?> 
			
			<input type="button" class="btn btn-warning btn-xs" value="<?php echo lang("ctn_55") ?>" data-toggle="modal" data-target="#editSubtaskModal" onClick="editObjective(<?php echo $r->ID ?>)" /> 
			<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) : ?>
			<a href="<?php echo site_url("tasks/delete_objective/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_508") ?>')" >
			<?php echo lang("ctn_57") ?>
			</a>
			<?php endif; ?>
			<?php endif; ?>
			</div>
			<input name="chk_id[]" type="checkbox" class='chkbox' value="<?php echo $r->ID; ?>"/>
			<h4><?php echo $r->title; ?> 
			<?php if(!$r->started && !$r->complete && $r->task_status == '') { ?>
			<span style='color:#2aabd2;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> NEW</span>
			<?php } ?>
			<?php if($r->started && !$r->complete && $r->task_status == '') { ?>
			<span style='color:#265a88;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> IN PROGRESS</span>
			<?php } ?>
			<?php if($r->task_status == 'WIP' && !$r->complete) { ?>
			<span style='color:#8600b3;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> WIP</span>
			<?php } ?>
			<?php if($r->task_status == 'On Hold' && !$r->complete) { ?>
			<span style='color:#eb9316;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> ON HOLD</span>
			<?php } ?>
			<?php if($r->task_status == 'rework' && !$r->complete) { ?>
			<span style='color:#c12e2a;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> REWORK</span>
			<?php } ?>
			<?php if($r->complete) echo "<span class='completeText' style='font-weight:bold;'> <span class='glyphicon glyphicon-ok'></span> ".lang("ctn_855")."</span>" ?>
			</h4>
			<p class="small-text"><?php echo $r->description ?></p>
			<?php $users = $this->task_model->get_task_objective_members($r->ID); ?>
			<div class="task-objective-user subtask_user"><b><?php echo lang("ctn_856") ?></b></div>
			<?php foreach($users->result() as $rr) : ?>
				<div class="task-objective-user">
               <?php echo $this->common->get_user_display(array("username" => $rr->username, "empid" => $rr->employee_id, "avatar" => $rr->avatar, "online_timestamp" => $rr->online_timestamp)) ?>
				<span><?php echo $rr->username;?></span>
				</div>
			<?php endforeach; ?>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-6">			
					<div id="subtask_unit">	
						<label><?php echo "Target:"; ?></label>
						<span><?php if($task->unit == 'Page'){echo $target = $r->totalPages;}else{echo $target = $r->totalArticles;} ?></span>
						<?php if($task->unit != ''){echo $task->unit."(s)";} ?>
					</div>	
					<div id="subtask_complex">	
						<label><?php echo lang("ctn_1591"); ?>:</label>
						<?php echo $task->complexity; ?>
					</div>	
				</div>
				<div class="col-xs-6">			
					<div id="subtask_unit_completed">	
						<label><?php if($task->unit != ''){echo $task->unit."(s)";} echo " Completed:"; ?></label>
						<span><?php echo $prod_count = $r->productivity_count; ?></span>
					</div>
					<div id="subtask_productivity_percentage">	
						<label><?php echo "Productivity Percentage"; ?>:</label>
						<?php
						if($r->complete){
							echo $r->productivity_percentage."%";
						}else{
							echo "0.00"."%";
						}
						?>
						<?php
							/* $percentage = (($prod_count/$target)*100);
							echo round($percentage,2)."%"; */
						?>
					</div>	
				</div>			
			</div>
		</div>
		<div class="col-md-12">
		<div class="col-xs-12">	
		<?php if($r->started == 1 && $r->start_time != '00:00:00' && $r->start_date != '0000-00-00') : ?>
			<div class="col-xs-6">
				<div id="tasks_started">
				<span><b>Started Datetime: </b> <?php echo $r->start_date.' '.$r->start_time.'<br>'; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php if($r->complete == 1 && $r->completed_datetime != '0000-00-00 00:00:00') : ?>
			<div class="col-xs-6">
				<div id="tasks_completion">
				<span><b>Completed Datetime: </b> <?php echo $r->completed_datetime.'<br>'; ?></span>
				</div>
			</div>
		<?php endif; ?>	
		</div>	
		<div class="col-xs-12">	
		<?php if($r->started == 1 && $r->onhold_date != NULL && $r->onhold_time != NULL) : ?>
			<div class="col-xs-6">
				<div id="tasks_started">
				<span><b>Hold/WIP Datetime: </b> <?php echo $r->onhold_date.' '.$r->onhold_time.'<br>'; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php if($r->complete == 1 && $r->completed_datetime != '0000-00-00 00:00:00') : ?>
			<div class="col-xs-6">		
			<div id="total_working_hours">
			<span><b>Total Duration: </b>
			<?php 
			if($r->onhold_time != '00:00:00' && $r->onhold_date != '0000-00-00' && $r->onhold_date != NULL){
				$time_start = strtotime($r->start_date . $r->start_time);
				$time_hold = strtotime($r->onhold_date . $r->onhold_time);
				$start_hold = abs($time_hold - $time_start);
				
				$hours_srt_hld = round(($start_hold/3600),2);
				$days_srt_hld = round($hours_srt_hld/24);
				$remain_srt_hld = floor($start_hold/(60*60*24));
				$remain_hrs_srt_hld = round(($start_hold-$remain_srt_hld*60*60*24)/(60*60));
				
				$time_hold = strtotime($r->onhold_date . $r->onhold_time);
				$time_completed = strtotime($r->completed_datetime);
				$hold_completed = abs($time_completed - $time_hold);
				
				$hours_hld_cptd = round(($hold_completed/3600),2);
				$days_hld_cptd = round($hours_hld_cptd/24);
				$remain_hld_cptd = floor($hold_completed/(60*60*24));
				$remain_hrs_hld_cptd = round(($hold_completed-$remain_hld_cptd*60*60*24)/(60*60));
				
				if( $days_srt_hld < 1 && $days_hld_cptd < 1)
				{
					$hours = $hours_srt_hld + $hours_hld_cptd;
					$days = 0;
				}
				elseif( $days_srt_hld < 1 && $days_hld_cptd > 1)
				{
					$remain_hrs = $hours_srt_hld + $remain_hrs_hld_cptd;
					$days = $days_hld_cptd;
				}
				elseif( $days_srt_hld > 1 && $days_hld_cptd < 1)
				{
					$remain_hrs = $remain_hrs_srt_hld + $hours_hld_cptd;
					$days = $days_srt_hld;
				}
				else
				{
					$days = $days_srt_hld + $days_hld_cptd;
					$remain_hrs = $remain_hrs_srt_hld + $remain_hrs_hld_cptd;
				} 		
			}
			else{
				$time1 = strtotime($r->start_date . $r->start_time);
				$time2 = strtotime($r->completed_datetime);
				$diff= abs($time2 - $time1);
				$hours = round(($diff/3600),2);
				$days = round($hours/24);
				
				$remain=floor($diff/(60*60*24));
				$remain_hrs = round(($diff-$remain*60*60*24)/(60*60));
			}
			
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
			?>
			</div>
			</div>
		<?php endif; ?> 	
		</div>	
		</div>
	</div>
	<hr>
	<?php  }}
	else{ ?>
	<div class="row">
		<div class="col-md-12">
			<div class="col-xs-12">
			<div class="pull-right hide_seek<?php if(!$r->complete){ /* echo $hide_seek; */}?>" style="display:none;">
			<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
  			
			<?php if(!$r->complete) : ?>
			<!--
			<input type="button" class="start_btn btn btn-primary btn-xs" value="<?php //echo "Start" ?>" /> 
			<a href="<?php //echo site_url("tasks/complete_objective/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-xs" >
			<?php //echo lang("ctn_854") ?></a>	
			-->			
			<?php endif; ?> 
			
			<input type="button" class="btn btn-warning btn-xs" value="<?php echo lang("ctn_55") ?>" data-toggle="modal" data-target="#editSubtaskModal" onClick="editObjective(<?php echo $r->ID ?>)" /> 
			<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) : ?>
			<a href="<?php echo site_url("tasks/delete_objective/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_508") ?>')" >
			<?php echo lang("ctn_57") ?>
			</a>
			<?php endif; ?>
			<?php endif; ?>
			</div>
			<input name="chk_id[]" type="checkbox" class='chkbox' value="<?php echo $r->ID; ?>"/>
			<h4><?php echo $r->title; ?> 
			<?php if(!$r->started && !$r->complete && $r->task_status == '') { ?>
			<span style='color:#2aabd2;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> NEW</span>
			<?php } ?>
			<?php if($r->started && !$r->complete && $r->task_status == '') { ?>
			<span style='color:#265a88;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> IN PROGRESS</span>
			<?php } ?>
			<?php if($r->task_status == 'WIP' && !$r->complete) { ?>
			<span style='color:#8600b3;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> WIP</span>
			<?php } ?>
			<?php if($r->task_status == 'On Hold' && !$r->complete) { ?>
			<span style='color:#eb9316;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> ON HOLD</span>
			<?php } ?>
			<?php if($r->task_status == 'rework' && !$r->complete) { ?>
			<span style='color:#c12e2a;font-size:15px;'> <span class='glyphicon glyphicon-arrow-right'></span> REWORK</span>
			<?php } ?>
			<?php if($r->complete) echo "<span class='completeText' style='font-weight:bold;'> <span class='glyphicon glyphicon-ok'></span> ".lang("ctn_855")."</span>" ?>
			</h4>
			<p class="small-text"><?php echo $r->description ?></p>
			<?php $users = $this->task_model->get_task_objective_members($r->ID); ?>
			<div class="task-objective-user subtask_user"><b><?php echo lang("ctn_856") ?></b></div>
			<?php foreach($users->result() as $rr) : ?>
				<div class="task-objective-user">
                <?php echo $this->common->get_user_display(array("username" => $rr->username, "empid" => $rr->employee_id, "avatar" => $rr->avatar, "online_timestamp" => $rr->online_timestamp)) ?>
				<span><?php echo $rr->username;?></span>
				</div>
			<?php endforeach; ?>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-6">			
					<div id="subtask_unit">	
						<label><?php echo "Target:"; ?></label>
						<span><?php if($task->unit == 'Page'){echo $target = $r->totalPages;}else{echo $target = $r->totalArticles;} ?></span>
						<?php if($task->unit != ''){echo $task->unit."(s)";} ?>
					</div>	
					<div id="subtask_complex">	
						<label><?php echo lang("ctn_1591"); ?>:</label>
						<?php echo $task->complexity; ?>
					</div>	
				</div>
				<div class="col-xs-6">			
					<div id="subtask_unit_completed">	
						<label><?php if($task->unit != ''){echo $task->unit."(s)";} echo " Completed:"; ?></label>
						<span><?php echo $prod_count = $r->productivity_count; ?></span>
					</div>
					<div id="subtask_productivity_percentage">	
						<label><?php echo "Productivity Percentage"; ?>:</label>
						<?php
						if($r->complete){
							echo $r->productivity_percentage."%";
						}else{
							echo "0.00"."%";
						}
						?>
						<?php
							/* $percentage = (($prod_count/$target)*100);
							echo round($percentage,2)."%"; */
						?>
					</div>	
				</div>			
			</div>
		</div>
		<div class="col-md-12">
		<div class="col-xs-12">	
		<?php if($r->started == 1 && $r->start_time != '00:00:00' && $r->start_date != '0000-00-00') : ?>
			<div class="col-xs-6">
				<div id="tasks_started">
				<span><b>Started Datetime: </b> <?php echo $r->start_date.' '.$r->start_time.'<br>'; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php if($r->complete == 1 && $r->completed_datetime != '0000-00-00 00:00:00') : ?>
			<div class="col-xs-6">
				<div id="tasks_completion">
				<span><b>Completed Datetime: </b> <?php echo $r->completed_datetime.'<br>'; ?></span>
				</div>
			</div>
		<?php endif; ?>	
		</div>	
		<div class="col-xs-12">	
		<?php if($r->started == 1 && $r->onhold_date != NULL && $r->onhold_time != NULL) : ?>
			<div class="col-xs-6">
				<div id="tasks_started">
				<span><b>Hold/WIP Datetime: </b> <?php echo $r->onhold_date.' '.$r->onhold_time.'<br>'; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php if($r->complete == 1 && $r->completed_datetime != '0000-00-00 00:00:00') : ?>
			<div class="col-xs-6">		
			<div id="total_working_hours">
			<span><b>Total Duration: </b>
			<?php 
			if($r->onhold_time != '00:00:00' && $r->onhold_date != '0000-00-00' && $r->onhold_date != NULL){
				$time_start = strtotime($r->start_date . $r->start_time);
				$time_hold = strtotime($r->onhold_date . $r->onhold_time);
				$start_hold = abs($time_hold - $time_start);
				
				$hours_srt_hld = round(($start_hold/3600),2);
				$days_srt_hld = round($hours_srt_hld/24);
				$remain_srt_hld = floor($start_hold/(60*60*24));
				$remain_hrs_srt_hld = round(($start_hold-$remain_srt_hld*60*60*24)/(60*60));
				
				$time_hold = strtotime($r->onhold_date . $r->onhold_time);
				$time_completed = strtotime($r->completed_datetime);
				$hold_completed = abs($time_completed - $time_hold);
				
				$hours_hld_cptd = round(($hold_completed/3600),2);
				$days_hld_cptd = round($hours_hld_cptd/24);
				$remain_hld_cptd = floor($hold_completed/(60*60*24));
				$remain_hrs_hld_cptd = round(($hold_completed-$remain_hld_cptd*60*60*24)/(60*60));
				
				if( $days_srt_hld < 1 && $days_hld_cptd < 1)
				{
					$hours = $hours_srt_hld + $hours_hld_cptd;
					$days = 0;
				}
				elseif( $days_srt_hld < 1 && $days_hld_cptd > 1)
				{
					$remain_hrs = $hours_srt_hld + $remain_hrs_hld_cptd;
					$days = $days_hld_cptd;
				}
				elseif( $days_srt_hld > 1 && $days_hld_cptd < 1)
				{
					$remain_hrs = $remain_hrs_srt_hld + $hours_hld_cptd;
					$days = $days_srt_hld;
				}
				else
				{
					$days = $days_srt_hld + $days_hld_cptd;
					$remain_hrs = $remain_hrs_srt_hld + $remain_hrs_hld_cptd;
				} 		
			}
			else{
				$time1 = strtotime($r->start_date . $r->start_time);
				$time2 = strtotime($r->completed_datetime);
				$diff= abs($time2 - $time1);
				$hours = round(($diff/3600),2);
				$days = round($hours/24);
				
				$remain=floor($diff/(60*60*24));
				$remain_hrs = round(($diff-$remain*60*60*24)/(60*60));
			}
			
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
			?>
			</div>
			</div>
		<?php endif; ?> 	
		</div>	
		</div>
	</div>
	<hr>
	<?php } ?>
<?php /* $hide_seek++;  */
endforeach; ?>
<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
	<?php if(count($objectives->result()) != 0) { ?>
	<input id="submit" name="submit" type="submit" class="btn btn-danger" value="Delete Selected Row(s)" />
	<?php } ?>
	<?php echo form_close() ?>
<?php endif; ?>	
</div>

<?php if($this->settings->info->enable_time && $this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
<div class="white-area-content content-separator">

<div class="db-header clearfix">
    <div class="task-header-title"> <span class="glyphicon glyphicon-time"></span> <?php echo lang("ctn_857") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("time/add_timer/?timer_get=1&projectid=" . $task->projectid . "&taskid=" . $task->ID . "&rate=" . number_format($this->user->info->time_rate,2,'.','') . "&hash=" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_858") ?></a> </div>
</div>

<canvas id="myChart" class="graph-height"></canvas>

</div>
<?php endif; ?>

<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
<div class="white-area-content content-separator">

<div class="db-header clearfix">
    <div class="task-header-title"> <span class="glyphicon glyphicon-comment"></span> <?php echo lang("ctn_859") ?></div>
    <div class="db-header-extra"> </div>
</div>

<?php foreach($messages->result() as $r) : ?>
<div class="media">
  <div class="media-left">
     <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
  </div>
  <div class="media-body">
  <div class="pull-right">
  <a href="<?php echo site_url("tasks/delete_message/" . $r->taskid . "/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_57") ?></a>
  </div>
    <?php echo $r->message ?>
    <p class="small-text"><?php echo lang("ctn_860") ?> <?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
  </div>
</div>
<hr>
<?php endforeach; ?>
<div class="align-center"><?php echo $this->pagination->create_links() ?></div>
<hr>
<h4><?php echo lang("ctn_861") ?></h4>
<?php echo form_open(site_url("tasks/add_message/" . $task->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                <div class="col-md-12 ui-front">
                   <textarea name="message" id="msg-area"></textarea>
                </div>
        </div>
<p><input type="submit" class="form-control btn btn-primary btn-sm" value="<?php echo lang("ctn_862") ?>" /></p>
<?php echo form_close(); ?>

</div>
<?php endif; ?>


</div>
<div class="col-md-4">
<div class="white-area-content">
<div class="db-header clearfix">
    <div class="task-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_863") ?></div>
    <div class="db-header-extra"> <?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?><button id="updatedetails-button-update" type="button" class="btn btn-default btn-xs"> <span class="glyphicon glyphicon-refresh spin"></span></button> <button onclick="update_task()" class="btn btn-primary btn-xs"><?php echo lang("ctn_864") ?></button><?php endif; ?> </div>
</div>

<div class="form-group">
      <label for="username-in" class="col-md-5 label-heading"><?php echo lang("ctn_827") ?></label>
      <div class="col-md-7">
        <input type="text" id="start_date" class="form-control datepicker" value="<?php echo date($this->settings->info->date_picker_format,$task->start_date) ?>">
      </div>
  </div>
  <br /><br />
  <div class="form-group">
      <label for="username-in" class="col-md-5 label-heading"><?php echo lang("ctn_828") ?> <span class="text-danger"><?php if($task->due_date + (24*3600) < time() && $task->status != 3) echo lang("ctn_865") ?></span></label>
      <div class="col-md-7">
        <input type="text" id="due_date" class="form-control datepicker" value="<?php echo date($this->settings->info->date_picker_format,$task->due_date) ?>">
      </div>
  </div>
  <br /><br />
  <?php 
  /* print_r($objectives->result()); */
  if(!empty($objectives->result())){
  $productivity_completed = 0;
  $sum = 0;
  foreach($objectives->result() as $r) : 
  if(($r->complete == 1) && ($r->productivity_percentage != 0.00)){
	 $sum+= $r->productivity_percentage;
	 $productivity_completed++;
  }
  endforeach;
  if($sum > 0){
  $cumul = $sum/$productivity_completed;
  }
  }
  ?>
  <?php if(isset($cumul)){ ?>
  <div class="form-group clearfix">
      <label for="username-in" class="col-md-5 label-heading"><?php echo "Total Productivity Percentage" ?> 
	  </label>
	  <div class="col-md-7">
        <div id="progressamount">
		  <?php echo round($cumul,2); ?>%
	    </div>
	  </div>
  </div>
  <br /><br />
  <?php } ?>
  
  <div class="form-group clearfix">
      <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_849") ?> <div id="progressamount"><?php echo $task->complete ?>%</div></label>
      <div class="col-md-8">
        <div id="progressslider"></div>
        <input type="hidden" id="progressamountval" class="form-control" value="<?php echo $task->complete ?>">
        <input type="checkbox" id="sync" value="1" <?php if($task->complete_sync) echo "checked" ?>> <?php echo lang("ctn_866") ?>
      </div>
  </div> 
</div>

<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="task-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_867") ?></div>
    <div class="db-header-extra"> 
		<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
			<button data-toggle="modal" data-target="#addMemberModal" class="btn btn-primary btn-xs">
				<?php echo lang("ctn_868") ?>
			</button> 
		<?php endif; ?> 
	</div>
</div>

<table class="table table-bordered table-hover">
<?php foreach($task_members->result() as $r) : ?>
<tr>
	<td> 
		<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?>
	</td>
	<td>
		<?php
		$pmcount = 0;
		$pmsum = 0;
		foreach($productivity_members->result() as $pm) : 
		if($pm->userid == $r->userid){
		$pmsum+= $pm->productivity_percentage;
		$pmcount++;
		}
		endforeach;
		if($pmsum > 0){
		?>
		<p style="font-size:11px;"><b>Productivity:</b></p>
		<?php 
		  $user_productivity = $pmsum/$pmcount;
		  echo round($user_productivity,2)."%";
		} 
		?>
	</td>
	<td>
		<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
		<button id="remind-user-<?php echo $r->ID ?>" class="btn btn-info btn-xs" onclick="remind_user(<?php echo $r->ID ?>)" title="<?php echo lang("ctn_870") ?>">
		<span class="glyphicon glyphicon-bell"></span>
		</button> 
		<a href="<?php echo site_url("tasks/remove_member/" . $r->userid . "/" . $task->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs">
		<span class="glyphicon glyphicon-trash"></span>
		</a>
		<?php endif; ?>
	</td>
</tr>

<?php endforeach; ?>
</table>
</div>

<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="task-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_872") ?></div>
	<?php $filecount = count($files->result()); 
	if($filecount < 1){
	?>   
   <div class="db-header-extra"> 
		<button data-toggle="modal" data-target="#addFileModal" class="btn btn-primary btn-xs">
			<?php 
				echo lang("ctn_873");
			?>
		</button> 
	</div>
	<?php } ?>  
</div>

<table class="table table-bordered table-hover">
<tr class="table-header"><td><?php echo lang("ctn_874") ?></td><td><?php echo lang("ctn_875") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($files->result() as $r) : ?>
<tr><td><a href="<?php echo site_url("files/view_file/" . $r->fileid) ?>"><?php echo $r->file_name ?><?php echo $r->extension ?></a></td><td><?php echo $r->file_type ?></td><td><a href="<?php echo site_url("tasks/remove_file/" . $r->taskid . "/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_871") ?></a></td></tr>
<?php endforeach; ?>
</table>

</div>
<?php endif; ?>

<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="task-header-title"><span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_1565"); ?></div>
    <div class="db-header-extra">  <button data-toggle="modal" data-target="#addDependenciesModal" class="btn btn-primary btn-xs"><?php echo lang("ctn_1316"); ?></button></div>
</div>

<table class="table table-bordered table-hover">
<tr class="table-header"><td><?php echo lang("ctn_847"); ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($dependencies->result() as $r) : ?>
<tr><td><a href="<?php echo site_url("tasks/view/" . $r->taskid) ?>"><?php echo $r->name ?></a></td><td><a href="<?php echo site_url("tasks/remove_dependency/" . $r->ID."/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>

</div>
<?php endif; ?>

<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="task-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_876") ?></div>
    <div class="db-header-extra">  <a href="<?php echo site_url("tasks/view_activity/" . $task->ID) ?>" class="btn btn-info btn-xs"><?php echo lang("ctn_877") ?></a></div>
</div>

<table class="table table-bordered table-hover">
<tr class="table-header"><td><?php echo lang("ctn_878") ?></td><td><?php echo lang("ctn_879") ?></td></tr>
<?php foreach($actions->result() as $r) : ?>
<tr><td> <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?></td><td><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> <?php echo $r->message ?><p class="small-text"><a href="<?php site_url($r->url) ?>"><?php echo lang("ctn_880") ?></a> - <?php echo date($this->settings->info->date_format, $r->timestamp) ?></p></td></tr>
<?php endforeach; ?>
</table>

</div>
<?php endif; ?>

</div>

</div>


<!-- Modal -->
<div class="modal fade" id="addDependenciesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_1568"); ?></h4>
      </div>
      <div class="modal-body">
       <?php echo form_open(site_url("tasks/add_task_dependency/" . $task->ID), array("class" => "form-horizontal")) ?>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_696"); ?></label>
                <div class="col-md-8 ui-front">
                    <select class="form-control" name="taskid">
                    <?php foreach($tasks->result() as $r) : ?>
                      <?php if($r->ID != $task->ID) : ?>
                        <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                      <?php endif; ?>
                    <?php endforeach; ?>
                    </select>
                </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1316"); ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_881") ?></h4>
      </div>
      <div class="modal-body">
       <?php echo form_open(site_url("tasks/add_task_member/" . $task->ID), array("class" => "form-horizontal")) ?>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_882") ?></label>
                <div class="col-md-8 ui-front">
                    <select id="add_title_members" class="form-control" name="userid[]" multiple>
                    <?php foreach($members->result() as $r) : ?>
                    	<option id="<?php echo "role_".$r->user_role; ?>" value="<?php echo $r->userid ?>"><?php echo $r->username ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_881") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_853") ?></h4>
      </div>
      <div class="modal-body">
       <?php echo form_open(site_url("tasks/add_task_objective/" . $task->ID), array("class" => "form-horizontal")) ?>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_839") ?></label>
                <div class="col-md-8 ui-front">
                   <input type="text" class="form-control" name="title" />
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_840") ?></label>
                <div class="col-md-8 ui-front">
                   <textarea name="description" id="objective-area"></textarea>
                </div>
        </div>
		<input type="hidden" id="subtask_get_complex" class="form-control" name="subtask_get_complex" value="<?php echo $task->complexity; ?>">	
		<input type="hidden" id="subtask_get_unit" class="form-control" name="subtask_get_unit" value="<?php echo $task->unit; ?>">	
		<div class="form-group">
			<label for="p-in" class="col-md-4 label-heading"><?php echo "Task Target"; ?></label>
			<div class="col-md-8 ui-front">
			<input type="number" id="subtask_target" class="form-control" name="subtask_target" value="">
			</div>
		</div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_841") ?></label>
                <div class="col-md-8 ui-front">
                   <?php foreach($task_members->result() as $r) : ?>
                   	<div class="task-objective-user">
                   	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="user-icon" /> <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a>
                   	<input type="checkbox" name="user_<?php echo $r->ID ?>" value="1">
                   	</div>
                   <?php endforeach; ?>
                </div>
        </div>
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
					<option value="<?php echo $chl; ?>"><?php echo $chl; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_853") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="editSubtaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="editObjective">
     <span class="glyphicon glyphicon-refresh spin"></span>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_873") ?></h4>
      </div>
      <div class="modal-body">
       <?php echo form_open(site_url("tasks/add_file/" . $task->ID), array("class" => "form-horizontal")) ?>
        <div class="form-group">
			<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_883") ?></label>
			<div class="col-md-6 ui-front">
				<input type="text" name="file_search" id="file-search">
				<input type="hidden" name="file_search_id" id="file-search-hidden">
				<span class="help-block"><?php echo lang("ctn_884") ?></span>
			</div>
			<div class="col-md-2" id="file-link">

			</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_873") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

		
<script type="text/javascript">
$(document).ready(function() {
	$("input.start_btn").click(function(){
	 // $("#task_started").show();
	 $(this).hide();
	  changeStatus(2);
	});
	/* var default_tasks = 0;
	document.cookie = "tasks_dft="+ default_tasks; */
	var task_id = <?php echo $task->ID; ?>;
	var complexity = "<?php echo $task->complexity; ?>";
	var unit = "<?php echo $task->unit; ?>";
	/* $("#default_tasks").change(function(){
		var default_tasks = $(this).val();
		//alert(default_tasks);
		if(default_tasks == 1){
			$("#default_ato #subtask_complexity").val(complexity);
			$("#default_ato #subtask_unit").val(unit);
			$("#default_ato input#default_pdf_xml_ato").click();
		}
		else if(default_tasks == 2){
			$("#default_wtx #subtask_complexity").val(complexity);
			$("#default_wtx #subtask_unit").val(unit);
			$("#default_wtx input#default_word_xml_wtx").click();
		}
		else if(default_tasks == 3){
			$("#default_xtx #subtask_complexity").val(complexity);
			$("#default_xtx #subtask_unit").val(unit);
			$("#default_xtx input#default_xml_xml_xtx").click();
		}
		else {
			//$("#default_xtx input#default_xml_xml_xtx").click();
			//alert('0'); $task->ID 
		}
		document.cookie = "tasks_dft_"+task_id+"="+ default_tasks;
		//$('#default_tasks').prop('disabled', true);  
	});*/
	
	var prev_val;
	$('#default_tasks').focus(function() {
		prev_val = $(this).val();
	}).change(function() {
		$(this).blur() // Firefox fix as suggested by AgDude
		var success = confirm('Are you sure you want to create the Workflow?');
		if(success)
		{
			/* alert('changed'); */
		var default_tasks = $(this).val();
		if(default_tasks == 1){
			$("#default_ato #subtask_complexity").val(complexity);
			$("#default_ato #subtask_unit").val(unit);
			$("#default_ato input#default_pdf_xml_ato").click();
		}
		else if(default_tasks == 2){
			$("#default_wtx #subtask_complexity").val(complexity);
			$("#default_wtx #subtask_unit").val(unit);
			$("#default_wtx input#default_word_xml_wtx").click();
		}
		else if(default_tasks == 3){
			$("#default_xtx #subtask_complexity").val(complexity);
			$("#default_xtx #subtask_unit").val(unit);
			$("#default_xtx input#default_xml_xml_xtx").click();
		}
		else {
			/* $("#default_xtx input#default_xml_xml_xtx").click();
			alert('0'); $task->ID;  */
		}
		document.cookie = "tasks_dft_"+task_id+"="+ default_tasks;
		/* $('#default_tasks').prop('disabled', true);  */
		}  
		else
		{
			$(this).val(prev_val);
			/* alert('unchanged'); */
			return false; 
		}
	});
	
	<?php if($task->due_date + (24*3600) < time() && $task->status != 3){  ?>
		/* $("#overdue_click").click();
		alert('overdue'); */
	<?php }  ?>
});
</script>
<script type="text/javascript">
CKEDITOR.replace('objective-area', { height: '100'});
CKEDITOR.replace('msg-area', { height: '100'});

$(document).ready(function() {
    var myChart = new Chart($("#myChart"), {
      type: 'line',
      data: {
          labels: [
          <?php foreach($last_dates as $d) : ?>
          "<?php echo $d['date'] ?>",
          <?php endforeach; ?>
          ],
          datasets: [{
              label: "Time Logged For Title",
              lineTension: 0.1,
              backgroundColor: "rgba(75,192,192,0.4)",
              borderColor: "rgba(75,192,192,1)",
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
              data: [
              <?php foreach($last_dates as $d) : ?>
              <?php echo $d['hours'] ?>,
              <?php endforeach; ?>
              ]
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  }
              }]
          }
      }
  }); 
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#chk_all').click(function(){
        if(this.checked)
            $(".chkbox").prop("checked", true);
        else
            $(".chkbox").prop("checked", false);
    });
});

$(document).ready(function(){
    $('#delete_tasks_form').submit(function(e){
        if(!confirm("Are you sure to delete this Task(s) ?")){
            e.preventDefault();
        }
    });
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(document).on("change", "select#add_title_members", function(){
		$('select#add_title_members option#role_15').prop('selected', true);
		$("#select#add_title_members option#role_15:selected").attr('disabled','disabled');
	});		
});	
</script>	
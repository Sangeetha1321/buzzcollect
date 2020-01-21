<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1605") ?></div>
    <div class="db-header-extra"> 
	<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
		<?php echo lang("ctn_1611") ?>
	</button>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo lang("ctn_1613") ?></td>
	<td><?php echo lang("ctn_1614") ?></td>
	<td><?php echo lang("ctn_1615") ?></td>
	<td><?php echo lang("ctn_1616") ?></td>
	<td><?php echo lang("ctn_1617") ?></td>
	<td><?php echo lang("ctn_1618") ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
<?php foreach($machine_details->result() as $r) : ?>
<tr>
	<td>
		<?php echo $r->machine_no ?>
	</td>
	<td>
		<?php echo $r->team_name ?>
	</td>
	<td>
		<?php echo $r->softwares ?>
	</td>
	<td>
		<?php echo $r->shifts ?>
	</td>
	<td>
		<?php echo $r->seat_no ?>
	</td>
	<td>
		<?php echo $r->head_count ?>
	</td>
	<td>
		<a href="<?php echo site_url("machine/edit_machine/" . $r->ID) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
			<span class="glyphicon glyphicon-cog"></span>
		</a>
		<a href="<?php echo site_url("machine/delete_machine/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
<?php endforeach; ?>
</table>

</div>

</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_1611") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("machine/add_machine"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
				<label for="machine_no" class="col-md-4 label-heading"><?php echo lang("ctn_1613") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="machine_no" value="">
				</div>
            </div>
			<div class="form-group">
				<label for="team_name" class="col-md-4 label-heading"><?php echo lang("ctn_1614") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="team_name" value="">
				</div>
            </div>
			<div class="form-group">
				<label for="softwares" class="col-md-4 label-heading"><?php echo lang("ctn_1615") ?></label>
				<div class="col-md-8 ui-front">
				<select name="softwares[]" multiple="multiple" class="form-control">
					<?php foreach($softwares->result() as $r) : ?>
						<option value="<?php echo $r->softwares_available ?>"><?php echo $r->softwares_available ?></option>
					<?php endforeach; ?>
				</select>
				</div>
			</div>		
			<div class="form-group">
				<label for="shifts" class="col-md-4 label-heading"><?php echo lang("ctn_1616") ?></label>
				<div class="col-md-8 ui-front">
				<select name="shifts[]" multiple="multiple" class="form-control">
					<?php 
					//$shifts_arr = array('First Shift','Second Shift','Night Shift','General Shift','OT - 6am to 6pm','OT - 6pm to 6am','OT - 10am to 10pm','OT - 10pm to 10am');
					foreach($shifts->result() as $r) : ?>
						<option value="<?php echo $r->shift_type ?>"><?php echo $r->shift_type ?></option>
					<?php endforeach; ?>
				</select>
				</div>
			</div>	
			<div class="form-group">
				<label for="seat_no" class="col-md-4 label-heading"><?php echo lang("ctn_1617") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="seat_no" value="">
				</div>
            </div>
			<div class="form-group">
				<label for="head_count" class="col-md-4 label-heading"><?php echo lang("ctn_1618") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="head_count" value="">
				</div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1611") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="import_projects">
	<div class="page-header-title"> 
	<span class="glyphicon glyphicon-import"></span> 
	Import/Export a CSV file
	</div>
	<?php if($this->session->flashdata('success')):?>
	<div class="alert alert-success alert-dismissible fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $this->session->flashdata('success'); ?>
	</div>
	<?php endif; ?>
	<?php if($this->session->flashdata('error')):?>
		<div class="alert alert-danger alert-dismissible fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $this->session->flashdata('error'); ?>
		</div>
	<?php endif; ?>
	<div class="admin_row equal_height">
		<br><br>
		<div align="center">
			<?php echo form_open(site_url("machine/import"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post")) ?>    
				<input type="file" name="file" id="file" class="import_file">
				<button type="submit" id="machine_import" name="import" title="Import Csv" class="import_btn btn btn-primary button-loading">Import Csv</button>
				<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/machine_details.csv" title="Example of csv file">Example of csv file</a>
				<br><br>
		</div>
		<div class="loader"></div>
		<style type="text/css">
		${demo.css}
		</style>
	</div>
	<div align="center" class="export">
		<a id="sample_csv" class="btn btn-primary" href="<?php echo site_url('machine/export')?>" title="Export Machine Details">Export Csv</a>
	</div>		
</div>
<script type="text/javascript">
$(document).ready(function () {
	$(window).load(function() { 
		$('.loader').hide();
	});
	$(document).on("click", "#machine_import", function () {
		$('.loader').show();
		return true;
	}); 
});
</script>
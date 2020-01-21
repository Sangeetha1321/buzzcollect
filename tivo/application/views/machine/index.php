<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1605") ?></div>
    <div class="db-header-extra form-inline"> 
		<div class="form-group has-feedback no-margin">
		<div class="input-group">
		<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_354") ?>" id="form-search-input" />
		<div class="input-group-btn">
		<input type="hidden" id="search_type" value="0">
		<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
		<ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
		<li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
		<li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
		<li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_357") ?></a></li>
		<li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="fn-exact"></span> <?php echo lang("ctn_358") ?></a></li>
		<li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="ln-exact"></span> <?php echo lang("ctn_359") ?></a></li>
		<li><a href="#" onclick="change_search(5)"><span class="glyphicon glyphicon-ok no-display" id="role-exact"></span> <?php echo lang("ctn_360") ?></a></li>
		<li><a href="#" onclick="change_search(6)"><span class="glyphicon glyphicon-ok no-display" id="email-exact"></span> <?php echo lang("ctn_361") ?></a></li>
		</ul>
		</div><!-- /btn-group -->
		</div>
		</div>
		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
			<?php echo lang("ctn_1611") ?>
		</button>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>


<div class="table-responsive">
<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('machine/export')?>" title="Export Csv">
	Export All Entries
</a>
<table id="machine-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
	<td><?php echo "ID"; ?></td>
	<td><?php echo lang("ctn_1613") ?></td>
	<td><?php echo lang("ctn_1614") ?></td>
	<td><?php echo lang("ctn_1615") ?></td>
	<td><?php echo lang("ctn_1616") ?></td>
	<td><?php echo lang("ctn_1617") ?></td>
	<td><?php echo lang("ctn_1618") ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
</thead>
<tbody>
</tbody>
<!--
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
-->
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
				<select name="team_name" class="form-control" id="team_name">
					<option value=""><?php echo "Select the Team Name"; ?></option>
					<?php if(isset($team_name)){ foreach($team_name->result() as $r) : ?>
						<option value="<?php echo $r->team_name; ?>"><?php echo $r->team_name; ?></option>
					<?php endforeach; } ?>
				</select>
				</div>
            </div>
			<div class="form-group">
				<label for="softwares" class="col-md-4 label-heading"><?php echo lang("ctn_1615") ?></label>
				<div class="col-md-8 ui-front">
				<select name="softwares[]" multiple="multiple" class="form-control">
					<?php foreach($softwares->result() as $r) : ?>
						<option value="<?php echo $r->softwares_available ?>">
							<?php echo $r->softwares_available ?>
						</option>
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
	Import an Excel file
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
			<?php echo form_open(site_url("machine/upload_machine"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post")) ?>    
				<input type="file" name="uploadmachine" id="file" class="import_file">
				<button type="submit" id="machine_import" name="uploadmac" title="Import Excel" class="import_btn btn btn-primary button-loading">Import Excel</button>
				<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/machine_details.xlsx" title="An Example file">Example file</a>
				<br><br>
		</div>
		<div class="loader"></div>
		<style type="text/css">
		${demo.css}
		</style>
	</div>	
</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#machine-table').DataTable({
        "dom" : "B<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        buttons: [
          { "extend": 'copy', "text":'<?php echo lang("ctn_1551") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'csv', "text":'<?php echo lang("ctn_1552") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'excel', "text":'<?php echo lang("ctn_1553") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'pdf', "text":'<?php echo lang("ctn_1554") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'print', "text":'<?php echo lang("ctn_1555") ?>',"className": 'btn btn-default btn-sm' }
        ],
        "order": [
          [0, "asc" ]
        ],
        "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("machine/machine_page") ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#form-search-input').on('keyup change', function () {
    table.search(this.value).draw();
});

} );
</script>
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
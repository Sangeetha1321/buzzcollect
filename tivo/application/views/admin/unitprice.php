<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Unitprice Settings"; ?></div>
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
		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo "Add Unitprice"; ?></button>
	</div>
</div>

<p><?php //echo lang("ctn_764") ?></p>

<div class="table-responsive">
<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/exportallunitprice')?>" title="Export Csv">
	Export All Entries
</a>
<table id="unitprice-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
	<td><?php echo "ID"; ?></td>
	<td><?php echo "Publisher"; ?></td>
	<td><?php echo "Process"; ?></td>
	<td><?php echo "Stage"; ?></td>
	<td><?php echo "PDF Type"; ?></td>
	<td><?php echo "Complexity"; ?></td>
	<td><?php echo "Unit"; ?></td>
	<td><?php echo "Unitprice"; ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
</thead>
<tbody>
</tbody>
<!--
<?php foreach($unitprice->result() as $r) : ?>
<tr>
	<td>
		<?php echo $r->ID; ?>
	</td>
	<td>
		<?php echo $r->publisher; ?>
	</td>
	<td>
		<?php echo $r->process; ?></label>
	</td>
	<td>
		<?php echo $r->stage; ?>
	</td>
	<td>
		<?php echo $r->pdfType; ?>
	</td>
	<td>
		<?php echo $r->complexity; ?>
	</td>
	<td>
		<label class="label label-default"><?php echo $r->unit; ?></label>
	</td>
	<td>
		<?php echo "$".$r->unitprice; ?>
	</td>
	<td>
		<a href="<?php echo site_url("admin/edit_unitprice/" . $r->ID) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
			<span class="glyphicon glyphicon-cog"></span>
		</a>
		<a href="<?php echo site_url("admin/delete_unitprice/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
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
        <h4 class="modal-title" id="myModalLabel"><?php echo "Add" ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("admin/add_unitprice"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="publisher" class="col-md-4 label-heading"><?php echo "Publisher"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="publisher" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="process" class="col-md-4 label-heading"><?php echo "Process"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="process" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="stage" class="col-md-4 label-heading"><?php echo "Stage"; ?></label>
                    <div class="col-md-8 ui-front">
						<!-- <select name="stage" id="stage" class="form-control">
                        	<option value="">Select Stage</option>
							<option value="Fulltext">Fulltext</option>
							<option value="Head and Tail">Head and Tail</option>
							<option value="Ultralite">Ultralite</option>
                        </select> -->
						<input type="text" class="form-control" name="stage" id="stage" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="pdfType" class="col-md-4 label-heading"><?php echo "PDF Type"; ?></label>
                    <div class="col-md-8 ui-front">
						<!-- <select name="pdfType" id="pdfType" class="form-control">
                        	<option value="">Select PDF Type</option>
							<option value="Editable">Editable</option>
							<option value="Scanned">Scanned</option>
                        </select> -->
						<input type="text" class="form-control" name="pdfType" id="pdfType" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="complexity" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="complexity" id="title_complexity" class="form-control">
                        	<option value="">Select Complexity</option>
							<option value="simple">Simple</option>
							<option value="medium">Medium</option>
							<option value="complex">Complex</option>
							<option value="heavycomplex">Heavy Complex</option>
                        </select>
                    </div>
            </div>
			<div class="form-group">
                    <label for="unit" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
                    <div class="col-md-8 ui-front">
                        <!-- <select name="unit" id="title_unit" class="form-control">
                        	<option value="">Select Unit</option>
							<option value="Page">Page</option>
							<option value="Article">Article</option>
							<option value="table">Table</option>
							<option value="figure">Figure</option>
							<option value="image">Image</option>
                        </select> -->
						<input type="text" class="form-control" name="unit" id="title_unit" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="unitprice" class="col-md-4 label-heading"><?php echo "Unitprice"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitprice" value="" id="unitprice" >
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo "Submit"; ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>


<div class="import_projects">
<div class="page-header-title"> <span class="glyphicon glyphicon-import"></span> Import an Excel file</div>
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
				<?php echo form_open(site_url("admin/upload_unitprice"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_xlsx", "method" => "post")) ?>
					<input type="file" name="uploadUnitprice" id="file" class="import_file" value="" />
					<button type="submit" id="enseignes_import" name="uploadUnit" title="Import an Excel file" class="import_btn btn btn-primary button-loading">Import Excel</button>
					<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/unitprice_details.xlsx" title="An Example file">Example file</a>
				<?php echo form_close() ?>
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
    var table = $('#unitprice-table').DataTable({
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
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("admin/unitprice_page") ?>",
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
	$(document).on("click", "#enseignes_import", function () {
		$('.loader').show();
		return true;
	}); 
});
</script>
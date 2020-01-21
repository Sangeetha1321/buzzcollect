<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_820") ?></div>
    <div class="db-header-extra form-inline"> 
<div class="btn-group">
<div class="dropdown">
	<button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<?php echo lang("ctn_844") ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo site_url("tasks/" . $page) ?>"><?php echo lang("ctn_845") ?></a></li>
		<?php foreach($projects->result() as $r) : ?>
			<li><a href="<?php echo site_url("tasks/".$page. "/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
</div>

<div class="form-group has-feedback no-margin">
<div class="input-group">
	<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_354") ?>" id="form-search-input" />
	<div class="input-group-btn">
		<input type="hidden" id="search_type" value="0">
		<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
		</button>
		<ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
			<li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
			<li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
			<li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_823") ?></a></li>
		</ul>
	</div><!-- /btn-group -->
</div>
</div>
    <?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), 
      $this->user)) : ?>
      <a href="<?php echo site_url("tasks/add?projectid=". $projectid) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_821") ?></a>
    <?php endif; ?>
</div>
</div>

<div class="btn-group" role="group" aria-label="...">
  <a href="<?php echo site_url("tasks/".$page."/0/0") ?>" class="btn btn-default btn-sm"><?php echo lang("ctn_846") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/1") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_830") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/2") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_831") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/3") ?>" class="btn btn-success btn-sm"><?php echo lang("ctn_832") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/4") ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_833") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/5") ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_834") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/6") ?>" class="btn btn-success btn-sm"><?php echo lang("ctn_1633") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/7") ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_1634") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/8") ?>" class="btn btn-success btn-sm"><?php echo lang("ctn_1635") ?></a>
</div>

<hr>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<div class="table-responsive">
<table id="tasks-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
<td><?php echo "Jobid"; ?></td>
<td><?php echo lang("ctn_847") ?></td>
<td><?php echo lang("ctn_848") ?></td>
<td><?php echo lang("ctn_825") ?></td>
<td><?php echo lang("ctn_849") ?></td>
<td><?php echo lang("ctn_827") ?></td>
<td><?php echo lang("ctn_828") ?></td>
<td><?php echo "Team"; ?></td>
<td><?php echo "User(s)"; ?></td>
<td><?php echo lang("ctn_52") ?></td>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>

<div class="import_projects">
<div class="page-header-title"> <span class="glyphicon glyphicon-import"></span> Import/Export a file</div>
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
<?php echo form_open(site_url("tasks/index/"), array("class" => "form-horizontal","id" => "project_statuses","name" => "project_statuses")) ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">	
			<label for="teamgroup-in" class="col-md-2 label-heading"><?php echo "Select Project"; ?></label>
			<div class="col-md-10">
			<select name="project_lr" id="project_lr" required class="form-control">
			<option value="">Select any project</option>
					<?php if(isset($project_list)){ foreach($project_list as $pro) { ?>
						<option value="<?php echo $pro->ID; ?>" <?php if ($project_lr == $pro->ID) echo 'selected="selected"'; ?> >
							<?php echo $pro->name; ?>
						</option>	
					<?php }} ?>	 
			</select>
			<input style="display:none;" type="submit" class="btn btn-primary" value="<?php echo "Submit"; ?>">
			</div>	
		</div>	
	<div class="form-group">
		<label for="start_date" class="col-md-2 label-heading"><?php echo "Start Date"; ?></label>
		<div class="col-md-10">
			<input name="filter_start_date" type="text" id="filter_start_date" class="form-control datepicker" value="<?php if(isset($filter_start_date)&&($filter_start_date!='')){echo $filter_start_date;} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="end_date" class="col-md-2 label-heading"><?php echo "End Date"; ?></label>
		<div class="col-md-10">
			<input name="filter_end_date" type="text" id="filter_end_date" class="form-control datepicker" value="<?php if(isset($filter_end_date)&&($filter_end_date!='')){echo $filter_end_date;} ?>">
		</div>
	</div>	
</div>
<?php echo form_close() ?>

				<div align="center">
					<?php /* echo form_open(site_url("tasks/import"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post", "id" => "upload_csv"))  */?>    
					<?php echo form_open(site_url("tasks/upload_title"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post", "id" => "upload_csv")) ?>    
						<input type="file" name="file" id="title_file" class="import_file">
						<button type="submit" id="tasks_import" name="import" title="Import Csv" class="import_btn btn btn-primary button-loading">Import an Excel file</button>
						<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/title_list.xlsx" title="Example file">Example file</a>
						<a id="export_csv" class="btn btn-primary" href="<?php echo site_url('tasks/export')?>" title="Export">Export</a>
						<br><br>
					<?php echo form_close() ?>
				</div>
			<div class="loader"></div>
			<style type="text/css">
			${demo.css}
			</style>
			</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#tasks-table').DataTable({
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
          [4, "asc" ]
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
		null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("tasks/tasks_page/" . $page . "/" . $projectid . "/" . $u_status) ?>",
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
function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "title-exact",
      ];
      set_search_icon(options[search], options);
        $('#search_type').val(search);
        $( "#form-search-input" ).trigger( "change" );
    }

function set_search_icon(icon, options) 
    {
      for(var i = 0; i<options.length;i++) {
        if(options[i] == icon) {
          $('#' + icon).fadeIn(10);
        } else {
          $('#' + options[i]).fadeOut(10);
        }
      }
    }
</script>
<script type="text/javascript">
$(document).ready(function () {
	$(window).load(function() { 
		$('.loader').hide();
	});
	$(document).on("click", "#tasks_import", function () {
		$('.loader').show();
		return true;
	}); 
	$(function() {
		$('#project_lr').change(function() {
			this.form.submit();
			//$('form input[type="file"]').prop("disabled", false);
		});
	});
	
	
	$("#filter_start_date").datepicker({
		numberOfMonths: 2,
		dateFormat: 'yy-mm-dd',
		onSelect: function (selected) {
			var dt = new Date(selected);
			dt.setDate(dt.getDate() + 1);
			$("#filter_end_date").datepicker("option", "minDate", dt);
		}
	});
	$("#filter_end_date").datepicker({
		numberOfMonths: 2,
		dateFormat: 'yy-mm-dd',
		onSelect: function (selected) {
			var dt = new Date(selected);
			dt.setDate(dt.getDate() - 1);
			$("#filter_start_date").datepicker("option", "maxDate", dt);
			$("#project_statuses").submit();
		}
	});
});
</script>
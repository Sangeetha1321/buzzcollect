<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
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

    <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_73") ?>" data-toggle="modal" data-target="#memberModal" />
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_74") ?></li>
</ol>

<p><?php echo lang("ctn_75") ?></p>


<div class="table-responsive">
<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/exportallmembers')?>" title="Export Csv">
	Export All Entries
</a>
<table id="member-table" class="table table-striped table-hover table-bordered">
<thead>
<tr class="table-header">
<td><?php echo "Employee Id"; ?></td>
<td><?php echo lang("ctn_191") ?></td>
<td><?php echo lang("ctn_1572") ?></td>
<td><?php echo "Team Name"; ?></td>
<td><?php echo lang("ctn_361") ?></td>
<td><?php echo lang("ctn_322") ?></td>
<td><?php echo lang("ctn_1579") ?></td>
<td><?php echo lang("ctn_1576") ?></td>
<td><?php echo lang("ctn_52") ?></td>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>


<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_73") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_member_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="employee-id" class="col-md-3 label-heading"><?php echo lang("ctn_1571") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control required digits" id="employee-id" name="employee_id" maxlength="6">
                    </div>
            </div>
			<div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_78") ?></label>
                    <div class="col-md-9">
                        <input type="email" class="form-control" id="email-in" name="email">
                    </div>
            </div>
            <div class="form-group">

                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_77") ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="username" name="username">
                            <div id="username_check"></div>
                        </div>
            </div>
            <div class="form-group">

                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_87") ?></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" id="password-in" name="password" value="">
                        </div>
                </div>

                <div class="form-group">

                        <label for="cpassword-in" class="col-md-3 label-heading"><?php echo lang("ctn_88") ?></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" id="cpassword-in" name="password2" value="">
                        </div>
                </div>

                <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_79") ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="name-in" name="first_name">
                        </div>
                </div>
                <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_80") ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="name-in" name="last_name">
                        </div>
                </div>
				<div class="form-group">
					<label for="designation" class="col-md-3 label-heading"><?php echo lang("ctn_1572") ?></label>
					<div class="col-md-9">
					<input type="text" class="form-control required" id="designation" name="designation" maxlength="128">
					</div>
				</div> 
				<!--
				<div class="form-group">
					<label for="department" class="col-md-3 label-heading"><?php echo lang("ctn_1573") ?></label>
					<div class="col-md-9">
					<input type="text" class="form-control required" id="department" name="department" maxlength="128">
					</div>
				</div> 
				-->
				<div class="form-group">
					<label for="category" class="col-md-3 label-heading"><?php echo lang("ctn_1574") ?></label>
					<div class="col-md-9">
					<input type="text" class="form-control required" id="category" name="category" maxlength="128">
					</div>
				</div> 
				<div class="form-group">
					<label for="doj" class="col-md-3 label-heading"><?php echo lang("ctn_1575") ?></label>
					<div class="col-md-9">
						<input name="doj" class="form-control datepicker" id="doj" type="text">
					</div>
				</div>	
				<div class="form-group">
					<label for="reporting" class="col-md-3 label-heading"><?php echo lang("ctn_1576") ?></label>
					<div class="col-md-9">
						<input type="text" class="form-control required" id="reporting" name="reporting" maxlength="128">
					</div>
				</div> 
				<div class="form-group">
					<label for="team_name" class="col-md-3 label-heading"><?php echo lang("ctn_1626") ?></label>
					<div class="col-md-9">
							<select name="team_name" class="form-control">
							<?php foreach($team_name->result() as $r) : ?>
								<option value="<?php echo $r->team_name ?>"><?php echo $r->team_name ?></option>
							<?php endforeach; ?>
							</select>
					</div>
				</div>
				<div class="form-group">
					<label for="gender" class="col-md-3 label-heading"><?php echo lang("ctn_1577") ?></label>
					<div class="col-md-9 radio">
						<label>
							<input name="gender" id="male" value="male" type="radio">
							Male
						</label>
						<label>
							<input name="gender" id="female" value="female" type="radio">
							Female
						</label>
					</div>				
				</div>  
				<div class="form-group">
					<label for="mobile" class="col-md-3 label-heading"><?php echo lang("ctn_1578") ?></label>
					<div class="col-md-9">
					<input type="text" class="form-control required digits" id="mobile" name="mobile" maxlength="10">
					</div>
				</div>	
				<div class="form-group">
					<label for="bloodGroup" class="col-md-3 label-heading"><?php echo lang("ctn_1579") ?></label>
					<div class="col-md-9">
					<!-- <input type="text" class="form-control required" name="bloodGroup" maxlength="128">
					-->
					<select name="bloodGroup" class="form-control">
						<option value="<?php echo "A+"; ?>">
						<?php echo "A+"; ?>
						</option>
						<option value="<?php echo "O+"; ?>">
						<?php echo "O+"; ?>
						</option>
						<option value="<?php echo "B+"; ?>">
						<?php echo "B+"; ?>
						</option>
						<option value="<?php echo "AB+"; ?>">
						<?php echo "AB+"; ?>
						</option>
						<option value="<?php echo "A-"; ?>">
						<?php echo "A-"; ?>
						</option>
						<option value="<?php echo "O-"; ?>">
						<?php echo "O-"; ?>
						</option>
						<option value="<?php echo "B-"; ?>">
						<?php echo "B-"; ?>
						</option>
						<option value="<?php echo "AB-"; ?>">
						<?php echo "AB-"; ?>
						</option>
					</select>
					</div>
				</div>  
				<div class="form-group">
					<label for="skillSet" class="col-md-3 label-heading"><?php echo lang("ctn_1580") ?></label>
					<div class="col-md-9">
					<!-- <input type="text" class="form-control required" name="skillSet" maxlength="128"> -->
					<select name="skillSet[]" multiple="multiple" class="form-control">
                        <?php foreach($member_skills->result() as $r) : ?>
                        	<option value="<?php echo $r->skills ?>"><?php echo $r->skills ?></option>
                        <?php endforeach; ?>
                    </select>
					</div>
				</div> 
				<div class="form-group">
					<label for="education" class="col-md-3 label-heading"><?php echo lang("ctn_1598") ?></label>
					<div class="col-md-9">
					<input type="text" class="form-control required" name="education" maxlength="128">
					</div>
				</div> 
				<div class="form-group">
                    <label for="status_catid" class="col-md-3 label-heading"><?php echo lang("ctn_776") ?></label>
                    <div class="col-md-9">
                        <select name="status_catid" class="form-control">
                        <?php foreach($member_categories->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
				</div>
<!-- <p class="panel-subheading"><?php echo lang("ctn_1116") ?></p> -->
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_429") ?></label>
	    <div class="col-md-9">
	      <input type="text" name="address_1" class="form-control">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_430") ?></label>
	    <div class="col-md-9">
	      <input type="text" name="address_2" class="form-control">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_431") ?></label>
	    <div class="col-md-9">
	      <input type="text" name="city" class="form-control" >
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_432") ?></label>
	    <div class="col-md-9">
	      <input type="text" name="state" class="form-control" >
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_433") ?></label>
	    <div class="col-md-9">
	      <input type="text" name="zipcode" class="form-control" >
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_434") ?></label>
	    <div class="col-md-9">
	      <input type="text" name="country" class="form-control" >
	    </div>
	</div>
                <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_322") ?></label>
                        <div class="col-md-9">
                            <select name="user_role" class="form-control" id="user_role">
                            <option value="0" selected><?php echo lang("ctn_471") ?></option>
                            <?php foreach($user_roles->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_61") ?>" />
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
					<!--
					<?php echo form_open(site_url("admin/import"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post")) ?>    
						<input type="file" name="file" id="file" class="import_file">
						<button type="submit" id="enseignes_import" name="import" title="Import Csv" class="import_btn btn btn-primary button-loading">Import Csv</button>
					<?php echo form_close() ?>
					-->	
				
				<?php echo form_open(site_url("admin/upload"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_xlsx", "method" => "post")) ?>
					<input type="file" name="uploadFile" id="file" class="import_file" value="" />
					<button type="submit" id="enseignes_import" name="uploadData" title="Import an Excel file" class="import_btn btn btn-primary button-loading">Import Excel</button>
					<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/user_list.xlsx" title="An Example file">Example file</a>
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
    var table = $('#member-table').DataTable({
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
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("admin/members_page") ?>",
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
        "user-exact",
        "fn-exact",
        "ln-exact",
        "role-exact",
        "email-exact"
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
	$(document).on("click", "#enseignes_import", function () {
		$('.loader').show();
		return true;
	}); 
});
</script>
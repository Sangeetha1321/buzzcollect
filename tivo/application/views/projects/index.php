<style>
table#projects-table{text-transform:uppercase;}
table#projects-table tr td:last-child {
    width: 75px !important;
} 
table#projects-table tr td {
    width: 50px !important;
}
table#cfg {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: auto;
  border: 1px solid #dddddd;
}
#projects-table tr.table-header td {
    padding: 3px 1px;
}
#projects-table tr.table-header td:nth-child(4) {
    width: 304px !important;
}
#projects-table tr.table-header td:nth-child(7) {
    width: 114px !important;
}
/* table#projects-table tr td:nth-child(7) {
    width: 114px !important;
} */
table#cfg td
{
  text-align: left;
  padding: 8px;
  border: 1px solid #dddddd;
}
table#cfg th
{
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
table#cfg tr:nth-child(even) {
 /*  background-color: #dddddd; */
}
.bootbox .modal-dialog {
    width: 70% !important;  
}
.upcfg{
	cursor: pointer;
}
.dt-buttons {
    display: none;
}
</style>
<div class="white-area-content">
<div class="db-header clearfix">
	<div class="page-header-title" style="margin-right:3px;"> 
		<span class="glyphicon glyphicon-filter" style="margin-right:4px;"></span> 
		<?php /* echo lang("ctn_691"); */ ?>
	</div> 
	<a href="<?php echo site_url("projects/".$page."/0") ?>" class="btn btn-round btn-sm"><?php echo lang("ctn_786") ?></a> 
	<?php foreach($categories->result() as $r) : ?>
	<a href="<?php echo site_url("projects/".$page."/" . $r->ID) ?>" class="btn btn-round btn-sm" style="border-color: #<?php echo $r->color ?>; color: #<?php echo $r->color ?>"><?php echo $r->name ?><span class="badge" style="background: #<?php echo $r->color ?>;"><?php echo $r->status_count; ?></span></a> 
	<?php endforeach; ?>
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
		<li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_767") ?></a></li>
		</ul>
		</div><!-- /btn-group -->
		</div>
		</div>
		<?php if($this->common->has_permissions(array("admin", "project_admin", "project_worker"), $this->user)) : ?><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_785") ?></button><?php endif; ?>
	</div>
</div>


<div class="table-responsive">
<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('projects/exportallprojects/'.$page)?>" title="Export Csv">
	Export CSV
</a>
<table id="projects-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
	<td><?php echo "Source Name"; ?></td>
	<td><?php echo "Transition Status"; ?></td>
	<td><?php echo "File Format"; ?></td>
	<td><?php echo lang("ctn_703") ?></td>
	<td><?php echo "Channel Config"; ?></td>
	<td><?php echo "Reporter"; ?></td>
	<td><?php echo "Created Date"; ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>

<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

</div>

<?php if($this->common->has_permissions(array("admin", "project_admin", "project_worker"), $this->user)) : ?>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_785") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("projects/add_project"), array("class" => "form-horizontal")) ?>
            <!-- <div id="clickme" class="btn btn-primary btn-sm">TIVO</div>
			<div id="showbydefault">
				<input type="hidden" name="tivo" value="0" id="tivo"/>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_767") ?></label>
						<div class="col-md-8 ui-front">
							<input type="text" class="form-control" name="name" value="" id="project_name">
						</div>
				</div>
			
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_768") ?></label>
						<div class="col-md-8 ui-front">
							<input type="file" class="form-control" name="userfile">
							<span class="help-block"><?php echo lang("ctn_769") ?></span>
						</div>
				</div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_770") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="project-description"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_771") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="complete" class="form-control" >
                        <span class="help-block"><?php echo lang("ctn_772") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_773") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="complete_sync" value="1" checked >
                        <span class="help-block"><?php echo lang("ctn_774") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_775") ?></label>
                    <div class="col-md-8">
                        <select name="catid" class="form-control">
                        <?php foreach($categories->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <?php foreach($fields->result() as $r) : ?>
                <div class="form-group">
                        <label for="name-in" class="col-md-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                        <div class="col-md-8">
                            <?php if($r->type == 0) : ?>
                                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="">
                            <?php elseif($r->type == 1) : ?>
                                <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"></textarea>
                            <?php elseif($r->type == 2) : ?>
                                 <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <p><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 3) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <p><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 4) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                    <?php foreach($options as $k=>$v) : ?>
                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="help-text"><?php echo $r->help_text ?></span>
                        </div>
                </div>
                <?php endforeach; ?>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1581") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="customer" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1582") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="publisher" id="publisher" class="form-control required">
                        	<option value="">Select Publisher</option>
							<?php foreach($pro_publisher->result() as $r) : ?>
							<option value="<?php echo $r->publisher; ?>"><?php echo $r->publisher; ?></option>
							<?php endforeach; ?>
                        </select>
					</div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1586") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="acronym" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1587") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="process_name" id="process_name" class="form-control required">
                        	<option value="">Select Process name</option>
							<?php foreach($pro_process_name->result() as $r) : ?>
							<option value="<?php echo $r->process_name; ?>"><?php echo $r->process_name; ?></option>
							<?php endforeach; ?>
                        </select>
						<input readonly id="geofacets_process_name" type="text" class="form-control" name="geofacets_process_name" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Stage"; ?></label>
                    <div class="col-md-8 ui-front">
						<select name="stage" id="stage" class="form-control">
                        	<option value="">Select Stage</option>
							<?php foreach($pro_stage->result() as $r) : ?>
							<option value="<?php echo $r->stage; ?>"><?php echo $r->stage; ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "PDF Type"; ?></label>
                    <div class="col-md-8 ui-front">
						<select name="pdfType" id="pdfType" class="form-control">
                        	<option value="">Select PDF Type</option>
							<?php foreach($pro_pdf_type->result() as $r) : ?>
							<option value="<?php echo $r->pdfType; ?>"><?php echo $r->pdfType; ?></option>
							<?php endforeach; ?>
                        </select>
                    	<input readonly id="geofacets_pdfType" type="text" class="form-control" name="geofacets_pdfType" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1588") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="pm" value="">
                    </div>
            </div>
			
			</div>
			<div id="shownotbydefault" style="display:none;">  -->
				<input type="hidden" name="tivo" value="1" id="tivo"/>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Source ID"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input required type="number" class="form-control" name="customer" value="" id="source_id">
                    </div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Source Name"; ?></label>
					<div class="col-md-8 ui-front">
						<input required type="text" class="form-control" name="name" value="" id="project_name">
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_768") ?></label>
					<div class="col-md-8 ui-front">
						<input type="file" class="form-control" name="userfile">
						<span class="help-block"><?php echo lang("ctn_769") ?></span>
					</div>
				</div> -->
				<!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Long Name"; ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="project-description"></textarea>
                    </div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_771") ?></label>
					<div class="col-md-8">
						<input type="text" name="complete" class="form-control" >
						<span class="help-block"><?php echo lang("ctn_772") ?></span>
					</div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_773") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="complete_sync" value="1" checked >
                        <span class="help-block"><?php echo lang("ctn_774") ?></span>
                    </div>
				</div> -->
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Transition Status"; /* echo lang("ctn_775") */ ?></label>
                    <div class="col-md-8">
                        <select name="catid" class="form-control">
                        <?php foreach($categories->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
				</div>
            <?php foreach($fields->result() as $r) : ?>
                <div class="form-group">
                        <label for="name-in" class="col-md-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                        <div class="col-md-8">
                            <?php if($r->type == 0) : ?>
                                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="">
                            <?php elseif($r->type == 1) : ?>
                                <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"></textarea>
                            <?php elseif($r->type == 2) : ?>
                                 <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <p><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 3) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <p><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 4) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                    <?php foreach($options as $k=>$v) : ?>
                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="help-text"><?php echo $r->help_text ?></span>
                        </div>
                </div>
                <?php endforeach; ?>
				<!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Frequency"; ?></label>
                    <div class="col-md-8 ui-front">
                       <select name="frequency" id="publisher" class="form-control required">
                        	<option value="">Select Frequency</option>
							<option value="daily">Daily</option>
							<option value="monthly">Monthly</option>
							<option value="weekly">Weekly</option>
							<option value="bi-weekly">Bi-Weekly</option>
                        </select>
					</div>
				</div> -->
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "File Format"; ?></label>
                    <div class="col-md-8 ui-front">
                        <select required name="process_name" id="process_name" class="form-control required">
                        	<option value="">Select File Format</option>
							<option value="xls">.xls</option>
							<option value="xlsx">.xlsx</option>
							<option value="doc">.doc</option>
							<option value="docx">.docx</option>
							<option value="csv">.csv</option>
							<option value="xml">.xml</option>
							<option value="html">.html</option>
							<option value="pdf">.pdf</option>
							<option value="txt">.txt</option>
							<!-- <option value="pdf_or_txt">.pdf or .txt</option>
							<option value="excel_and_xml">.xls and .xml</option> -->
                        </select>
					</div>
				</div>
				<div class="form-group">
					<label for="team_name" class="col-md-4 label-heading"><?php echo lang("ctn_1626") ?></label>
					<div class="col-md-8 ui-front">
							<select multiple="multiple" name="team_name[]" id="teams" class="form-control required">
							<option value="">Select Team Name</option>
							<?php foreach($team_name->result() as $r) : ?>
								<option value="<?php echo $r->team_name ?>"><?php echo $r->team_name ?></option>
							<?php endforeach; ?>
							</select>
					</div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Reporter"; ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="user_name" id="users" class="form-control">
                        <option value="">Select Reporter</option>
                        </select>
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Broadcast Day Start Time"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input required type="time" class="form-control" name="broadcast_day_st_time" value="">
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Channel Config"; /* echo lang("ctn_465") */ ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="file" class="form-control" name="userfile">
                    </div>
				</div>
				<!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Reporter"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="pm" value="">
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Country"; ?></label>
                    <div class="col-md-8 ui-front">
                       <select name="country" id="country" class="form-control required">
                        	<option value="">Select Country</option>
							<option value="Italy">Italy</option>
							<option value="Portugal">Portugal</option>
							<option value="UK">UK</option>
                        </select>
					</div>
				</div> -->
			<!-- </div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input id="add_new_project" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_785") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="modal fade" id="addProModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo " Channel Config"; ?></h4>
      </div>
      <div class="modal-body"> 
		<?php
			if($projects){
				foreach($projects->result() as $r) {
					echo $r->image;
				} 
			}
		?>	
<div id="upimage"></div>		
<div id="upid"></div>		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
       <!-- <input id="add_new_project" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_785") ?>"> -->
        <?php /* echo form_close() */ ?>
      </div>
    </div>
  </div>
</div>

<!--
<div class="import_projects">
	<div class="page-header-title"> <span class="glyphicon glyphicon-import"></span> Import a CSV file</div>
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
			<?php echo form_open(site_url("projects/import"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post")) ?>    
				<input type="file" name="file" id="file" class="import_file">
				<button type="submit" id="projects_import" name="import" title="Import Csv" class="import_btn btn btn-primary button-loading">Import Csv</button> 
				<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/project_list.csv" title="Example of csv file">Example of csv file</a>
				<br><br>
		</div>
	<div class="loader"></div>
	<style type="text/css">
		${demo.css}
	</style>
	</div>
</div>
-->
<div class="import_projects">
<!-- <div class="page-header-title"> <span class="glyphicon glyphicon-import"></span> Import an Excel file</div> -->
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
			<!-- <div class="admin_row equal_height">
				<br><br>
				<div align="center">
				<?php echo form_open(site_url("projects/upload_projects"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_xlsx", "method" => "post")) ?>
					<input type="file" name="uploadprojects" id="file" class="import_file" value="" />
					<button type="submit" id="projects_import" name="uploadpro" title="Import an Excel file" class="import_btn btn btn-primary button-loading">Import Excel</button>
					<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/project_list.xlsx" title="An Example file">Example file</a>
				<?php echo form_close() ?>
				<br><br>
				</div>
			<div class="loader"></div>
			<style type="text/css">
			${demo.css}
			</style>
			</div> -->
			<div class="admin_row equal_height">
				<div style="font-size: 24px;"> <span class="glyphicon glyphicon-import"></span> Import Source Onboarding</div>
				<div align="center">
					<?php echo form_open(site_url("projects/import_tivo_projects"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_csv", "method" => "post")) ?>    
					<!-- <select required name="tivo_country" id="tivo_country">
					<option value="">Select any Country</option>
					<option value="Italy">Italy</option>
					<option value="Portugal">Portugal</option>
					<option value="UK">UK</option>
					</select> --> 
					<input type="file" name="file" id="file" class="import_file">
					<button type="submit" id="projects_tivo_import" name="import_tivo_projects" title="Import a CSV file" class="import_btn btn btn-primary button-loading">Import CSV</button> 
					<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/tivo_source_list.csv" title="An Example file">Example file</a>
					<br><br>
					<?php echo form_close() ?>
				</div>
			<div class="loader"></div>
			<style type="text/css">
				${demo.css}
			</style>
			</div>
			<div class="admin_row equal_height">
				<div style="font-size: 24px;"> <span class="glyphicon glyphicon-import"></span> Import Source Database</div>
				<div align="center">
					<?php echo form_open(site_url("projects/import_source_auto_populate"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "autopopulate_csv", "method" => "post")) ?>    
					<input type="file" name="file" id="file" class="import_file">
					<button type="submit" id="import_auto_populate" name="import_auto_populate" title="Import a CSV file" class="import_btn btn btn-primary button-loading">Import CSV</button> 
					<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/tivo_source_autopopulate.csv" title="An Example file">Example file</a>
					<br><br>
					<?php echo form_close() ?>
				</div>
			<div class="loader"></div>
			<style type="text/css">
				${demo.css}
			</style>
			</div>
			<!-- <div class="admin_row equal_height">
			<div style="font-size: 24px;"> <span class="glyphicon glyphicon-import"></span> Import an Excel file for Tivo</div>
			<br><br>
				<div align="center">
				<?php echo form_open(site_url("projects/upload_tivo_projects"), array("class" => "form-horizontal","enctype" =>"multipart/form-data", "name" => "upload_tivo_xlsx", "method" => "post")) ?>
					<input type="file" name="uploadtivoprojects" id="file" class="import_file" value="" />
					<button type="submit" id="projects_tivo_import" name="uploadtivopro" title="Tivo Import an Excel file" class="import_btn btn btn-primary button-loading">Tivo Import</button>
					<a id="sample_csv" class="btn btn-primary" href="<?php echo base_url(); ?>sample_csv/tivo_project_list.xlsx" title="An Example file">Example file</a>
				<?php echo form_close() ?>
				<br><br>
				</div>
			<div class="loader"></div>
			<style type="text/css">
			${demo.css}
			</style>
			</div> -->
</div>

<link href="<?php echo base_url() ?>scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url() ?>scripts/libraries/chosen/chosen.jquery.min.js"></script>
<?php if($this->common->has_permissions(array("admin", "project_admin", "project_worker"), $this->user)) : ?>
<script type="text/javascript">
$(document).ready(function() {
//CKEDITOR.replace('project-description', { height: '100'});
	$("#project_name").focus(function() {
		console.log('in');
	}).blur(function() {
		console.log('out');
		var source_name = $("#project_name").val();
		if(source_name) { 
			var hitURL = "<?php echo base_url(); ?>projects/ajaxcall";
			var csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
			var csrf_hash = "<?php $this->security->get_csrf_hash() ?>";
			$.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { sourcename : source_name }
			}).done(function(data){ 
				console.log(data);
				for(var i=0;i<data.length;i++)
				{
					$('#source_id').val(data[i].source_id);
					$('#teams').val(data[i].scheduling_team);
					$('#long_name').val(data[i].long_name);
				}
			});	
		}
	});
});
</script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url() ?>scripts/custom/bootbox.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {   
$('#addModal').on('shown.bs.modal', function () {
  $(".chosen-select-no-single").chosen({
    disable_search_threshold:10
});
});
    

    var st = $('#search_type').val();

    var table = $('#projects-table').DataTable({
        "dom" : "B<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 50,
        "serverSide": true,
        "orderMulti": false,
         buttons: [
          { "extend": 'copy', "text":'<?php echo lang("ctn_1551") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'csv', "text":'<?php echo lang("ctn_1552") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'excel', "text":'<?php echo lang("ctn_1553") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'pdf', "text":'<?php echo lang("ctn_1554") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'print', "text":'<?php echo lang("ctn_1555") ?>',"className": 'btn btn-default btn-sm' }
        ],
        "order" : [
          [6, "desc" ]
        ],
        "columns": [
        null,
        null,
        { "orderable": false },
        null,
		{ "orderable": false },
		null, 
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("projects/projects_page/" . $page . "/" . $catid) ?>",
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
	$(document).on("click", "#projects_import", function () {
		$('.loader').show();
		return true;
	});  
	$(document).on("click", "#projects_tivo_import", function () {
		/* $('.loader').show();
		return true; */
		/* $("#tivo_country").on('change', function() {
		if ($(this).val() != ''){
			$('.loader').show();
			return true;
		} else {
			
		}
		});	 */
	}); 
	//$('#project_name').focusout(function() {
	
	$(document).on("click",".upcfg",function(){  
    	$.ajax({
	        url : "<?php echo site_url("projects/check_upcfg") ?>",
	        type : 'GET',
	        data : {
	          cfgfile : $(".upcfg").text(), 
	          cfgat : $(".upcfg").attr('at'),
	          rid : this.id,
	        },
	        dataType: 'JSON',
	        success: function(data) {
				console.log(data);  
				bootbox.alert(data);
	        },
			error : function(data) {
				console.log('error');
				/* alert($(".upcfg").text()); 
				alert(console.log('error')); */ 
			}
	    });
    });
	
	$(document).on("keyup","#project_name",function(){
    	$.ajax({
	        url : "<?php echo site_url("projects/check_project_name") ?>",
	        type : 'GET',
	        data : {
	          projectname : $(this).val(),
	        },
	        dataType: 'JSON',
	        success: function(data) {
	        	if(data.success) {
	        		$("#project_name").css("border-color", "green");
	        		$("#project_name").css("box-shadow", "inset 0 1px 1px green");
					$('#add_new_project').prop('disabled', false);
	        	} else {
	        		$("#project_name").css("border-color", "red");
					$("#project_name").css("box-shadow", "inset 0 1px 1px red");
					$('#add_new_project').prop('disabled', true);
	        		if(data.field_errors) {
			            var errors = data.fieldErrors;
			            for (var property in errors) {
			                if (errors.hasOwnProperty(property)) {
			                    // Find form name
			                    var field_name = '#' + form + ' input[name="'+property+'"]';
			                    $(field_name).addClass("errorField");
			                    if(errors[property]) {
				                    // Get input group of field
				                    $(field_name).parent().closest('.form-group').after('<div class="form-error-no-margin">'+errors[property]+'</div>');
				                }
			                    

			                }
			            }
			          }
	        	}
	        }
	    });
    }); 
	
	$("#process_name").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue=='Geofacets Table conversion'){
                $("#geofacets_process_name").show();
				$("#geofacets_process_name").val('Geofacets Figure conversion');
				
                $("#geofacets_pdfType").show();
				$("#pdfType").val('Editable').trigger('change');
				$("#geofacets_pdfType").val('Scanned');
				

            } 
			else if(optionValue=='Geofacets Figure conversion'){
				$("#geofacets_process_name").show();
				$("#geofacets_process_name").val('Geofacets Table conversion');
				
				$("#geofacets_pdfType").show();
				$("#pdfType").val('Editable').trigger('change');
				$("#geofacets_pdfType").val('Scanned');
			}
			else{
				$("#geofacets_process_name").hide();
				$("#geofacets_process_name").val('');
				
				$("#geofacets_pdfType").hide();
				$("#geofacets_pdfType").val('');
            }
        });
    }).change();
	
	$("#pdfType").change(function(){
        $(this).find("option:selected").each(function(){
			var proopt = $("#process_name option:selected").val();
			if((proopt == 'Geofacets Table conversion') || (proopt == 'Geofacets Figure conversion')){
				var optionValue = $(this).attr("value");
				if(optionValue=='Editable'){
					$("#geofacets_pdfType").show();
					$("#geofacets_pdfType").val('Scanned');
				} 
				else if(optionValue=='Scanned'){
					$("#geofacets_pdfType").show();
					$("#geofacets_pdfType").val('Editable');
				}
				else{
					$("#geofacets_pdfType").hide();
					$("#geofacets_pdfType").val('');
				}
			}
        });
    });
	/* $("#clickme").on('click', function() {
	 //  $(this).hide();
	   $("#showbydefault").toggle();
	   $("#shownotbydefault").toggle();
	});	 */
});
</script>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(document).on("change", "select#teams", function(){
			var team_name = $(this).val(); 
			var hitURL = "<?php echo base_url(); ?>team/ajaxcall";
			var csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
			var csrf_hash = "<?php $this->security->get_csrf_hash() ?>";
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { teamname : team_name }
			}).done(function(data){ 
				$('select#users').html('');
                for(var i=0;i<data.length;i++)
                {
                    $("<option />").val(data[i].username)
                                   .text(data[i].username)
                                   .appendTo($('select#users'));
                }
			});		
	});	
});	
</script>	
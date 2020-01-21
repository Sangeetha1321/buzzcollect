<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('project-description', { height: '100'});
});
</script>
<div class="white-area-content">
<!--
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_766") ?></div>
    <div class="db-header-extra">
</div>
</div> -->

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("projects/edit_project_pro/" . $project->ID), array("class" => "form-horizontal")) ?>
<?php if($project->tivo == 1){ ?>
				<input type="hidden" name="tivo" value="1" id="tivo"/>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Source ID"; ?></label>
					<div class="col-md-8 ui-front">
						<input type="text" class="form-control" name="customer" value="<?php echo $project->customer ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Source Name"; ?></label>
					<div class="col-md-8 ui-front">
					<input id="project_edit_name" type="text" class="form-control" name="name" value="<?php echo $project->name ?>">
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_768") ?></label>
					<div class="col-md-8 ui-front">
					<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>" class="user-icon" />
					<input type="file" class="form-control" name="userfile">
					<span class="help-block"><?php echo lang("ctn_769") ?></span>
					</div>
				</div> -->
				<!-- <div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Long Name"; ?></label>
					<div class="col-md-8">
					<textarea name="description" id="project-description"><?php echo $project->description ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_771") ?></label>
					<div class="col-md-8">
						<input type="text" name="complete" class="form-control" value="<?php echo $project->complete ?>" >
						<span class="help-block"><?php echo lang("ctn_772") ?></span>
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_773") ?></label>
					<div class="col-md-8">
						<input type="checkbox" name="complete_sync" value="1" <?php if($project->complete_sync) : ?>checked<?php endif; ?> >
						<span class="help-block"><?php echo lang("ctn_774") ?></span>
					</div>
				</div> -->
				<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Transition Status"; /* echo lang("ctn_775") */ ?></label>
					<div class="col-md-8">
					<select name="catid" class="form-control">
					<?php foreach($categories->result() as $r) : ?>
					<option value="<?php echo $r->ID ?>" <?php if($r->ID == $project->catid) echo "selected" ?>><?php echo $r->name ?></option>
					<?php endforeach; ?>
					</select>
					</div>
				</div>
				<?php foreach($fields->result() as $r) : ?>
					<div class="form-group">
							<label for="name-in" class="col-sm-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
							<div class="col-sm-8">
								<?php if($r->type == 0) : ?>
									<input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
								<?php elseif($r->type == 1) : ?>
									<textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
								<?php elseif($r->type == 2) : ?>
									 <?php $options = explode(",", $r->options); ?>
									 <?php $values = array_map('trim', (explode(",", $r->value))); ?>
									<?php if(count($options) > 0) : ?>
										<?php foreach($options as $k=>$v) : ?>
										<p><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></p>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php elseif($r->type == 3) : ?>
									<?php $options = explode(",", $r->options); ?>
									
									<?php if(count($options) > 0) : ?>
										<?php foreach($options as $k=>$v) : ?>
										<p><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></p>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php elseif($r->type == 4) : ?>
									<?php $options = explode(",", $r->options); ?>
									<?php if(count($options) > 0) : ?>
										<select name="cf_<?php echo $r->ID ?>" class="form-control">
										<?php foreach($options as $k=>$v) : ?>
										<option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
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
                       <select name="frequency" id="frequency" class="form-control required">
                        	<option value="">Select Frequency</option>
							<option value="daily" <?php if($project->frequency == "daily"){ echo "selected";} ?>>Daily</option>
							<option value="monthly" <?php if($project->frequency == "monthly"){ echo "selected";} ?>>Monthly</option>
							<option value="weekly" <?php if($project->frequency == "weekly"){ echo "selected";} ?>>Weekly</option>
							<option value="bi-weekly" <?php if($project->frequency == "bi-weekly"){ echo "selected";} ?>>Bi-Weekly</option>
                        </select>
					</div>
				</div> -->
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "File Format"; ?></label>
					<div class="col-md-8 ui-front">
					<select name="process_name" id="process_name" class="form-control required">
						<option value="">Select File Format</option>
						<option value="xls" <?php if($project->process_name == "xls" || $project->process_name == "XLS"){ echo "selected";} ?>>.xls</option>
						<option value="xlsx" <?php if($project->process_name == "xlsx" || $project->process_name == "XLSX"){ echo "selected";} ?>>.xlsx</option>
						<option value="doc" <?php if($project->process_name == "doc" || $project->process_name == "DOC"){ echo "selected";} ?>>.doc</option>
						<option value="docx" <?php if($project->process_name == "docx" || $project->process_name == "DOCX"){ echo "selected";} ?>>.docx</option>
						<option value="csv" <?php if($project->process_name == "csv" || $project->process_name == "CSV"){ echo "selected";} ?>>.csv</option>
						<option value="xml" <?php if($project->process_name == "xml" || $project->process_name == "XML"){ echo "selected";} ?>>.xml</option>
						<option value="html" <?php if($project->process_name == "html" || $project->process_name == "HTML"){ echo "selected";} ?>>.html</option>
						<option value="pdf" <?php if($project->process_name == "pdf" || $project->process_name == "PDF"){ echo "selected";} ?>>.pdf</option>
						<option value="txt" <?php if($project->process_name == "txt" || $project->process_name == "TXT"){ echo "selected";} ?>>.txt</option>
						<!-- <option value="pdf_or_txt" <?php if($project->process_name == "pdf_or_txt"){ echo "selected";} ?>>.pdf or .txt</option>
						<option value="excel_and_xml" <?php if($project->process_name == "excel_and_xml"){ echo "selected";} ?>>.xls and .xml</option> -->
					</select>
					</div>
				</div>
				<div class="form-group">
					<label for="team_name" class="col-md-4 label-heading"><?php echo lang("ctn_1626") ?></label>
					<div class="col-md-8 ui-front">
							<select multiple="multiple" name="team_name[]" id="teams" class="form-control required">
							<option value="">Select Team Name</option>
							<?php foreach($team_name->result() as $r) : ?>
								<option value="<?php echo $r->team_name ?>" <?php if($project->team_name == $r->team_name){ echo "selected";} ?>><?php echo $r->team_name ?></option>
							<?php endforeach; ?>
							</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Reporter"; ?></label>
					<div class="col-md-8 ui-front">
					<input id="users" type="text" class="form-control" name="user_name" value="<?php echo $project->user_name ?>">
					</div>
				</div>
				<!--
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="user_name" id="users" class="form-control">
                        <option value="">Select User(s)</option>
                        </select>
                    </div>
				</div>
				-->
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Broadcast Day Start Time"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="time" class="form-control" name="broadcast_day_st_time" value="<?php echo $project->broadcast_day_start_time; ?>">
                    </div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Channel Config"; /* lang("ctn_479") */ ?></label>
					<div class="col-md-8 ui-front">
					<p><?php echo lang("ctn_480") ?>: <a href="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>"><?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?></a></p>
                    <input type="file" class="form-control" name="userfile">
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Reporter"; ?></label>
					<div class="col-md-8 ui-front">
						<input type="text" class="form-control" name="pm" value="<?php echo $project->pm ?>">
					</div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Country"; ?></label>
                    <div class="col-md-8 ui-front">
                       <select name="country" id="country" class="form-control required">
                        	<option value="">Select Country</option>
							<option value="Italy" <?php if($project->country == "Italy"){ echo "selected";} ?>>Italy</option>
							<option value="Portugal" <?php if($project->country == "Portugal"){ echo "selected";} ?>>Portugal</option>
							<option value="UK" <?php if($project->country == "UK"){ echo "selected";} ?>>UK</option>
                        </select>
					</div>
				</div> -->
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_776") ?></label>
					<div class="col-md-8">
						<select name="status" class="form-control">
							<option value="0"><?php echo lang("ctn_777") ?></option>
							<option value="1" <?php if($project->status == 1) echo "selected" ?>><?php echo "Inactive"; /* echo lang("ctn_778") */ ?></option>
						</select>
					</div>
				</div>
<?php } else { ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_767") ?></label>
        <div class="col-md-8 ui-front">
            <input id="project_edit_name" type="text" class="form-control" name="name" value="<?php echo $project->name ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_768") ?></label>
        <div class="col-md-8 ui-front">
        	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>" class="user-icon" />
            <input type="file" class="form-control" name="userfile">
            <span class="help-block"><?php echo lang("ctn_769") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_770") ?></label>
        <div class="col-md-8">
            <textarea name="description" id="project-description"><?php echo $project->description ?></textarea>
        </div>
</div>
<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_771") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="complete" class="form-control" value="<?php echo $project->complete ?>" >
                        <span class="help-block"><?php echo lang("ctn_772") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_773") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="complete_sync" value="1" <?php if($project->complete_sync) : ?>checked<?php endif; ?> >
                        <span class="help-block"><?php echo lang("ctn_774") ?></span>
                    </div>
            </div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_775") ?></label>
        <div class="col-md-8">
            <select name="catid" class="form-control">
            <?php foreach($categories->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $project->catid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
		<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1581") ?></label>
		<div class="col-md-8 ui-front">
			<input type="text" class="form-control" name="customer" value="<?php echo $project->customer ?>">
		</div>
</div>
<div class="form-group">
		<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1582") ?></label>
		<div class="col-md-8 ui-front">
		<!-- <input type="text" class="form-control required" name="publisher" value="<?php //echo $project->publisher ?>"> -->
		<select name="publisher" id="publisher" class="form-control required">
			<option value="">Select Publisher</option>
			<?php foreach($pro_publisher->result() as $r) : ?>
			<option value="<?php echo $r->publisher; ?>" <?php if($project->publisher == $r->publisher){ echo "selected";} ?>><?php echo $r->publisher; ?></option>
			<?php endforeach; ?>
		</select>
		</div>
</div>
<div class="form-group">
		<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1586") ?></label>
		<div class="col-md-8 ui-front">
			<input type="text" class="form-control" name="acronym" value="<?php echo $project->acronym ?>">
		</div>
</div>
<div class="form-group">
		<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1587") ?></label>
		<div class="col-md-8 ui-front">
		<!-- <input type="text" class="form-control" name="process_name" value="<?php //echo $project->process_name ?>"> -->
		<select name="process_name" id="process_name" class="form-control required">
			<option value="">Select Process name</option>
			<?php foreach($pro_process_name->result() as $r) : ?>
			<option value="<?php echo $r->process_name; ?>" <?php if($project->process_name == $r->process_name){ echo "selected";} ?>><?php echo $r->process_name; ?></option>
			<?php endforeach; ?>
		</select>
		<input readonly id="geofacets_process_name" type="text" class="form-control" name="geofacets_process_name" value="<?php echo $project->geofacets_process_name; ?>">
		</div>
</div>
<div class="form-group">
		<label for="p-in" class="col-md-4 label-heading"><?php echo "Stage"; ?></label>
		<div class="col-md-8 ui-front">
			<select name="stage" id="stage" class="form-control">
				<option value="">Select Stage</option>
				<?php foreach($pro_stage->result() as $r) : ?>
				<option value="<?php echo $r->stage; ?>" <?php if($project->stage == $r->stage){ echo "selected";} ?>><?php echo $r->stage; ?></option>
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
				<option value="<?php echo $r->pdfType; ?>" <?php if($project->pdfType == $r->pdfType){ echo "selected";} ?>><?php echo $r->pdfType; ?></option>
				<?php endforeach; ?>
			</select>
			<input readonly id="geofacets_pdfType" type="text" class="form-control" name="geofacets_pdfType" value="<?php echo $project->geofacets_pdfType; ?>">
        </div>
</div>
<div class="form-group">
		<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1588") ?></label>
		<div class="col-md-8 ui-front">
			<input type="text" class="form-control" name="pm" value="<?php echo $project->pm ?>">
		</div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_776") ?></label>
        <div class="col-md-8">
            <select name="status" class="form-control">
                <option value="0"><?php echo lang("ctn_777") ?></option>
                <option value="1" <?php if($project->status == 1) echo "selected" ?>><?php echo "Inactive"; /* lang("ctn_778") */ ?></option>
            </select>
        </div>
</div>
<!--	<h4><?php echo lang("ctn_779") ?></h4>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_780") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="calendar_id" value="<?php echo $project->calendar_id ?>">
                        <span class="help-block"><?php echo lang("ctn_781") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_782") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control jscolor" name="calendar_color" value="<?php echo $project->calendar_color ?>">
                    </div>
            </div> -->
            <?php foreach($fields->result() as $r) : ?>
            <div class="form-group">

                <label for="name-in" class="col-sm-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                <div class="col-sm-8">
                    <?php if($r->type == 0) : ?>
                        <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
                    <?php elseif($r->type == 1) : ?>
                        <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
                    <?php elseif($r->type == 2) : ?>
                         <?php $options = explode(",", $r->options); ?>
                         <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <p><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 3) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <p><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 4) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        <?php if(count($options) > 0) : ?>
                            <select name="cf_<?php echo $r->ID ?>" class="form-control">
                            <?php foreach($options as $k=>$v) : ?>
                            <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="help-text"><?php echo $r->help_text ?></span>
                </div>
        </div>
    <?php endforeach; ?>
	<?php } ?>
			
<input id="edit_project" type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_783") ?>">
<?php echo form_close() ?>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
</div>

<script type="text/javascript">
$(document).on("keyup","#project_edit_name",function(){
	$.ajax({
		url : "<?php echo site_url("projects/check_project_name") ?>",
		type : 'GET',
		data : {
		  projectname : $(this).val(),
		},
		dataType: 'JSON',
		success: function(data) {
			if(data.success) {
				$("#project_edit_name").css("border-color", "green");
				$("#project_edit_name").css("box-shadow", "inset 0 1px 1px green");
				$('#edit_project').prop('disabled', false);
			} else {
				$("#project_edit_name").css("border-color", "red");
				$("#project_edit_name").css("box-shadow", "inset 0 1px 1px red");
				$('#edit_project').prop('disabled', true);
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
$(document).ready(function () {
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
		var proVal = $("#process_name option:selected").attr("value");
		if((proVal=='Geofacets Table conversion') || (proVal=='Geofacets Figure conversion')){
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
    }).change();	
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
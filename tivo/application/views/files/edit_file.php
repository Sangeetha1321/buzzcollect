<script type="text/javascript">
$(document).ready(function() {
	$('#project-select').change(function() {
		var projectid = $('#project-select').val();
		$.ajax({
			url: global_base_url + 'files/get_folders_for_project/',
			type: 'GET',
			data: {
				projectid : projectid
			},
			success: function(msg) {
				$('#folder-area').html(msg);
			}
		});
	});
});
</script>
<div class="white-area-content">
<!--
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_463") ?></div>
    <div class="db-header-extra"> 
</div>
</div> -->

<div class="panel panel-default">
<div class="panel-body">

 <?php echo form_open_multipart(site_url("files/edit_file_process/" . $file->ID . "/" . $all), array("class" => "form-horizontal","autocomplete" => "off")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_469") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control" id="project-select">
                        <option value="-1"><?php echo lang("ctn_470") ?></option>
                        <option value="0"><?php echo lang("ctn_471") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $file->projectid) echo "selected" ?>><?php echo $r->name ?></option>                       	
						<?php endforeach; ?>
                        </select>
						<input type="hidden" name="project_name" value="<?php echo $file->project_name ?>">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "File Format"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="format_used" value="<?php echo $file->format_used ?>" id="format_used">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Broadcast Day Start Time"; ?></label>
                    <div class="col-md-8">
                        <input type="time" class="form-control" name="broadcast_day_st_time" value="<?php echo $file->broadcast_day_st_time ?>" id="broadcast_day_st_time">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_479") ?></label>
                    <div class="col-md-8">
						<?php 
							$bseurl = 'http://' . $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.'uploadfiles/Input'.'/'.$file->upload_file_name; 
						?>
                        <!-- <p><?php echo lang("ctn_480") ?>: <a href="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $file->upload_file_name ?>"><?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $file->upload_file_name ?></a></p> -->
                        <p><?php echo lang("ctn_480") ?>: <a href="<?php echo $bseurl; ?>"><?php echo $bseurl; ?></a></p>
                        <input type="file" class="form-control" name="userfile" id="uploadfile">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_464") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name" value="<?php echo $file->file_name ?>" id="filenom">
                    </div>
            </div>
           <!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_466") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="file_url" value="<?php echo $file->file_url ?>" >
                        <span class="help-block"><?php echo lang("ctn_467") ?></span>
                    </div>
            </div> -->
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Status"; ?></label>
                    <div class="col-md-8">
                        <select name="status" class="form-control" id="file_status">
                        <option value="1" <?php if($file->status == 1) echo "selected" ?>><?php echo lang("ctn_830") ?></option>
                        <option value="2" <?php if($file->status == 2) echo "selected" ?>><?php echo lang("ctn_831") ?></option>
                        <option value="3" <?php if($file->status == 3) echo "selected" ?>><?php echo lang("ctn_832") ?></option>
                        <option value="4" <?php if($file->status == 4) echo "selected" ?>><?php echo lang("ctn_833") ?></option>
                        <option value="5" <?php if($file->status == 5) echo "selected" ?>><?php echo lang("ctn_834") ?></option> <!--
                        <option value="6" <?php if($file->status == 6) echo "selected" ?>><?php echo lang("ctn_1633") ?></option>
                        <option value="7" <?php if($file->status == 7) echo "selected" ?>><?php echo lang("ctn_1634") ?></option>
                        <option value="8" <?php if($file->status == 8) echo "selected" ?>><?php echo lang("ctn_1635") ?></option> -->
                        </select>
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Date Range"; ?></label>
                    <div class="col-md-8">
                        <select name="period" class="form-control" id="period">
							<option value="0" <?php if($file->period == 0) echo "selected" ?>>Entire</option>
							<option value="1" <?php if($file->period == 1) echo "selected" ?>>Custom</option>
						</select>
                    </div>
            </div>
			<div id="display_cal">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Start Date"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control form_datetime" name="st_date" value="<?php if($file->st_date == '1970-01-01 00:00:00'){echo "";}else{echo $file->st_date;} ?>" id="st_date">
                    </div>
				</div>
				<!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Start Time"; ?></label>
                    <div class="col-md-8">
                        <input type="time" class="form-control" name="st_time" value="<?php echo $file->st_time ?>" id="st_time">
                    </div>
				</div> -->
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "End Date"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control form_datetime" name="end_date" value="<?php if($file->st_date == '1970-01-01 00:00:00'){echo "";}else{echo $file->end_date;} ?>" id="end_date">
                    </div>
				</div> 
				<!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "End Time"; ?></label>
                    <div class="col-md-8">
                        <input type="time" class="form-control" name="end_time" value="<?php echo $file->end_time ?>" id="end_time">
					</div>
				</div> -->
			</div>
			<!--
            <div class="form-group" id="folder-area">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_472") ?></label>
                    <div class="col-md-8">
                        <select name="folderid" class="form-control">
                        <option value="-1"><?php echo lang("ctn_473") ?></option>
                        <?php if($folders) : ?>
                            <?php foreach($folders->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if($file->folder_parent == $r->ID) echo "selected" ?>><?php echo $r->file_name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_474") ?></span>
                    </div>
            </div>
			-->
            <hr>
            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_481") ?>" />
            <?php echo form_close() ?>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
</div>
<script type="text/javascript">
CKEDITOR.replace('file_note', { height: '100'});
</script>
<script type="text/javascript">
	$('#project-select').change(function() {
    	$.ajax({
	        url : "<?php echo site_url("files/ajaxgetformatbroadcast") ?>",
	        type : 'GET',
	        data : {
	          sourceid : $("#project-select").val(),
	        },
	        dataType: 'JSON',
	        success: function(data) {
				console.log(data);  
				$("#format_used").val(data.format_used);
				$("#broadcast_day_st_time").val(data.broadcast_day_start_time);
	        },
			error : function(data) {
				console.log('error');
			}
	    });
    });  
	$('#uploadfile').click(function() { 
		var format_used = $("#format_used").val(); 
		/* alert(format_used);  */
		if(format_used == "pdf" || format_used == "PDF"){
			var acc = ".pdf";
		}else if(format_used == "xml" || format_used == "XML"){
			var acc = "application/xml";
		}else if(format_used == "txt" || format_used == "TXT"){
			var acc = "text/plain";
		}else if(format_used == "doc" || format_used == "docx" || format_used == "DOC" || format_used == "DOCX"){
			var acc = ".doc,.docx";
		}else if(format_used == "xls" || format_used == "xlsx" || format_used == "excel" || format_used == "XLS" || format_used == "XLSX" || format_used == "EXCEL"){
			var acc = "application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
		}else if(format_used == "png" || format_used == "jpg" || format_used == "PNG" || format_used == "JPG"){
			var acc = "image/*";
		}else if(format_used == "htm" || format_used == "html" || format_used == "HTM" || format_used == "HTML"){
			var acc = "text/html";
		}else if(format_used == "avi" || format_used == "mpg" || format_used == "mpeg" || format_used == "mp4" || format_used == "AVI" || format_used == "MPG" || format_used == "MPEG" || format_used == "MP4"){
			var acc = "video/*";
		}else if(format_used == "mp3" || format_used == "wav" || format_used == "MP3" || format_used == "WAV"){
			var acc = "audio/*";
		}else if(format_used == "pdf_or_txt" || format_used == "PDF_OR_TXT"){
			var acc = ".pdf,text/plain";
		}else if(format_used == "csv" || format_used == "CSV"){
			var acc = ".csv";
		}else{
			var acc = "";
		}
		$("#uploadfile").attr("accept", acc); 
	});
	/* $('#uploadfile').change(function() { 
		if($('#uploadfile').val()) { 
			$('#file_status').val(2).trigger('change');
		}
		else{
			$('#file_status').val(1).trigger('change');	
		}
	}); */
	$('#uploadfile').change(function(e) { 
		if($('#uploadfile').val()) { 
			var fileName = e.target.files[0].name;
			var fileNom = fileName.split('.').shift(); 
			$("#filenom").val(fileNom);	
		}
		else{
			$("#filenom").val('');	
		}
	}); 
	$(function() {
		$('#display_cal').hide(); 
		$('#period').change(function(){
			if($('#period').val() == 1) {
				$('#display_cal').show(); 
			} else {
				$('#display_cal').hide(); 
			} 
		});
		if($('#period').val() == 1) {
				$('#display_cal').show(); 
			} else {
				$('#display_cal').hide(); 
			} 
	});
</script>
<script type="text/javascript">
	$('.form_datetime').datetimepicker({ 
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
</script>  
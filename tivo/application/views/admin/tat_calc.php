<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "TAT Calculator" ?></div>
</div>

<div class="container-fluid">
<?php echo form_open(site_url("admin/tat"), array("class" => "form-horizontal","id" => "tat")) ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="row">
Please fill any three of the below grids to calculate for the remaining.
<p style="color:#8c9d85;">* - Mandatory</p>
</div>
	<div class="row tat">
		<div class="col bg-success">
			<h4>Productivity *</h4>
			<div class="form-group">	
			<div class="col-md-7">
			<input id="p" type="number" class="form-control required" name="productivity" value="<?php echo $productivity_val; ?>">
			</div>	
			</div>	
		</div>
		<div class="col bg-warning">
			<h4>Volume</h4>
			<div class="form-group">	
			<div class="col-md-7">
			<input id="v" type="number" class="form-control" name="volume" value="<?php if($vol_calc == ''){echo $volume_val;}else{echo $vol_calc;} ?>">
			</div>	
			</div>
		</div>
  </div>  
  <div class="row tat">
		<div class="col bg-warning">
			<h4>Schedule</h4>
			<div class="form-group">	
			<div class="col-md-7">
			<input id="s" type="number" class="form-control" name="schedule" value="<?php if($schd_calc == ''){echo $schedule_val;}else{echo $schd_calc;} ?>">
			</div>	
			</div>
		</div>
		<div class="col bg-warning">
			<h4>Resource</h4>
			<div class="form-group">	
			<div class="col-md-7">
			<input id="r" type="number" class="form-control" name="resource" value="<?php if($res_calc == ''){echo $resource_val;}else{echo $res_calc;} ?>">
			</div>	
			</div>
		</div>
  </div>  
<input id="tat_submit" style="float:right;margin:10px 0 0 0;" type="submit" class="btn btn-primary" value="<?php echo "Calculate"; ?>">
<?php echo form_close() ?>  
</div>
</div>
</div>

<script>
    $(document).ready(function () {
		$("#tat_submit").click(function() {
			var prod = $('#p').val();
			var vol = $('#v').val();
			var schd = $('#s').val();
			var res = $('#r').val();

			if(vol == '' && schd =='') {
				alert('Please fill out any 3 fields including Productivity');
				$("#tat_submit").attr("disabled", true);
			}
			else if(vol == '' && res =='') {
				alert('Please fill out any 3 fields including Productivity');
				$("#tat_submit").attr("disabled", true);
			}
			else if(res == '' && schd =='') {
				alert('Please fill out any 3 fields including Productivity');
				$("#tat_submit").attr("disabled", true);
			}
			else {
				$("#tat_submit").attr("disabled", false);
			}
		});
	});
</script>		
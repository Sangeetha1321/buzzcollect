<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1625") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("machine/edit_shift_users_pro/" . $shift_users->id), array("class" => "form-horizontal")) ?>
	<div class="form-group">
	<label for="team_name" class="col-md-4 label-heading"><?php echo "Team Name"; ?></label>
	<div class="col-md-8 ui-front">
		<select disabled name="team_name" class="form-control">
		<?php foreach($shift_allocation as $r) : ?>
			<option value="<?php echo $r['team_name'] ?>" <?php if($r['team_name'] == $shift_users->team_name) echo "selected" ?>><?php echo $r['team_name']; ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	</div>
	<div class="form-group">
	<label for="member_name" class="col-md-4 label-heading"><?php echo "Member Name"; ?></label>
	<div class="col-md-8 ui-front">
		<input disabled type="text" class="form-control" name="member_name" value="<?php echo $shift_users->member_name ?>">
	</div>
	</div>
	<div class="form-group">
			<label for="shift_type" class="col-md-4 label-heading"><?php echo "Shift Type"; ?></label>
			<div class="col-md-8 ui-front">
				<!-- <input type="text" class="form-control" name="shift_type" value=""> -->
				<select name="shift_type" class="form-control" id="shift_type">
				<?php foreach($shift_type->result() as $r) : ?>
					<option value="<?php echo $r->shift_type; ?>" <?php if($r->shift_type == $shift_users->shift_type) echo "selected" ?>><?php echo $r->shift_type . ': '. $r->shift_timing; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
	</div>
	<input type="hidden" class="form-control" name="current_date" value="<?php echo $current_date; ?>">
			<input type="hidden" class="form-control" name="s1" id="s1" value="0">
			<input type="hidden" class="form-control" name="s2" id="s2" value="0">
			<input type="hidden" class="form-control" name="s3" id="s3" value="0">

			<div class="container-fluid">
				<h2><b>Total Machines: </b><?php echo $machine_count; ?></h2>
				<!-- <p>Number of Remaining Machines for each Shifts:</p>
					<?php foreach($sum_of_shift as $r) : ?>
						<div class="shift_machine panel-group col-xs-12">
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">First Shift</div>
							<div class="panel-body"><?php echo $machine_count - $r['sum_s1']; ?></div>
							</div>
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">Second Shift</div>
							<div class="panel-body"><?php echo $machine_count - $r['sum_s2']; ?></div>
							</div>
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">Night Shift</div>
							<div class="panel-body"><?php echo $machine_count - $r['sum_s3']; ?></div>
							</div>
						</div>
						<div class="shift_machine panel-group col-xs-12">
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">General Shift</div>
							<div class="panel-body"><?php if(($machine_count - $r['sum_s1']) < ($machine_count - $r['sum_s2'])){ echo $machine_count - $r['sum_s1']; }else{ echo $machine_count - $r['sum_s2']; } ?></div>
							</div>
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">OT 1</div>
							<div class="panel-body"><?php if(($machine_count - $r['sum_s1']) < ($machine_count - $r['sum_s2'])){ echo $machine_count - $r['sum_s1']; }else{ echo $machine_count - $r['sum_s2']; } ?></div>
							</div>
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">OT 2</div>
							<div class="panel-body"><?php if(($machine_count - $r['sum_s2']) < ($machine_count - $r['sum_s3'])){ echo $machine_count - $r['sum_s2']; }else{ echo $machine_count - $r['sum_s3']; } ?></div>
							</div>
						</div>
						<div class="shift_machine panel-group col-xs-12">
							<div class="panel panel-success col-xs-4">
							<div class="panel-heading">OT 3</div>
							<div class="panel-body"><?php if(($machine_count - $r['sum_s1']) < ($machine_count - $r['sum_s2'])){ echo $machine_count - $r['sum_s1']; }else{ echo $machine_count - $r['sum_s2']; } ?></div>
							</div>
						</div>
					<?php endforeach; ?> -->
					<div class="white-area-content">
						<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr class="table-header">
								<?php if($this->common->has_permissions(array("admin"), $this->user)) { 	
								foreach($shift_details as $r) : ?>
									<td><?php echo '<b>'.$r['shift_type'].'</b><br/><small>('.$r['shift_timing'].')</small>'; ?></td>
								<?php endforeach; 
								} else { 
								foreach($shift_detail as $r) : ?>
									<td><?php echo '<b>'.$r['shift_type'].'</b><br/><small>('.$r['shift_timing'].')</small>'; ?></td>
								<?php endforeach; 
								} ?>
							</tr>
							<tr>
								<?php if($this->common->has_permissions(array("admin"), $this->user)) { 	
								foreach($shift_details as $r) : ?>
									<td><table class="table table-bordered table-striped table-hover">								
									<?php 
									$mem_nom = $r['member_name']; 
									$mem_array = explode(',', $mem_nom);
									$team_nom = $r['team_name']; 
									$team_array = explode(',', $team_nom);
									$c = array_combine($mem_array, $team_array);
									
									foreach($c as $k => $v) { ?>
									<tr>
										<td><?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; 
										//echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($mem))))."<br>";?>
										</td>
									</tr>
									<?php } ?>
									</table></td>
								<?php endforeach; 
								} else { 
								foreach($shift_detail as $r) : ?>
									<td><table class="table table-bordered table-striped table-hover">
									<?php 
										$mem_nom = $r['member_name']; 
										$mem_array = explode(',', $mem_nom);
										$team_nom = $r['team_name']; 
										$team_array = explode(',', $team_nom);
										$c = array_combine($mem_array, $team_array);
									foreach($c as $k => $v) { ?>
										<tr><td><?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; ?></td></tr>
									<?php } ?>
								</table></td>
								<?php endforeach; 
								} ?>
							</tr>
						</table>
						</div>
					</div>	
			</div>
			
			<?php foreach($sum_of_shift as $r) : ?>
			<input type="hidden" class="form-control" name="sum_of_s1" id="sum_of_s1" value="<?php echo $r['sum_s1']; ?>">
			<input type="hidden" class="form-control" name="sum_of_s2" id="sum_of_s2" value="<?php echo $r['sum_s2']; ?>">
			<input type="hidden" class="form-control" name="sum_of_s3" id="sum_of_s3" value="<?php echo $r['sum_s3']; ?>">
			<?php endforeach; ?>  
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1625") ?>">
<?php echo form_close() ?>
</div>
</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(document).on("change", "select#shift_type", function(){
		var shift_type = $(this).val();
		var machine_count = "<?php echo $machine_count-1; ?>";
		$('input#shift_users_submit').prop('disabled', false);		
		if ((shift_type == 'General Shift') || (shift_type == 'OT 1') || (shift_type == 'OT 3')){
			$('input#s1').val('1');
			$('input#s2').val('1');
			$('input#s3').val('0');
			if(($('input#sum_of_s1').val() >= machine_count) || ($('input#sum_of_s2').val() >= machine_count)){
				alert('Select another Shift Type otherthan General shift,OT 1 or OT 3.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'OT 2'){
			$('input#s1').val('0');
			$('input#s2').val('1');
			$('input#s3').val('1');
			if(($('input#sum_of_s2').val() >= machine_count) || ($('input#sum_of_s3').val() >= machine_count)){
				alert('Select another Shift Type otherthan OT 2.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'First Shift'){
			$('input#s1').val('1');
			$('input#s2').val('0');
			$('input#s3').val('0');
			if($('input#sum_of_s1').val() >= machine_count){
				alert('Select another Shift Type otherthan First Shift,General shift,OT 1 or OT 3.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'Second Shift'){
			$('input#s1').val('0');
			$('input#s2').val('1');
			$('input#s3').val('0');
			if($('input#sum_of_s2').val() >= machine_count){
				alert('Select either First Shift or Night Shift.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'Night Shift'){
			$('input#s1').val('0');
			$('input#s2').val('0');
			$('input#s3').val('1');
			if($('input#sum_of_s3').val() >= machine_count){
				alert('Select another Shift Type otherthan Night Shift or OT 2.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else{
			$('input#s1').val('0');
			$('input#s2').val('0');
			$('input#s3').val('0');
			$('input#shift_users_submit').prop('disabled', false);
		}	
	});
});
</script> 	
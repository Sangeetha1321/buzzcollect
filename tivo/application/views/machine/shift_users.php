<?php if($this->settings->info->enable_team && $this->common->has_permissions(array("admin", "project_admin", "team_worker", "team_manage"), $this->user)) : ?>
	<div class="white-area-content">
		<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>	
		<div class="db-header clearfix">
			<div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Shift & Users"; ?>
			<small><?php $day = date('w')-1; 
				$week_start = date('M d', strtotime('-'.$day.' days'));
				$week_end = date('M d', strtotime('+'.(6-$day).' days')); 
				echo '('.$week_start.' - '.$week_end.')';
			?></small>
			</div>
			<div class="db-header-extra"> 
			<?php if($this->settings->info->enable_team && $this->common->has_permissions(array("project_admin", "team_worker", "team_manage"), $this->user)) : ?>
			<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
				<?php echo "Assign Shift to User"; ?>
			</button>
			<?php endif; ?>
			<a style="float: right; margin-left: 15px; padding: 5px 10px; font-size: 12px; line-height: 1.5; font-weight: normal;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('machine/exportshiftusers')?>" title="Export Csv">
				Export Csv
			</a>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
			<tr class="table-header">
				<td><?php echo "Team Name";  ?></td>
				<td><?php echo "Member Name"; ?></td>
				<td><?php echo "Shift Type"; ?></td>
				<!-- <td><?php echo "Created Date"; ?></td> -->
				<td><?php echo lang("ctn_52"); ?></td>
			</tr>
			<?php foreach($shift_allocation as $r) : ?>
			<tr>
				<td>
					<?php echo $r['team_name']; ?>
				</td>
				<td>
					<?php echo $r['member_name']; ?>
				</td>
				<td>
					<label class="label label-default"><?php echo $r['shift_type']; ?></label>
				</td>
				<!-- <td>
					<?php echo $r['created_date']; ?>
				</td> -->
				<td>
					<a href="<?php echo site_url("machine/edit_shift_users/" . $r['id']) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
						<span class="glyphicon glyphicon-cog"></span>
					</a>
					<a href="<?php echo site_url("machine/delete_shift_users/" . $r['id'] . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
						<span class="glyphicon glyphicon-trash"></span>
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
<?php endif; ?>
<br/>
<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Current Week Shift Details"; ?>
	<small>
	<?php $day = date('w')-1; 
		$week_start = date('M d', strtotime('-'.$day.' days'));
		$week_end = date('M d', strtotime('+'.(6-$day).' days')); 
		echo '('.$week_start.' - '.$week_end.')';
	?></small>
	</div>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('machine/exportcurrentweekshifts')?>" title="Export Csv">
		Export Csv
	</a>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header">
	<?php foreach($current_week_shifts as $r) : ?>
		<td><?php echo $r['shift_type']; ?></td>
	<?php endforeach; ?>
</tr>
<tr>
<?php foreach($current_week_shifts as $r) : ?>
	<td>
		<table class="table table-bordered table-striped table-hover">
				<?php 
				$mem_nom = $r['member_name']; 
				$mem_array = explode(',', $mem_nom);
				$team_nom = $r['team_name']; 
				$team_array = explode(',', $team_nom);
				$combine = array_combine($mem_array, $team_array);
				?>
			<?php foreach($combine as $k => $v) { ?>
			<tr>
				<td><?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; 
				//echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($mem))))."<br>";?>
				</td>
			</tr>
		<?php } ?>	
		</table>
	</td>
<?php endforeach; ?>
</tr>
</table>
</div>
</div>
<br/>
<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Previous Week Shift Details"; ?>
	<small>
	<?php /* $day = date('w')-1; 
		$week_start = date('M d', strtotime('-'.(7-$day).' days')); 
		$week_end = date('M d', strtotime('-'.(1-$day).' days')); 
		echo '('.$week_start.' - '.$week_end.')'; */
		$lastWeek = array();
		$prevMon = abs(strtotime("previous monday"));
		$currentDate = abs(strtotime("today"));
		$seconds = 86400; //86400 seconds in a day
		$dayDiff = ceil( ($currentDate-$prevMon)/$seconds ); 
		if( $dayDiff < 7 )
		{
			$dayDiff += 1; //if it's monday the difference will be 0, thus add 1 to it
			$prevMon = strtotime( "previous monday", strtotime("-$dayDiff day") );
		}
		$prevMon = date("Y-m-d",$prevMon);
		// create the dates from Monday to Sunday
		/* for($i=0; $i<7; $i++)
		{
			$d = date("M d", strtotime( $prevMon." + $i day") );
			$lastWeek[]=$d;
		} 
		print_R($lastWeek);
		*/
		$week_start = date("M d", strtotime( $prevMon." + ". 0 ."day") );
		$week_end = date("M d", strtotime( $prevMon." + ". 6 ."day") );
		echo '('.$week_start.' - '.$week_end.')'; 
	?></small>
	</div>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('machine/exportlastweekshifts')?>" title="Export Csv">
		Export Csv
	</a>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header">
	<?php foreach($last_week_shifts as $r) : ?>
		<td><?php echo $r['shift_type']; ?></td>
	<?php endforeach; ?>
</tr>
<tr>
<?php foreach($last_week_shifts as $r) : ?>
	<td><table class="table table-bordered table-striped table-hover">
		<?php 
		$mem_nom = $r['member_name']; 
		$mem_array = explode(',', $mem_nom);
		$team_nom = $r['team_name']; 
		$team_array = explode(',', $team_nom);
		$com = array_combine($mem_array, $team_array);
		?>
		<!-- <td>
		<?php 
		/* foreach($mem_array as $mem) {
			echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($mem))))."<br>";
		} */ ?> 
		</td>
		<td>
		<?php 
		/* foreach($team_array as $te) {
			echo $te."<br>";
		}  */
		?>
		</td> -->
	<?php
		foreach($com as $k => $v) { ?>
		<tr>
			<td><?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; 
			//echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($mem))))."<br>";?>
			</td>
		</tr>
	<?php } ?>
	</table></td>
<?php endforeach; ?>
</tr>
</table>
</div>
</div>
<br/>
<!--
<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Shift Details"; ?></div>
	<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('machine/exportallshiftdetails')?>" title="Export Csv">
		Export Csv
	</a>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header">
	<?php foreach($shift_details as $r) : ?>
		<td><?php echo $r['shift_type']; ?></td>
	<?php endforeach; ?>
</tr>
<tr>
<?php foreach($shift_details as $r) : ?>
	<td><table class="table table-bordered table-striped table-hover">
		<?php 
		$mem_nom = $r['member_name']; 
		$mem_array = explode(',', $mem_nom);
		$team_nom = $r['team_name']; 
		$team_array = explode(',', $team_nom);
		$c = array_combine($mem_array, $team_array);
		?>
		<?php
		$count = 0;
		foreach($c as $k => $v) { ?>
		<tr>
			<td><?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; 
			//echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($mem))))."<br>";?>
			</td>
		</tr>
		<?php $count++; } ?>
		<tr><td><b><?php echo "Total Members: " .$count;?></b></td></tr>
		</table></td>
<?php endforeach; ?>
</tr>
</table>
</div>
</div> -->
                    
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<div id="editme" class="btn btn-primary">Edit Shifts</div>
	 <div id="addblock">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo "Assign Shift to User"; ?>
		<small style="color:#ffffff;">
		<?php $day = date('w')-1; 
			$week_start = date('M d', strtotime('-'.$day.' days'));
			$week_end = date('M d', strtotime('+'.(6-$day).' days')); 
			echo '('.$week_start.' - '.$week_end.')';
		?></small>
		</h4>
      </div>
      <div class="modal-body">
            <?php echo form_open_multipart(site_url("machine/add_shift_users"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
				<label for="team_name" class="col-md-4 label-heading"><?php echo "Team Name"; ?></label>
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
                    <label for="member_name" class="col-md-4 label-heading"><?php echo "Member Name"; ?></label>
                    <div class="col-md-8 ui-front">
						<!-- <select name="member_name" class="form-control" id="member_name">
						</select> -->
						<select  multiple="multiple" name="member_name[]" class="form-control" id="member_name">
						</select> 
						<div id="membername_check"></div>
                    </div>
            </div>
			<div class="form-group">
                    <label for="shift_type" class="col-md-4 label-heading"><?php echo "Shift Type"; ?></label>
                    <div class="col-md-8 ui-front">
                        <!-- <input type="text" class="form-control" name="shift_type" value=""> -->
						<select name="shift_type" class="form-control" id="shift_type">
							<option value=""><?php echo "Select the Shift Type"; ?></option>
						<?php foreach($shift_type->result() as $r) : ?>
							<option value="<?php echo $r->shift_type; ?>"><?php echo $r->shift_type . ': '. $r->shift_timing; ?></option>
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
										<td>
										<?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; 
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
										$week = $r['week_number_of_year']; 
										$crdate = $r['created_date']; 
										$time=strtotime($crdate);
										$year = date("Y",$time);
										$mem_nom = $r['member_name']; 
										$mem_array = explode(', ', $mem_nom);
										$team_nom = $r['team_name']; 
										$team_array = explode(', ', $team_nom);
										$c = array_combine($mem_array, $team_array);
										
									foreach($c as $k => $v) { ?>
										<tr>
										<td>
										<a style="float:right;" href="<?php echo site_url("machine/delete_it/" . $k . "/" . $v . "/" . $week . "/" . $year . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
											<span class="glyphicon glyphicon-trash"></span>
										</a>
										<?php echo "<b>".strtoupper($k)."</b><br>(".$v.")"; ?>
										</td>
										</tr>
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
	  </div>
	  
      <div class="modal-footer">
		<a style="float:left;" href="<?php echo site_url("machine/delete_all_shift_users") ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo "Are you sure you want to delete all the above assigned shifts?"; ?>')" title="<?php echo "Delete All"; ?>"  data-toggle="tooltip" data-placement="bottom">
			<span class="glyphicon glyphicon-trash"></span> Delete All
		</a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input id="shift_users_submit" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1622") ?>">
        <?php echo form_close(); ?>
      </div>
	</div>
	
	<div id="editblock" style="display:none;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo "Edit Shift to User"; ?>
		<small style="color:#ffffff;">
		<?php $day = date('w')-1; 
			$week_start = date('M d', strtotime('-'.$day.' days'));
			$week_end = date('M d', strtotime('+'.(6-$day).' days')); 
			echo '('.$week_start.' - '.$week_end.')';
		?></small>
		</h4>
      </div>
	   <div class="modal-body">
            <?php echo form_open_multipart(site_url("machine/edit_shift_for_user"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
				<label for="eteam_name" class="col-md-4 label-heading"><?php echo "Team Name"; ?></label>
				<div class="col-md-8 ui-front">
				<select name="eteam_name" class="form-control" id="eteam_name">
					<option value=""><?php echo "Select the Team Name"; ?></option>
					<?php if(isset($team_name)){ foreach($team_name->result() as $r) : ?>
						<option value="<?php echo $r->team_name; ?>"><?php echo $r->team_name; ?></option>
					<?php endforeach; } ?>
				</select>
				</div>
            </div>
			<div class="form-group">
                    <label for="emember_name" class="col-md-4 label-heading"><?php echo "Member Name"; ?></label>
                    <div class="col-md-8 ui-front">
						<select name="emember_name" class="form-control" id="emember_name">
						</select> 
						<div id="emembername_check"></div>
                    </div>
            </div>
			<div class="form-group">
                    <label for="eshift_type" class="col-md-4 label-heading"><?php echo "Shift Type"; ?></label>
                    <div class="col-md-8 ui-front">
						<select name="eshift_type" class="form-control" id="eshift_type">
							<option value=""><?php echo "Select the Shift Type"; ?></option>
						<?php foreach($shift_type->result() as $r) : ?>
							<option value="<?php echo $r->shift_type; ?>"><?php echo $r->shift_type . ': '. $r->shift_timing; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
            </div>
			<input type="hidden" class="form-control" name="current_date" value="<?php echo $current_date; ?>">
			<input type="hidden" class="form-control" name="es1" id="es1" value="0">
			<input type="hidden" class="form-control" name="es2" id="es2" value="0">
			<input type="hidden" class="form-control" name="es3" id="es3" value="0">
			<div class="container-fluid">
				<h2><b>Total Machines: </b><?php echo $machine_count; ?></h2>
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
			<input type="hidden" class="form-control" name="esum_of_s1" id="esum_of_s1" value="<?php echo $r['sum_s1']; ?>">
			<input type="hidden" class="form-control" name="esum_of_s2" id="esum_of_s2" value="<?php echo $r['sum_s2']; ?>">
			<input type="hidden" class="form-control" name="esum_of_s3" id="esum_of_s3" value="<?php echo $r['sum_s3']; ?>">
			<?php endforeach; ?> 
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input id="edit_shift_users" type="submit" class="btn btn-primary" value="<?php echo "Edit Shift"; ?>">
        <?php echo form_close(); ?>
      </div>
	  </div>
	
    </div>
  </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(document).on("change", "select#team_name", function(){
	 /*  $('select#team_name').change(function ()  */
		 var team_name = $(this).val(); 
			var hitURL = "<?php echo base_url(); ?>machine/ajaxcall";
			var csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
			var csrf_hash = "<?php $this->security->get_csrf_hash() ?>";
           // console.log(team_name);
			/* alert(csrf_hash); */
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { teamname : team_name } 
			/* data: { 
					teamname : team_name,
					csrf_token_name : csrf_hash
				  } */
			}).done(function(data){
				/* console.log(data);
				alert(data); */
				$('select#member_name').html('');
                for(var i=0;i<data.length;i++)
                {
				/* alert(data[i]); */
                    $("<option />").val(data[i].username)
                                   .text(data[i].username)
                                   .appendTo($('select#member_name'));
                }
			});
	});	
	<?php if($this->settings->info->enable_team && $this->common->has_permissions(array("project_admin", "team_worker", "team_manage"), $this->user)) : ?>
	jQuery( window ).on( "load", function() {
		$('#addModal').modal('show');
	});
	<?php endif; ?>
	/* jQuery(document).on("change", "select#shift_type", function(){ */
	jQuery("select#shift_type, select#member_name").change(function() { 	
		var shift_type = $("select#shift_type").val();
		var length = $('#member_name > option:selected').length;
		var len = (length - 1); 
		var sum_s1 = $('input#sum_of_s1').val();
		var sum_s2 = $('input#sum_of_s2').val();
		var sum_s3 = $('input#sum_of_s3').val();
		var s1 = parseInt(sum_s1, 10) + parseInt(len, 10);
		var s2 = parseInt(sum_s2, 10) + parseInt(len, 10);
		var s3 = parseInt(sum_s3, 10) + parseInt(len, 10);
		
		var machine_count = "<?php echo $machine_count; ?>";
		/* alert(s1); */
		$('input#shift_users_submit').prop('disabled', false);		
		if ((shift_type == 'General Shift') || (shift_type == 'OT 1') || (shift_type == 'OT 3')){
			$('input#s1').val('1');
			$('input#s2').val('1');
			$('input#s3').val('0');
			if((s1 >= machine_count) || (s2 >= machine_count)){
				// machine_count is the Machine count for testing purpose, its 3. Actually its 57 later.
				alert('Select another Shift Type other than General shift,OT 1 or OT 3.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'OT 2'){
			$('input#s1').val('0');
			$('input#s2').val('1');
			$('input#s3').val('1');
			if((s2 >= machine_count) || (s3 >= machine_count)){
				alert('Select another Shift Type other than OT 2.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'First Shift'){
			$('input#s1').val('1');
			$('input#s2').val('0');
			$('input#s3').val('0');
			if(s1 >= machine_count){
				alert('Select another Shift Type other than First Shift,General shift,OT 1 or OT 3.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'Second Shift'){
			$('input#s1').val('0');
			$('input#s2').val('1');
			$('input#s3').val('0');
			if(s2 >= machine_count){
				alert('Select either First Shift or Night Shift.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else if (shift_type == 'Night Shift'){
			$('input#s1').val('0');
			$('input#s2').val('0');
			$('input#s3').val('1');
			if(s3 >= machine_count){
				alert('Select another Shift Type other than Night Shift or OT 2.');
				$('input#shift_users_submit').prop('disabled', true);
			}
		}
		else{
			$('input#s1').val('0');
			$('input#s2').val('0');
			$('input#s3').val('0');
			$('input#shift_users_submit').prop('disabled', false);
		}	
		$("select#member_name").click();
	});
	
	jQuery(document).on("click", "select#member_name", function(){
	//$("select#shift_type, select#member_name").click(function() { 
	/* alert('test'); */
		var member_name = $('select#member_name').val();
		if(member_name.length > 0) {
		$.ajax({
			url: global_base_url + "machine/check_membername",
			type: "get",
			data: {
				"member_name" : member_name
			},
			success: function(msg) {
				if(!msg){
					$('#membername_check').html('');
					/* $('input#shift_users_submit').prop('disabled', false);  */
				}else{
					$('#membername_check').html(msg);
					$('input#shift_users_submit').prop('disabled', true);
				}
			}
		});
		} else {
		$('#membername_check').html('');
		$('input#shift_users_submit').prop('disabled', false);
		}
	
	});
});
/*  Start of editpoup up */
$( "#editme" ).click(function() {
	$( "#editblock" ).toggle( "slow", function() {});
	$( "#addblock" ).toggle( "slow", function() {});
});
jQuery(document).ready(function(){
	jQuery(document).on("change", "select#eteam_name", function(){
		 var team_name = $(this).val(); 
			var hitURL = "<?php echo base_url(); ?>machine/ajaxcall";
			var csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
			var csrf_hash = "<?php $this->security->get_csrf_hash() ?>";
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { teamname : team_name } 
			}).done(function(data){
				$('select#emember_name').html('');
                for(var i=0;i<data.length;i++)
                {
                    $("<option />").val(data[i].username)
                                   .text(data[i].username)
                                   .appendTo($('select#emember_name'));
                }
			});
	});	
	jQuery("select#eshift_type, select#emember_name").change(function() { 	
	/* $("select#eshift_type, select#emember_name").change(function() {  */
		var shift_type = $("select#eshift_type").val();
		var length = $('#emember_name > option:selected').length;
		var len = (length - 1); 
		var sum_s1 = $('input#esum_of_s1').val();
		var sum_s2 = $('input#esum_of_s2').val();
		var sum_s3 = $('input#esum_of_s3').val();
		var s1 = parseInt(sum_s1, 10) + parseInt(len, 10);
		var s2 = parseInt(sum_s2, 10) + parseInt(len, 10);
		var s3 = parseInt(sum_s3, 10) + parseInt(len, 10);
		
		var machine_count = "<?php echo $machine_count; ?>";
		$('input#edit_shift_users').prop('disabled', false);		
		if ((shift_type == 'General Shift') || (shift_type == 'OT 1') || (shift_type == 'OT 3')){
			$('input#es1').val('1');
			$('input#es2').val('1');
			$('input#es3').val('0');
			if((s1 >= machine_count) || (s2 >= machine_count)){
				// machine_count is the Machine count for testing purpose, its 3. Actually its 57 later.
				alert('Select another Shift Type other than General shift,OT 1 or OT 3.');
				$('input#edit_shift_users').prop('disabled', true);
			}
		}
		else if (shift_type == 'OT 2'){
			$('input#es1').val('0');
			$('input#es2').val('1');
			$('input#es3').val('1');
			if((s2 >= machine_count) || (s3 >= machine_count)){
				alert('Select another Shift Type other than OT 2.');
				$('input#edit_shift_users').prop('disabled', true);
			}
		}
		else if (shift_type == 'First Shift'){
			$('input#es1').val('1');
			$('input#es2').val('0');
			$('input#es3').val('0');
			if(s1 >= machine_count){
				alert('Select another Shift Type other than First Shift,General shift,OT 1 or OT 3.');
				$('input#edit_shift_users').prop('disabled', true);
			}
		}
		else if (shift_type == 'Second Shift'){
			$('input#es1').val('0');
			$('input#es2').val('1');
			$('input#es3').val('0');
			if(s2 >= machine_count){
				alert('Select either First Shift or Night Shift.');
				$('input#edit_shift_users').prop('disabled', true);
			}
		}
		else if (shift_type == 'Night Shift'){
			$('input#es1').val('0');
			$('input#es2').val('0');
			$('input#es3').val('1');
			if(s3 >= machine_count){
				alert('Select another Shift Type other than Night Shift or OT 2.');
				$('input#edit_shift_users').prop('disabled', true);
			}
		}
		else{
			$('input#es1').val('0');
			$('input#es2').val('0');
			$('input#es3').val('0');
			$('input#edit_shift_users').prop('disabled', false);
		}	
		$("select#emember_name").click();
	});
});
/*  End of editpoup up */
</script> 
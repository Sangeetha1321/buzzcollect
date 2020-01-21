<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1619") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("machine/edit_machine_pro/" . $machine->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
				<label for="machine_no" class="col-md-4 label-heading"><?php echo lang("ctn_1613") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="machine_no" value="<?php echo $machine->machine_no ?>">
				</div>
            </div>
			<div class="form-group">
				<label for="team_name" class="col-md-4 label-heading"><?php echo lang("ctn_1614") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="team_name" value="<?php echo $machine->team_name ?>">
				</div>
            </div>
			<div class="form-group">
				<label for="softwares" class="col-md-4 label-heading"><?php echo lang("ctn_1615") ?></label>
				<div class="col-md-8 ui-front">
				<select name="softwares[]" multiple="multiple" class="form-control">
				<?php 
				$target = explode(",",$machine->softwares);
				$total = json_decode(json_encode($softwares->result()), true);
				$softwares_available = (array_column($total, 'softwares_available'));
				/* echo "<pre>";
				print_r($softwares_available);
				print_r($target);  */
			
				foreach ($softwares_available as $needle)
				{
					if (in_array($needle, $target)){$selected = 'selected="selected"'; }
					else{$selected = '';}
					echo "<option value='$needle' $selected>$needle</option>"; 
				} 
				?>
				</select>
				</div>
			</div>		
			<div class="form-group">
				<label for="shifts" class="col-md-4 label-heading"><?php echo lang("ctn_1616") ?></label>
				<div class="col-md-8 ui-front">
				<select name="shifts[]" multiple="multiple" class="form-control">
				<?php 
					$target = explode(",",$machine->shifts);
					//$shift_type = array('First Shift','Second Shift','Night Shift','General Shift','OT - 6am to 6pm','OT - 6pm to 6am','OT - 10am to 10pm','OT - 10pm to 10am');
					/* echo "<pre>";
					print_r($shift_type);
					print_r($target);  */
					$total = json_decode(json_encode($shifts->result()), true);
					$shift_type = (array_column($total, 'shift_type'));
					foreach ($shift_type as $needle)
					{
						if (in_array($needle, $target)){$selected = 'selected="selected"'; }
						else{$selected = '';}
						echo "<option value='$needle' $selected>$needle</option>"; 
					} 
				?>
				</select>
				</div>
			</div>	
			<div class="form-group">
				<label for="seat_no" class="col-md-4 label-heading"><?php echo lang("ctn_1617") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="seat_no" value="<?php echo $machine->seat_no ?>">
				</div>
            </div>
			<div class="form-group">
				<label for="head_count" class="col-md-4 label-heading"><?php echo lang("ctn_1618") ?></label>
				<div class="col-md-8 ui-front">
					<input type="text" class="form-control" name="head_count" value="<?php echo $machine->head_count ?>">
				</div>
            </div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1620") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Shift & Users"; ?></div>
    <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo "Assign Shift to User"; ?></button>
</div>
</div>

<p><?php //echo lang("ctn_764") ?></p>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo "Team Name";  ?></td>
	<td><?php echo "Member Name"; ?></td>
	<td><?php echo "Shift Type"; ?></td>
	<td><?php echo "Created Date" ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
<?php foreach($shift_allocation->result() as $r) : ?>
<tr>
	<td>
		<?php echo $r->team_name; ?>
	</td>
	<td>
		<?php echo $r->member_name; ?>
	</td>
	<td>
		<label class="label label-default"><?php echo $r->shift_type; ?></label>
	</td>
	<td>
		<?php echo $r->created_date; ?>
	</td>
	<td>
		<a href="<?php echo site_url("machine/edit_shift_users/" . $r->id) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
			<span class="glyphicon glyphicon-cog"></span>
		</a>
		<a href="<?php echo site_url("machine/delete_shift_users/" . $r->id . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
<?php endforeach; ?>
</table>
</div>

</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo "Assign Shift to User"; ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("machine/add_shift_users"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="team_name" class="col-md-4 label-heading"><?php echo "Team Name"; ?></label>
                    <div class="col-md-8 ui-front">
					<select name="team_name" class="form-control" id="team_name">
					<option value="<?php echo "Select the Team Name" ?>"><?php echo "Select the Team Name" ?></option>
					<?php foreach($team_name->result() as $r) : ?>
						<option value="<?php echo $r->team_name ?>"><?php echo $r->team_name ?></option>
					<?php endforeach; ?>
					</select>
					</div>
            </div>
			<div class="form-group">
                    <label for="member_name" class="col-md-4 label-heading"><?php echo "Member Name"; ?></label>
                    <div class="col-md-8 ui-front">
                        <!-- <input type="text" class="form-control" name="member_name" value=""> -->
						<select name="member_name" class="form-control" id="member_name">
						</select>
                    </div>
            </div>
			<div class="form-group">
                    <label for="shift_type" class="col-md-4 label-heading"><?php echo "Shift Type"; ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="shift_type" value="">
                    </div>
            </div>
			<input type="hidden" class="form-control" name="current_date" value="<?php echo $current_date; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1622") ?>">
        <?php echo form_close() ?>
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
			var csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
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
                    $("<option />").val(data[i].first_name)
                                   .text(data[i].first_name)
                                   .appendTo($('select#member_name'));
                }
			});
		
	});
 
    });
</script>
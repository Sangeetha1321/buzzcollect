<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo "Team and Users" ?></div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo lang("ctn_1626") ?></td>
	<td><?php echo "Users"; ?></td>
	<td><?php echo "Count"; ?></td>
</tr>
<?php foreach($team_users->result() as $r) : ?>
<tr>
	<td>
		<?php echo $r->team_name; ?>
	</td>
	<td>
		<?php 
		$count_loop = 0;
		foreach($team_users_all_team->result() as $tuat) : 
		if($r->team_name == $tuat->team_name){
		?>
			<label class="label label-default" id="team_vs_users">
				<?php echo $tuat->username; ?>
			</label>
		<?php 
		$count_loop++;
		}
		endforeach; ?>	
	</td>
	<td>
		<?php echo $count_loop; ?>
	</td>
</tr>
<?php endforeach; ?>
</table>
</div>


<div class="content-separator block-area">

<?php echo form_open(site_url("machine/team_users/"), array("class" => "form-horizontal","id" => "resources_sharing")) ?><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">	
		<label for="teamgroup-in" class="col-md-5 label-heading"><?php echo "Team"; ?></label>
		<div class="col-md-7">
		<select name="all_teams" id="all_teams">
				<option value="">All teams</option>			
				<?php if(isset($team_users)){ foreach($team_users->result() as $team) { ?>
					<option value="<?php echo $team->team_name; ?>" <?php if ($all_teams == $team->team_name) echo 'selected="selected"'; ?> >
						<?php echo $team->team_name; ?>
					</option>	
				<?php }} ?>	 
		</select>
		</div>	
		</div>	
		<div class="form-group">	
		<label for="allresources-in" class="col-md-5 label-heading"><?php echo "Resources"; ?></label>
		<div class="col-md-7">
		<select name="all_resources" id="all_resources">
				<option value="">Select any Resources</option>			
				<?php if(isset($allresources)){ foreach($allresources as $resource) { ?>
					<option value="<?php echo $resource['ID']; ?>" <?php /* if ($all_resources == $resource['ID']) echo 'selected="selected"'; */ ?> >
						<?php echo $resource['username']; ?>
					</option>	
				<?php }} ?>	 
		</select>
		</div>	
		</div>	
			<input style="display:none;" type="submit" class="btn btn-primary" value="<?php echo "Submit"; ?>">
			<?php echo form_close() ?>
	</div>

<h4 class="home-label"><?php echo "Shared Resources"; ?></h4>

<div class="table-responsive" id="daily_mem_productivity">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo "Original Team Name"; ?></td>
	<td><?php echo "Resources"; ?></td>
	<td><?php echo "Shared to Team"; ?></td>
	<td><?php echo "Shared Date"; ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
<?php 
foreach($shared_resources as $shres) :
$resource_id = $shres->ID;
$team_name = $shres->team_name;
$shared_resource = $shres->shared_resource;
$username = $shres->username;
$avatar = $shres->avatar;
$online_timestamp = $shres->online_timestamp; 
if($shres->shared_from != 0){
	$shared_from = date('d/m/Y h:i:s', $shres->shared_from); 
}
else{
	$shared_from = ''; 
} 
?>
<tr>
	<td>
	<?php echo $team_name; ?>
	</td>
	<td> 
		<?php echo $this->common->get_user_display(array("username" => $username, "avatar" => $avatar, "online_timestamp" => $online_timestamp))  ?>
		<span><?php echo $username; ?></span>
	</td>
	<td>
		<?php 
			echo $shared_resource; 		
		?>
	</td>
	<td>
		<?php 
			echo $shared_from; 		
		?>
	</td>
	<td><!--
		<a href="<?php echo site_url("machine/edit_resources/" . $resource_id) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
			<span class="glyphicon glyphicon-cog"></span>
		</a> -->
		<a href="<?php echo site_url("machine/delete_resources/" . $resource_id . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
<?php
endforeach;  ?>
</table>
</div>

</div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_1628") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("machine/add_team_name"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="team_name" class="col-md-4 label-heading"><?php echo lang("ctn_1626") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="team_name" value="">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1628") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
$("#all_teams").change(function () {
	$("#resources_sharing").submit();
}); 
$("#all_resources").change(function () {
	$("#resources_sharing").submit();
	location.reload();
}); 
});
</script>
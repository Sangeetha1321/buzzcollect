<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1602") ?></div>
    <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_1603") ?></button>
</div>
</div>

<p><?php //echo lang("ctn_764") ?></p>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_1580") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($skills->result() as $r) : ?>
<tr>
	<td>
		<label class="label label-default"><?php echo $r->skills ?></label>
	</td>
	<td>
		<a href="<?php echo site_url("admin/edit_skills/" . $r->ID) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
			<span class="glyphicon glyphicon-cog"></span>
		</a>
		<a href="<?php echo site_url("admin/delete_skills/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
<?php endforeach; ?>
</table>
</div>

<div class="content-separator block-area">
<?php echo form_open(site_url("admin/skills/"), array("class" => "form-horizontal","id" => "skill_matrix","name" => "project_status")) ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">	
			<label for="teamgroup-in" class="col-md-4 label-heading"><?php echo "Select Team Name"; ?></label>
			<div class="col-md-8">
			<select name="teamnom" id="teamnom">	
			<option value=""><?php echo "Select Team Name"; ?></option> 
			<?php if(isset($team_list)){ foreach($team_list as $team) { ?>
				<option value="<?php echo $team->team_name; ?>" <?php if ($team_name == $team->team_name) echo 'selected="selected"'; ?> >
					<?php echo $team->team_name; ?>
				</option> 	
			<?php }} ?>						 
			</select>
			<input style="display:none;" type="submit" class="btn btn-primary" value="<?php echo "Submit"; ?>">
			</div>	
		</div>	
</div>
<?php echo form_close() ?>
<h4 class="home-label"><?php echo "Skill Matrix"; ?>
<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('admin/skillmatrixexport')?>" title="Export">Export
</a>
</h4>
<div class="table-responsive">
<table class="table small-text table-bordered table-striped table-hover">
<tr class="table-header">
	<td><?php echo "Employee ID"; ?></td>
	<td><?php echo "Email"; ?></td>
	<td><?php echo "Username"; ?></td>
	<td><?php echo "Skill Set"; ?></td>
	<td><?php echo "Team Name"; ?></td>
	<td><?php echo "Designation"; ?></td>
	<td><?php echo "Department"; ?></td>
	<td><?php echo "DOJ"; ?></td>
	<td><?php echo "Reporting"; ?></td>
	<td><?php echo "Education"; ?></td>
</tr>
<?php foreach($skillmatrix as $r) : ?>
<tr>
<td><?php echo $r['employee_id']; ?></td>
<td><?php echo $r['email']; ?></td>
<td><?php echo $r['username']; ?></td>
<td><?php echo $r['skillSet']; ?></td>
<td><?php echo $r['team_name']; ?></td>
<td><?php echo $r['designation']; ?></td>
<td><?php echo $r['department']; ?></td>
<td><?php echo $r['doj']; ?></td>
<td><?php echo $r['reporting']; ?></td>
<td><?php echo $r['education']; ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_763") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("admin/add_skills"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="skills" class="col-md-4 label-heading"><?php echo lang("ctn_1580") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="skills" value="">
                    </div>
            </div>
            <!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php //echo lang("ctn_765") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control jscolor" id="p-in" name="color" value="">
                    </div>
            </div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_763") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
$(function() {
    $('#teamnom').change(function() {
        this.form.submit();
    });
});
});
</script>
<!-- <div class="white-area-content">
	<div class="db-header no-margin-bottom clearfix">
		<div class="page-header-title"> 
			<span class="glyphicon glyphicon-folder-open"></span><?php echo lang("ctn_1367") ?>
		</div>
		<div class="db-header-extra form-inline"></div>
	</div>
</div> -->

<div class="row content-separator">
<div class="col-md-8">
<div class="white-area-content">
<div class="db-header clearfix">
		<div class="page-header-simple-title"><?php echo $project->name ?></div>
		<div class="db-header-extra form-inline"> 
			<?php if( $this->common->has_permissions(array("admin", "project_admin", "doc_manage", "doc_worker"), $this->user) || ($this->common->has_team_permissions(array("doc"), $team_member)) ) : ?><?php endif; ?>
			<?php if( $this->common->has_permissions(array("admin", "project_admin"), $this->user) || ($this->common->has_team_permissions(array("admin"), $team_member)) ) : ?>
				<a href="<?php echo site_url("projects/edit_project/" . $project->ID) ?>" class="btn btn-round btn-sm" title="<?php echo lang("ctn_55") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("projects/delete_project/" . $project->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-round btn-sm" onclick="return confirm(\'<?php echo lang("ctn_789") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>
			<?php endif; ?>
		</div>
	</div>
	<?php /* echo $project->description */ ?>
	<!-- <hr> -->
	<?php if($project->tivo == 1){ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Source ID"; ?></p>
		<p class="project-info-bit"><?php echo $project->customer ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Transition Status"; ?></p>
		<p class="project-info-bit">
		<?php foreach($categories->result() as $r) : ?>
		<?php if($r->ID == $project->catid){echo $r->name;}?>
		<?php endforeach; ?>
		</p>
		</div>
		<!--
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Frequency"; ?></p>
		<p class="project-info-bit"><?php echo $project->frequency; ?></p>
		</div> 
		-->
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Format Used"; ?></p>
		<p class="project-info-bit"><?php echo $project->process_name; ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Team"; ?></p>
		<p class="project-info-bit"><?php echo $project->team_name; ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Channel Config"; ?></p>
		<p class="project-info-bit">
		<!-- <a href="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>"><?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?></a>-->
		<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>
		</p> 
		</div>
		<!--
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Reporter"; ?></p>
		<p class="project-info-bit"><?php echo $project->pm; ?></p>
		</div>
		
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo "Country"; ?></p>
		<p class="project-info-bit"><?php echo $project->country; ?></p>
		</div>-->
		<?php } else{ ?>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1581"); ?></p>
		<p class="project-info-bit"><?php echo $project->customer ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1582") ?></p>
		<p class="project-info-bit"><?php echo $project->publisher ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1586") ?></p>
		<p class="project-info-bit"><?php echo $project->acronym ?></p>
		</div>
		<div class="project-info project-block">
		<p class="project-info-title"><?php echo lang("ctn_1587") ?></p>
		<p class="project-info-bit"><?php if($project->process_name == 'Geofacets Table conversion' || $project->process_name == 'Geofacets Figure conversion'){echo $project->process_name.' & '.$project->geofacets_process_name;}else{echo $project->process_name;} ?></p>
		</div>
		<?php if(isset($project->stage)){ ?>
			<div class="project-info project-block">
				<p class="project-info-title"><?php echo "Stage"; ?></p>
				<p class="project-info-bit"><?php echo $project->stage ?></p>
			</div>
		<?php } if(isset($project->pdfType)){ ?>
			<div class="project-info project-block">
				<p class="project-info-title"><?php echo "PDF Type"; ?></p>
				<p class="project-info-bit">
					<?php 
						if($project->process_name == 'Geofacets Table conversion' || $project->process_name == 'Geofacets Figure conversion'){
							echo $project->pdfType.' & '.$project->geofacets_pdfType;
						} else{
							echo $project->pdfType;
						}
					?>
				</p>
			</div>
		<?php } ?>
		<div class="project-info project-block">
			<p class="project-info-title"><?php echo lang("ctn_1588") ?></p>
			<p class="project-info-bit"><?php echo $project->pm ?></p>
		</div>
		<?php } ?>
<hr>
<div class="project-info project-block">
	<p class="project-info-title"><?php echo lang("ctn_1368") ?></p>
	<p class="project-info-bit"><?php echo date($this->settings->info->date_format, $project->timestamp) ?></p>
</div>
<div class="project-info project-block">
	<p class="project-info-title"><?php echo lang("ctn_1369") ?></p>
	<p class="project-info-bit"><?php echo $members->num_rows() ?></p>
</div>
<div class="project-info project-block">
	<p class="project-info-title"><?php echo "Broadcast Day Start Time"; ?></p>
	<p class="project-info-bit"><?php echo $project->broadcast_day_start_time; ?></p>
</div>
<!--
<div class="project-info project-block">
	<p class="project-info-title"><?php echo lang("ctn_1370") ?></p>
	<p class="project-info-bit"><?php echo $tasks_total ?></p>
</div>
<div class="project-info project-block">
	<p class="project-info-title"><?php echo lang("ctn_1371") ?></p>
	<p class="project-info-bit"><?php echo $project->complete ?>%</p>
</div>
<div class="project-info project-block">
	<p class="project-info-title"><?php echo lang("ctn_1486") ?></p>
	<p class="project-info-bit"><?php echo $hours ?></p>
</div> -->
</div>

<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker", "task_client"), $this->user)) : ?>
<!-- <div class="white-area-content content-separator">
    <div class="db-header clearfix">
        <div class="page-header-simple-title"><?php echo lang("ctn_1370") ?></div>
		<div class="db-header-extra form-inline"> 
			<a href="<?php echo site_url("projects/gantt_chart/" . $project->ID) ?>" class="btn btn-round btn-sm"><?php echo lang("ctn_1372") ?></a>
		<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
			<a href="<?php echo site_url("tasks/add") ?>" class="btn btn-round btn-sm"><span class="glyphicon glyphicon-plus"></span></a>
		<?php endif; ?>
		</div>
    </div>
	<div class="table-responsive">
		<table id="tasks-table" class="table table-bordered table-striped table-hover">
			<thead>
				<tr class="table-header">
					<td><?php echo "Job Id"; ?></td>
					<td><?php echo lang("ctn_847") ?></td>
					<td><?php echo lang("ctn_848") ?></td>
					<td><?php echo lang("ctn_825") ?></td>
					<td><?php echo lang("ctn_849") ?></td>
					<td><?php echo lang("ctn_827") ?></td>
					<td><?php echo lang("ctn_828") ?></td>
				</tr>
			</thead>
			<tbody class="small-text"></tbody>
		</table>
	</div>
</div> -->
<?php endif; ?>

<div class="white-area-content content-separator">
    <div class="db-header clearfix">
        <div class="page-header-simple-title"><?php echo lang("ctn_1373") ?></div>
        <div class="db-header-extra form-inline"></div>
    </div>
	<?php foreach($messages->result() as $r) : ?>
		<div class="media">
		  <div class="media-left">
			 <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
		  </div>
		  <div class="media-body">
		  <div class="pull-right">
		  <a href="<?php echo site_url("projects/delete_message/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_57") ?></a>
		  </div>
			<?php echo $r->message ?>
			<p class="small-text"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
		  </div>
		</div>
		<hr>
	<?php endforeach; ?>
	<div class="align-center"><?php echo $this->pagination->create_links() ?></div>
	<hr>
	<?php echo form_open(site_url("projects/add_message/" . $project->ID), array("class" => "form-horizontal")) ?>
	<div class="form-group">
			<div class="col-md-12 ui-front">
			   <textarea name="message" id="msg-area"></textarea>
			</div>
	</div>
	<p><input type="submit" class="form-control btn btn-primary btn-sm" value="<?php echo lang("ctn_862") ?>" /></p>
	<?php echo form_close(); ?>
	<button style="margin:0 0 0 0;" class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>
	
</div>

</div>
<div class="col-md-4">

<?php if($fields->num_rows() > 0)  : ?>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-simple-title"><?php echo lang("ctn_652"); ?></div>
    <div class="db-header-extra form-inline">

</div>
</div>

<table class="table borderless small-text">
<?php foreach($fields->result() as $r) : ?>
  <?php if($r->type == 1) : ?>
    <tr><td><?php echo $r->name ?><br /><strong><?php echo $r->value ?></strong></td></tr>
  <?php else : ?>
    <tr><td><?php echo $r->name ?> <span class="profile-info-content"><?php echo $r->value ?></span></td></tr>
  <?php endif; ?>
<?php endforeach; ?>
</table>


</div>
<?php endif; ?>

<div class="white-area-content <?php if($fields->num_rows() > 0) : ?>content-separator<?php endif; ?>">
<div class="db-header clearfix">
    <div class="page-header-simple-title"><?php echo lang("ctn_703") ?></div>
    <div class="db-header-extra form-inline"> 

    <?php if( $this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || ($this->common->has_team_permissions(array("admin", "team"), $team_member)) ) : ?>
           <a href="<?php echo site_url("team/index/" . $project->ID) ?>" class="btn btn-round btn-sm" title="<?php echo lang("ctn_910") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-plus"></span></a>
    <?php endif; ?>

</div>
</div>

<table class="table table-bordered table-hover">
<?php foreach($members->result() as $r) : ?>
<tr><td> <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?></td><td><label class="label label-default"><?php echo $r->team_role_name ?></label></td></tr>
<?php endforeach; ?>
</table>
</div>
<!--
<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="page-header-simple-title"><?php echo lang("ctn_1374") ?></div>
    <div class="db-header-extra form-inline"> 

    <?php if( $this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user) || ($this->common->has_team_permissions(array("admin", "file"), $team_member)) ) : ?>
           <a href="<?php echo site_url("files/add_file/" . $project->ID) ?>" class="btn btn-round btn-sm" title="<?php echo lang("ctn_486") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-plus"></span></a>
    <?php endif; ?>

</div>
</div>

<table class="table">
<?php foreach($files->result() as $r) : ?>
<tr><td><a href="<?php echo site_url("files/view_file/" . $r->ID) ?>"><?php echo $r->file_name ?><?php echo $r->extension ?></a></td><td><?php echo $r->file_type ?></td></tr>
<?php endforeach; ?>
</table>

</div>
-->
<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="page-header-simple-title"><?php echo lang("ctn_1375") ?></div>
    <div class="db-header-extra form-inline"> 

</div>
</div>

<table class="table">
<?php foreach($activity->result() as $r) : ?>
<tr><td width="30">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
</td><td><p class="task-blob-title"><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> <?php echo $r->message ?></p>
<p class="task-blob-date"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
</td></tr>
<?php endforeach; ?>
</table>


</div>

</div>
</div>
<script type="text/javascript">
CKEDITOR.replace('msg-area', { height: '100'});
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#tasks-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [4, "asc" ]
        ],
        "columns": [
        null,
		null,
        null,
        null,
        null,
        null,
        null
    ],
        "ajax": {
            url : "<?php echo site_url("tasks/tasks_page/index/" . $project->ID . "/0") ?>",
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
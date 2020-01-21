<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_887") ?></div>
    <div class="db-header-extra form-inline"> 
    <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_844") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("team") ?>"><?php echo lang("ctn_845") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <?php if($r->ID == $projectid) $proj = $r; ?>
      <li><a href="<?php echo site_url("team/".$page."/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
</div>

    <div class="form-group has-feedback no-margin">
<div class="input-group">
<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_354") ?>" id="form-search-input" />
<div class="input-group-btn">
    <input type="hidden" id="search_type" value="0">
        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
        <ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
          <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
          <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_357") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="role-exact"></span> <?php echo lang("ctn_360") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>


    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_910") ?></button>


</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<?php if(isset($projectid) && $projectid > 0 && isset($proj)) : ?>
  <?php $project = $proj; ?>
<p><?php echo lang("ctn_911") ?> <strong><?php echo $project->name ?></strong> <?php if($this->user->info->active_projectid == $projectid) : ?> (<?php echo lang("ctn_912") ?>) <?php endif; ?></p>
<?php endif; ?>

<div class="table-responsive">
<table id="team-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo "Project Role";/* lang("ctn_1508") */ ?></td><td><?php echo lang("ctn_825") ?></td><td><?php echo "Team"; ?></td><td><?php echo lang("ctn_913") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

</div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_910") ?></h4>
      </div>
      <div class="modal-body ui-front">
         <?php echo form_open(site_url("team/add_team_member"), array("class" => "form-horizontal")) ?>          
		   <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Teams" ?></label>
                    <div class="col-md-8 ui-front">
                        <!-- <select multiple="multiple" name="teams[]" id="teams" class="form-control" onChange="getUsers(this.value);"> -->
                        <select multiple="multiple" name="teams[]" id="teams" class="form-control">
                        <?php foreach($teams->result() as $r) : ?>
                        	<option value="<?php echo $r->team_name ?>"><?php echo $r->team_name.' - '.$r->reporting; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
			<?php /* echo $_POST["teamname"];
echo $_GET["teamname"];
echo $_REQUEST["teamname"]; */
/* echo $_GET["teamname"];
echo $_POST["teamname"]; */
/* $get_string = "pg_id=2&parent_id=2&document&video";
parse_str($get_string, $get_array);
print_r($get_array); */

?>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8 ui-front">
                        <select multiple="multiple" name="users[]" id="users" class="form-control">
                        <option value="">Select User(s)</option>
                        </select>
                    </div>
            </div>
			<!-- <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="username" value="" id="username-search">
                    </div>
            </div> -->
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_360") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="roleid" class="form-control">
                        <?php foreach($roles->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <!-- <div class="form-group">
				<label for="reporting" class="col-md-4 label-heading"><?php echo lang("ctn_1576") ?></label>
				<div class="col-md-8 ui-front">
					<select name="reporting" class="form-control">
					<?php foreach($reporting as $r) : ?>
						<option value="<?php echo $r['reporting'] ?>">
							<?php echo $r['reporting'] ?>
						</option>
					<?php endforeach; ?>
					</select>
				</div>
			</div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_910") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#team-table').DataTable({
        "dom" : "B<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        buttons: [
          { "extend": 'copy', "text":'<?php echo lang("ctn_1551") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'csv', "text":'<?php echo lang("ctn_1552") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'excel', "text":'<?php echo lang("ctn_1553") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'pdf', "text":'<?php echo lang("ctn_1554") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'print', "text":'<?php echo lang("ctn_1555") ?>',"className": 'btn btn-default btn-sm' }
        ],
        "order": [
          [2, "asc" ]
        ],
        "columns": [
        null,
        null,
        null,
		null,
        null,
        { "orderable": false },
    ],
        "ajax": {
            url : "<?php echo site_url("team/team_page/" . $page . "/" . $projectid) ?>",
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
        "user-exact",
        "role-exact",
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

	
	/* function getUsers(val) {
		var selectedteams = [];
		$('#teams :selected').each(function(i, selected) {
			selectedteams[i] = $(selected).val();
		});
		var hitURL = "<?php echo base_url(); ?>team";
				
		$.ajax({
		type : "GET",
		url : hitURL,
		data : { teamname : selectedteams },
		success: function(data){
			$("#users").html(data);
		}
		});
	} */
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(document).on("change", "select#teams", function(){
			var team_name = $(this).val(); 
			var hitURL = "<?php echo base_url(); ?>team/ajaxcall";
			var csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
			var csrf_hash = "<?php $this->security->get_csrf_hash() ?>";
           // console.log(team_name);
			/* alert(csrf_hash); */
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { teamname : team_name }
			}).done(function(data){
				/* console.log(data);
				alert(data); */  
				$('select#users').html('');
                for(var i=0;i<data.length;i++)
                {
				/* alert(data[i]); */
				if(data[i].user_role != 15){
                    $("<option />").val(data[i].username)
                                   .text(data[i].username)
                                   .appendTo($('select#users'));
				}
				else{
					$("<option id='teammanager' />").val(data[i].username)
                                   .text(data[i].username)
                                   .appendTo($('select#users'));
				}
                }
				$('select#users option').prop('selected', true);
				jQuery(document).on("change", "select#users", function(){
					$('select#users option#teammanager').prop('selected', true);
					$("#select#users option#teammanager:selected").attr('disabled','disabled');
				});	
			});		
	});	
});	
</script>	

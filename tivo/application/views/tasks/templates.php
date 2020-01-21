<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_1483") ?></div>
    <div class="db-header-extra form-inline"> 
    <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_844") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("tasks/" . $page) ?>"><?php echo lang("ctn_845") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <li><a href="<?php echo site_url("tasks/".$page. "/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_823") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>
    <?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), 
      $this->user)) : ?>
      <a href="<?php echo site_url("tasks/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_821") ?></a>
    <?php endif; ?>
</div>
</div>

<div class="btn-group" role="group" aria-label="...">
  <a href="<?php echo site_url("tasks/".$page."/0/0") ?>" class="btn btn-default btn-sm"><?php echo lang("ctn_846") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/1") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_830") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/2") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_831") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/3") ?>" class="btn btn-success btn-sm"><?php echo lang("ctn_832") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/4") ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_833") ?></a>
  <a href="<?php echo site_url("tasks/".$page."/" . $projectid . "/5") ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_834") ?></a>
</div>

<hr>

<div class="table-responsive">
<table id="tasks-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_847") ?></td><td><?php echo lang("ctn_848") ?></td><td><?php echo lang("ctn_849") ?></td><td><?php echo lang("ctn_1503") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="createModalContent">
      
    </div>
  </div>
</div>
<script type="text/javascript">
  function create_task(taskid) 
  {
    $.ajax({
      url: global_base_url + 'tasks/create_template/' + taskid,
      type: 'GET',
      success: function(msg) {
        $('#createModalContent').html(msg);
        $('#createModal').modal('show');
      }
    })  
  }

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
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("tasks/templates_page/" . $projectid . "/" . $u_status) ?>",
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
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-time"></span> <?php echo lang("ctn_987") ?></div>
    <div class="db-header-extra"> 
          <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_448") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("time/" . $page . "/-1") ?>"><?php echo lang("ctn_449") ?> <?php if($projectid == -1) : ?><span class="glyphicon glyphicon-ok small-text"></span> <?php endif; ?></a></li>
      <?php $active_project = lang("ctn_449"); ?>
      <?php if($projectid == -1) {
        $active_project = lang("ctn_449");
      }
      ?>
    <?php foreach($projects->result() as $r) : ?>
      <li><a href="<?php echo site_url("time/".$page."/" . $r->ID) ?>"><?php echo $r->name ?> <?php if($r->ID == $projectid) : ?><span class="glyphicon glyphicon-ok small-text"></span> <?php endif; ?></a></li>
      <?php if($r->ID == $projectid) {
        $active_project = $r->name;
      }
      ?>
    <?php endforeach; ?>
  </ul>
</div>
</div>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_996") ?></button>
</div>
</div>

<p><?php echo lang("ctn_1177") ?> <strong><?php echo $active_project ?></strong></p>

<hr>


<div class="table-responsive">
<table id="time-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_528") ?></td><td><?php echo lang("ctn_814") ?></td><td><?php echo lang("ctn_825") ?></td><td><?php echo lang("ctn_1507") ?></td><td>Status</td><td><?php echo lang("ctn_52") ?></td></tr>
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
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_996") ?></h4>
      </div>
      <div class="modal-body ui-front">
         <?php echo form_open(site_url("time/add_timer"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_988") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="note" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control" id="project-select">
                        <option value="0"><?php echo lang("ctn_990") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->user->info->active_projectid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Title"; ?></label>
                    <div class="col-md-8 ui-front" id="task-area">
                        <select name="taskid" class="form-control"><option value="0"><?php echo lang("ctn_990") ?></option>

                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_991") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="rate" value="<?php echo number_format($this->user->info->time_rate,2,'.','') ?>">
                        <span class="help-text"><?php echo lang("ctn_992") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_545"); ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="status" class="form-control">
                          <option value="0"><?php echo lang("ctn_548"); ?></option>
                          <option value="1"><?php echo lang("ctn_549"); ?></option>
                        </select>
                    </div>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1006") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#time-table').DataTable({
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
        ],
        "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("time/time_page/" . $page . "/" . $projectid) ?>",
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
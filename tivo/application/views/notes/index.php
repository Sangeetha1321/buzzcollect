<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo lang("ctn_750") ?></div>
    <div class="db-header-extra form-inline"> 
    <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_448") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("notes/" . $page) ?>"><?php echo lang("ctn_449") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <li><a href="<?php echo site_url("notes/".$page."/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
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
        <ul class="dropdown-menu small-text" style="min-width: 90px !important;">
          <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
          <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_357") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_514") ?></a></li>
          <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="project-exact"></span> <?php echo lang("ctn_753") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_755") ?></button>
</div>
</div>

<div class="table-responsive">
<table id="notes-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td width="40"><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_514") ?></td><td><?php echo lang("ctn_753") ?></td><td><?php echo lang("ctn_756") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

</div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_755") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("notes/add_note"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_514") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="title">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_490") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="type" class="form-control" id="note-type">
                          <option value="0"><?php echo lang("ctn_752") ?></option>
                          <option value="1"><?php echo lang("ctn_1473") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group" id="note-editor">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_752") ?></label>
                    <div class="col-md-8 ui-front">
                       <textarea name="note" id="note-area"></textarea>
                    </div>
            </div>
            <div class="form-group" id="note-todo" style="display: none;">
                  <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1474") ?></label>
                    <div class="col-md-8 ui-front">
                      <div id="todolist">
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #1 ..." name="todo_1" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #2 ..." name="todo_2" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #3 ..." name="todo_3" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #4 ..." name="todo_4" class="form-control"></p>
                       <p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #5 ..." name="todo_5" class="form-control"></p>
                     </div>
                     <input type="hidden" name="todo_count" value="5" id="todo_count">
                     <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1476") ?>" id="addAnother">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_753") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->user->info->active_projectid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1477") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="personal" value="1">
                        <span class="help-block"><?php echo lang("ctn_1478") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1479") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="pinned" value="1">
                        <span class="help-block"><?php echo lang("ctn_1480") ?></span>
                    </div>
            </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_755") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

  $('#note-type').change(function() {
    var val = $("#note-type").val();
    if(val == 1) {
      $('#note-editor').css("display", "none");
      $('#note-todo').css("display", "block");
    } else {
      $('#note-editor').css("display", "block");
      $('#note-todo').css("display", "none");
    }
  });

  $('#addAnother').click(function() {
    var count = $('#todo_count').val();
    count++;
    var html = '<p><input type="text" placeholder="<?php echo lang("ctn_1481") ?> #'+count+' ..." class="form-control" name="todo_'+count+'"></p>';
    $('#todolist').append(html);
    $('#todo_count').val(count);
  });

  CKEDITOR.replace('note-area', { height: '100'});

   var st = $('#search_type').val();
    var table = $('#notes-table').DataTable({
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
          [3, "desc" ]
        ],
        "columns": [
        { "orderable": false },
        null,
        null,
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("notes/notes_page_pro/" . $page . "/" . $projectid) ?>",
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
        "title-exact",
        "project-exact"
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
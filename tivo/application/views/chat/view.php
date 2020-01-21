<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-comment"></span> <?php echo lang("ctn_1265") ?></div>
    <div class="db-header-extra form-inline"> 
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
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="message-exact"></span> <?php echo lang("ctn_12") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>


  <?php  if($chat->userid == $this->user->info->ID || ($this->common->has_permissions(array("admin"), $this->user)) ) : ?>
<a href="<?php echo site_url("chat/edit_chat/" . $chat->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_1326") ?></a>
<?php endif; ?>

</div>
</div>

<p><?php echo lang("ctn_1327") ?> <strong><?php echo $chat->title ?></strong></p>

<div class="table-responsive">
<table id="chat-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_12") ?></td><td><?php echo lang("ctn_921") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody class="small-text">
</tbody>
</table>
</div>

<hr>

<h4><?php echo lang("ctn_1328") ?></h4>

<?php if($chat->userid == $this->user->info->ID || $this->common->has_permissions(array("admin"), $this->user)) : ?>
<?php echo form_open(site_url("chat/add_user/" . $chat->ID), array("class" => "form-inline")) ?>
<div class="form-group">
    <input type="text" class="form-control" id="username-search" name="username" placeholder="<?php echo lang("ctn_1315") ?>">
  </div>
  <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1316") ?>">
<?php echo form_close() ?>
<hr>
<?php endif; ?>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($users->result() as $r) : ?>
<tr><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?></td><td>
<?php if($chat->userid == $this->user->info->ID || $this->common->has_permissions(array("admin"), $this->user)) : ?>
<a href="<?php echo site_url("chat/remove_from_chat/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_1317") ?></a>
<?php endif; ?></td></tr>
<?php endforeach; ?>
</table>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#chat-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [ 2, "desc"]
        ],
        "columns": [
        { "orderable": false },
        { "orderable": false },
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("chat/chat_page/" . $chat->ID) ?>",
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
        "message-exact"
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
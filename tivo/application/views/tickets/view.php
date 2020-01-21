<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_922") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<?php $prioritys = array(1 => "<span class='label label-info'>".lang("ctn_931")."</span>", 2 => "<span class='label label-primary'>".lang("ctn_932")."</span>", 3=> "<span class='label label-warning'>".lang("ctn_933")."</span>", 4 => "<span class='label label-danger'>".lang("ctn_934")."</span>"); ?>
<?php $statuses = array(1=>lang("ctn_927"), 2 => lang("ctn_928"), 3 => lang("ctn_929")) ?>
<?php 
if($ticket->status == 1) {
$statusbtn = "btn-info";
} elseif($ticket->status == 2) {
$statusbtn = "btn-primary";
} elseif($ticket->status == 3) {
$statusbtn = "btn-danger";
} 
?>
<div class="panel panel-default">
<div class="panel-body">

<div class="row">
<div class="col-md-8">
<h3><?php echo $ticket->title ?></h3>
<p><?php echo $ticket->body ?></p>
<?php if(!empty($ticket->notes) && $this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
<hr>
<p><?php echo lang("ctn_937") ?></p>
<p><i><?php echo $ticket->notes ?></i></p>
<?php endif; ?>
<?php if(!$this->settings->info->disable_ticket_upload && $files->num_rows() > 0) : ?>
    <hr>
    <h4><?php echo lang("ctn_976") ?></h4>
    <div class="form-group">
            <div class="col-md-12">
                <table class="table table-bordered">
                <?php foreach($files->result() as $r) : ?>
                    <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td>
                          <?php if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                                  <a href="<?php echo site_url("tickets/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php endif; ?></td></tr>
                <?php endforeach; ?>
                </table>
            </div>
    </div>
<?php endif; ?>
</div>
<div class="col-md-4 ticket-border-left">

<div class="media" style="overflow: visible !important;">
  <div class="media-left">
      <?php echo $this->common->get_user_display(array("username" => $ticket->client_username, "avatar" => $ticket->client_avatar, "online_timestamp" => $ticket->client_online_timestamp)) ?>
  </div>
  <div class="media-body" style="overflow: visible !important;">
    <p><?php echo lang("ctn_977") ?> <a href="<?php echo site_url("profile/" . $ticket->client_username) ?>"><?php echo $ticket->client_username ?></a></p>
    <p><button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><?php echo lang("ctn_978") ?></button></p>
    <div class="collapse" id="collapseExample">
      <div class=" small-text">
        <table class="table">
        <tr><td><?php echo lang("ctn_25") ?></td><td><?php echo $ticket->client_username ?></td></tr>
        <tr><td><?php echo lang("ctn_24") ?></td><td><?php echo $ticket->client_email ?></td></tr>
        <tr><td><?php echo lang("ctn_81") ?></td><td><?php echo $ticket->first_name ?> <?php echo $ticket->last_name ?></td></tr>
        <tr><td><?php echo lang("ctn_429") ?></td><td><?php echo $ticket->address_1 ?></td></tr>
        <tr><td><?php echo lang("ctn_430") ?></td><td><?php echo $ticket->address_2 ?></td></tr>
        <tr><td><?php echo lang("ctn_431") ?></td><td><?php echo $ticket->city ?></td></tr>
        <tr><td><?php echo lang("ctn_432") ?></td><td><?php echo $ticket->state ?></td></tr>
        <tr><td><?php echo lang("ctn_433") ?></td><td><?php echo $ticket->zipcode ?></td></tr>
        <tr><td><?php echo lang("ctn_434") ?></td><td><?php echo $ticket->country ?></td></tr>
        </table>
      </div>
    </div>
    <p><?php echo lang("ctn_979") ?> <?php echo date($this->settings->info->date_format, $ticket->timestamp); ?></p>
    <p><?php echo lang("ctn_980") ?> <?php echo $prioritys[$ticket->priority] ?></p>
    <p><?php echo lang("ctn_981") ?> <?php echo date($this->settings->info->date_format, $ticket->last_reply_timestamp) ?> <?php if(isset($ticket->lr_username)) : ?>by <a href="<?php echo site_url("profile/" . $ticket->lr_username) ?>"><?php echo $ticket->lr_username ?></a><?php endif; ?> </p>

    <p><?php echo lang("ctn_982") ?> <?php if(isset($ticket->assigned_username)) : ?><a href="<?php echo site_url("profile/" . $ticket->assigned_username) ?>"><?php echo $ticket->assigned_username ?></a><?php else : ?><?php echo lang("ctn_983") ?><?php endif; ?></p>
    <p><?php echo lang("ctn_984") ?> <a href=""><?php echo $ticket->catname ?></a></p>
    <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
    <p><div class="dropdown ui-front">
    <button id="status-button-update" type="button" class="btn btn-default btn-xs"> <span class="glyphicon glyphicon-refresh spin"></span></button>
  <button class="btn <?php echo $statusbtn ?> btn-xs dropdown-toggle" type="button" id="status-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo $statuses[$ticket->status] ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="javascript: void(0)" onclick="changeStatus(<?php echo $ticket->ID ?>,1);"><?php echo lang("ctn_927") ?></a></li>
    <li><a href="javascript: void(0)" onclick="changeStatus(<?php echo $ticket->ID ?>,2);"><?php echo lang("ctn_928") ?></a></li>
    <li><a href="javascript: void(0)" onclick="changeStatus(<?php echo $ticket->ID ?>,3);"><?php echo lang("ctn_929") ?></a></li>
  </ul>
</div></p>
    <hr>
    <p><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assignModal">Assign</button> <a href="<?php echo site_url("tickets/edit_ticket/" . $ticket->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("tickets/delete_ticket/" . $ticket->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_508") ?>')" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></p>
  <?php endif; ?>
  </div>
</div>

</div>
</div>

</div>
</div>

</div>

<?php foreach($replies->result() as $r) : ?>
	<div class="white-area-content content-separator">
  <?php if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
  	<div class="ticket-reply-options">
  	<a href="<?php echo site_url("tickets/edit_ticket_reply/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a>
  	<a href="<?php echo site_url("tickets/delete_ticket_reply/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_508") ?>')" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a>
  	</div>
  <?php endif; ?>
<p><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?> <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></p>
<p><?php echo $r->body ?></p>
<p class="small-text"><?php echo date($this->settings->info->date_format, $r->timestamp); ?></p>
<?php if($r->files && !$this->settings->info->disable_ticket_upload ) : ?>
<?php $files = $this->tickets_model->get_reply_files($r->ID); ?>
<hr>
                <div class="form-group clearfix">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_974") ?></label>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                            <?php foreach($files->result() as $r) : ?>
                                <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td>
                                  <?php if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                                  <a href="<?php echo site_url("tickets/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php endif; ?>
                                </td></tr>
                            <?php endforeach; ?>
                            </table>
                        </div>
                </div>
<?php endif; ?>
<hr>
</div>
<?php endforeach; ?>

<div class="white-area-content content-separator">
<h4><?php echo lang("ctn_985") ?></h4>
<?php echo form_open_multipart(site_url("tickets/ticket_reply/" . $ticket->ID), array("class" => "form-horizontal")) ?>
<p><textarea name="body" id="ticket-body"></textarea></p>
<?php if(!$this->settings->info->disable_ticket_upload) : ?>
                <hr>
                <h4><?php echo lang("ctn_939") ?></h4>
                <input type="hidden" name="file_count" value="1" id="file_count">
                <div id="file_block">
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_940") ?></label>
                        <div class="col-md-8">
                            <input type="file" name="user_file_1" class="form-control">
                        </div>
                </div>
                </div>
                <input type="button" name="s" value="<?php echo lang("ctn_941") ?>" class="btn btn-info btn-xs" onclick="add_file()">
                <hr>
            <?php endif; ?>
<p><input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_986") ?>"></p>
<?php echo form_close() ?>
</div>

<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-pushpin"></span> Assign User</h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("tickets/assign_user_pro/" . $ticket->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="username" id="username-search" value="">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="Assign">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
CKEDITOR.replace('ticket-body', { height: '100'});

function add_file() 
{
    var count = $('#file_count').val();
    count++;
    var html = '<div class="form-group">'+
                    '<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_940") ?></label>'+
                    '<div class="col-md-8">'+
                        '<input type="file" name="user_file_'+count+'" class="form-control">'+
                    '</div>'+
            '</div>';
    $('#file_block').append(html);
    $('#file_count').val(count);
}
</script>
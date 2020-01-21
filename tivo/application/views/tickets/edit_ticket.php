<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_922") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("tickets/edit_ticket_pro/" . $ticket->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_923") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="title" value="<?php echo $ticket->title ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_924") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="username_client" placeholder="<?php echo lang("ctn_592") ?>" id="username-search" value="<?php echo $ticket->client_username ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_925") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="username_assigned" placeholder="<?php echo lang("ctn_592") ?>" value="<?php if(isset($ticket->assigned_username)) : ?><?php echo $ticket->assigned_username ?><?php endif; ?>" id="username-search2">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_926") ?></label>
                    <div class="col-md-8">
                        <select name="status" class="form-control">
                        <option value="1"><?php echo lang("ctn_927") ?></option>
                        <option value="2" <?php if($ticket->status == 2) echo "selected" ?>><?php echo lang("ctn_928") ?></option>
                        <option value="3" <?php if($ticket->status == 3) echo "selected" ?>><?php echo lang("ctn_929") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_930") ?></label>
                    <div class="col-md-8">
                        <select name="priority" class="form-control">
                        <option value="1" <?php if($ticket->priority == 1) echo "selected" ?>><?php echo lang("ctn_931") ?></option>
                        <option value="2" <?php if($ticket->priority == 2) echo "selected" ?>><?php echo lang("ctn_932") ?></option>
                        <option value="3" <?php if($ticket->priority == 3) echo "selected" ?>><?php echo lang("ctn_933") ?></option>
                        <option value="4" <?php if($ticket->priority == 4) echo "selected" ?>><?php echo lang("ctn_934") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_935") ?></label>
                    <div class="col-md-8">
                        <select name="departmentid" class="form-control">
                        <?php foreach($departments->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>" <?php if($ticket->departmentid == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_936") ?></label>
                    <div class="col-md-8">
                        <textarea name="body" id="ticket-body"><?php echo $ticket->body ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_937") ?></label>
                    <div class="col-md-8">
                        <textarea name="notes" id="ticket-body-notes"><?php echo $ticket->notes ?></textarea>
                        <span class="help-block"><?php echo lang("ctn_938") ?></span>
                    </div>
            </div>
            <hr>
            <?php foreach($fields->result() as $r) : ?>
              <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo $r->name ?> <?php if($r->required) echo "(*Required)"; ?></label>
                    <div class="col-md-8">
                        <?php if($r->type == 0) : ?>
                          <input type="text" name="field_id_<?php echo $r->ID ?>" value="<?php if(isset($r->value)) echo $r->value ?>" class="form-control">
                        <?php elseif($r->type == 1) : ?>
                          <textarea name="field_id_<?php echo $r->ID ?>"><?php if(isset($r->value)) echo $r->value ?></textarea>
                        <?php elseif($r->type == 2) : ?>
                          <select name="field_id_<?php echo $r->ID ?>" class="form-control">
                          <?php $options = explode(",", $r->select_options) ?>
                          <?php foreach($options as $v) : ?>
                            <option value="<?php echo $v ?>" <?php if(isset($r->value) && $v == $r->value) echo "selected" ?>><?php echo $v ?></option>
                          <?php endforeach; ?>
                          </select>
                        <?php elseif($r->type == 3) : ?>
                          <input type="checkbox" name="field_id_<?php echo $r->ID ?>" value="1" <?php if(isset($r->value) && $r->value == 1) echo "checked" ?>> Select
                        <?php endif; ?>
                        <?php if(!empty($r->help_text)) : ?>
                            <span class="help-text"><?php echo $r->help_text ?></span>
                          <?php endif; ?>
                    </div>
              </div>
            <?php endforeach; ?>
            <?php if(!$this->settings->info->disable_ticket_upload) : ?>
                <hr>
                <h4><?php echo lang("ctn_939") ?></h4>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_974") ?></label>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                            <?php foreach($files->result() as $r) : ?>
                                <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td><a href="<?php echo site_url("tickets/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
                            <?php endforeach; ?>
                            </table>
                        </div>
                </div>
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
            

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_975") ?>">
        <?php echo form_close() ?>
</div>
</div>


</div>
<script type="text/javascript">
CKEDITOR.replace('ticket-body', { height: '100'});
CKEDITOR.replace('ticket-body-notes', { height: '100'});

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
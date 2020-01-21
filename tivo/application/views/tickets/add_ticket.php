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
<?php echo form_open_multipart(site_url("tickets/add_ticket_pro/"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_923") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="title" value="">
                    </div>
            </div>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                    <div class="form-group">
                            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_924") ?></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="username_client" value="" placeholder="<?php echo lang("ctn_592") ?>" id="username-search">
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_925") ?></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="username_assigned" value="" placeholder="<?php echo lang("ctn_592") ?>" value="<?php echo $this->user->info->username ?>" id="username-search2">
                            </div>
                    </div>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_926") ?></label>
                        <div class="col-md-8">
                            <select name="status" class="form-control">
                            <option value="1"><?php echo lang("ctn_927") ?></option>
                            <option value="2"><?php echo lang("ctn_928") ?></option>
                            <option value="3"><?php echo lang("ctn_929") ?></option>
                            </select>
                        </div>
                </div>
            <?php endif; ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_930") ?></label>
                    <div class="col-md-8">
                        <select name="priority" class="form-control">
                        <option value="1"><?php echo lang("ctn_931") ?></option>
                        <option value="2"><?php echo lang("ctn_932") ?></option>
                        <option value="3"><?php echo lang("ctn_933") ?></option>
                        <option value="4"><?php echo lang("ctn_934") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_935") ?></label>
                    <div class="col-md-8">
                        <select name="departmentid" class="form-control">
                        <?php foreach($departments->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_936") ?></label>
                    <div class="col-md-8">
                        <textarea name="body" id="ticket-body"></textarea>
                    </div>
            </div>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_937") ?></label>
                        <div class="col-md-8">
                            <textarea name="notes" id="ticket-body-notes"></textarea>
                            <span class="help-block"><?php echo lang("ctn_938") ?></span>
                        </div>
                </div>
            <?php endif; ?>
            <hr>
            <?php foreach($fields->result() as $r) : ?>
              <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo $r->name ?> <?php if($r->required) echo "(*Required)"; ?></label>
                    <div class="col-md-8">
                        <?php if($r->type == 0) : ?>
                          <input type="text" name="field_id_<?php echo $r->ID ?>" class="form-control">
                        <?php elseif($r->type == 1) : ?>
                          <textarea name="field_id_<?php echo $r->ID ?>"></textarea>
                        <?php elseif($r->type == 2) : ?>
                          <select name="field_id_<?php echo $r->ID ?>" class="form-control">
                          <?php $options = explode(",", $r->select_options) ?>
                          <?php foreach($options as $v) : ?>
                            <option value="<?php echo $v ?>"><?php echo $v ?></option>
                          <?php endforeach; ?>
                          </select>
                        <?php elseif($r->type == 3) : ?>
                          <input type="checkbox" name="field_id_<?php echo $r->ID ?>" value="1"> Select
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
            

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_942") ?>">
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
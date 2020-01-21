<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_728") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("leads/edit_lead/" . $lead->ID) ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("leads/delete_lead/" . $lead->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
</div>
</div>

</div>

<div class="row">
<div class="col-md-8">

<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="page-header-title"><?php echo $lead->title ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="form-horizontal">
<?php foreach($fields->result() as $r) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?></label>
        <div class="col-md-8">
            <?php if(isset($r->answer)) : ?><?php echo $r->answer ?><?php else : ?>N/A<?php endif; ?>
        </div>
</div>
<?php endforeach; ?>
</div>


</div>

<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="page-header-title"><?php echo lang("ctn_750") ?></div>
    <div class="db-header-extra"> 
</div>
</div>


<?php foreach($notes->result() as $r) : ?>
<div class="media">
  <div class="media-left">
     <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
  </div>
  <div class="media-body">
  <div class="pull-right">
  <a href="<?php echo site_url("leads/delete_lead_note/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_57") ?></a>
  </div>
    <?php echo $r->note ?>
    <p class="small-text"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
  </div>
</div>
<hr>
<?php endforeach; ?>

<div class="align-center"><?php echo $this->pagination->create_links() ?></div>


<hr>

<?php echo form_open(site_url("leads/add_lead_note/" . $lead->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                <div class="col-md-12 ui-front">
                   <textarea name="note" id="msg-area"></textarea>
                </div>
        </div>
<p><input type="submit" class="form-control btn btn-primary btn-sm" value="<?php echo lang("ctn_755") ?>" /></p>
<?php echo form_close(); ?>

</div>


</div>
<div class="col-md-4">

<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="page-header-title"><?php echo lang("ctn_1359") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<table class="table borderless small-text">
<tr><td><?php echo lang("ctn_545") ?></td><td><?php if(isset($lead->status)) : ?><?php echo $lead->status ?><?php endif; ?></td></tr>
<tr><td><?php echo lang("ctn_1349") ?></td><td><?php if(isset($lead->source)) : ?><?php echo $lead->source ?><?php endif; ?></td></tr>
<tr><td><?php echo lang("ctn_925") ?></td><td><?php if(isset($lead->assigned_username)) : ?><a href="<?php echo site_url("profile/" . $lead->assigned_username) ?>"><?php echo $lead->assigned_username ?></a><?php endif; ?></td></tr>
<tr><td><?php echo lang("ctn_293") ?></td><td><?php echo date($this->settings->info->date_format, $lead->timestamp) ?></td></tr>
<tr><td><?php echo lang("ctn_813") ?></td><td><?php echo $lead->IP ?></td></tr>
</table>

</div>

<div class="white-area-content content-separator">
<div class="db-header clearfix">
    <div class="page-header-title"><?php echo lang("ctn_1350") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1360") ?>" data-toggle="modal" data-target="#myModal" />
</div>
</div>

<table class="table borderless small-text">
<tr><td><?php echo lang("ctn_357") ?></td><td><?php if(isset($lead->client_username)) : ?><a href="<?php echo site_url("profile/" . $lead->client_username) ?>"><?php echo $lead->client_username ?></a><?php else : ?><?php echo lang("ctn_819") ?>: <?php echo $lead->first_name ?> <?php echo $lead->last_name ?><?php endif; ?></td></tr>
<tr><td><?php echo lang("ctn_24") ?></td><td><?php echo $lead->email ?></td></tr>
<tr><td><?php echo lang("ctn_429") ?></td><td><?php echo $lead->address_1 ?></td></tr>
<tr><td><?php echo lang("ctn_430") ?></td><td><?php echo $lead->address_2 ?></td></tr>
<tr><td><?php echo lang("ctn_431") ?></td><td><?php echo $lead->city ?></td></tr>
<tr><td><?php echo lang("ctn_432") ?></td><td><?php echo $lead->state ?></td></tr>
<tr><td><?php echo lang("ctn_433") ?></td><td><?php echo $lead->zipcode ?></td></tr>
<tr><td><?php echo lang("ctn_434") ?></td><td><?php echo $lead->country ?></td></tr>
<?php foreach($cfields->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><?php echo $r->value ?></td></tr>
<?php endforeach; ?>
</table>

<?php if($lead->user_added) : ?>
<p class="small-text"><strong><?php echo lang("ctn_1361") ?></strong></p>
<?php endif; ?>

</div>

</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_1360") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("leads/add_client_pro/" . $lead->ID), array("class" => "form-horizontal")) ?>
         <p><?php echo lang("ctn_1362") ?></p>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_24") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="email" value="<?php echo $lead->email ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="username" value="<?php echo $lead->first_name ?>_<?php echo $lead->last_name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_87") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="password" class="form-control" name="password">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_88") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="password" class="form-control" name="password2">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_218") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="first_name" value="<?php echo $lead->first_name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_219") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="last_name" value="<?php echo $lead->last_name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_429") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="address_1" value="<?php echo $lead->address_1 ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_430") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="address_2" value="<?php echo $lead->address_2 ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_431") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="city" value="<?php echo $lead->city ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_432") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="state" value="<?php echo $lead->state ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_433") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="zipcode" value="<?php echo $lead->zipcode ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_434") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="country" value="<?php echo $lead->country ?>">
                    </div>
            </div>
            <?php foreach($cfields->result() as $r) : ?>
            <div class="form-group">

                <label for="name-in" class="col-sm-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                <div class="col-sm-8">
                    <?php if($r->type == 0) : ?>
                        <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
                    <?php elseif($r->type == 1) : ?>
                        <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
                    <?php elseif($r->type == 2) : ?>
                         <?php $options = explode(",", $r->options); ?>
                         <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 3) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 4) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        <?php if(count($options) > 0) : ?>
                            <select name="cf_<?php echo $r->ID ?>" class="form-control">
                            <?php foreach($options as $k=>$v) : ?>
                            <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="help-text"><?php echo $r->help_text ?></span>
                </div>
        </div>
    <?php endforeach; ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1360") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('msg-area', { height: '100'});
});
</script>
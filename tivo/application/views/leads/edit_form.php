<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_791") ?></div>
    <div class="db-header-extra"> 
</div>
</div>


<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("leads/edit_form_pro/" . $form->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_792") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="title" value="<?php echo $form->title ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_793") ?></label>
        <div class="col-md-8 ui-front">
           <textarea name="welcome" id="note-area"><?php echo $form->welcome ?></textarea>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
        <div class="col-md-8">
            <input type="text" name="username" class="form-control" id="username-search" <?php if(isset($form->assigned_username)) : ?> value="<?php echo $form->assigned_username ?>" <?php endif; ?> placeholder="<?php echo lang("ctn_592") ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1344") ?></label>
        <div class="col-md-8">
            <input type="checkbox" name="collect_user" value="1" <?php if($form->collect_user) echo"checked" ?>>
            <span class="help-block"><?php echo lang("ctn_1345") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1346") ?></label>
        <div class="col-md-8">
           <select name="statusid" class="form-control">
           <?php foreach($statuses->result() as $r) : ?>
            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $form->default_statusid) echo "selected" ?>><?php echo $r->name ?></option>
           <?php endforeach; ?>
           </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1347") ?></label>
        <div class="col-md-8">
           <select name="sourceid" class="form-control">
           <?php foreach($sources->result() as $r) : ?>
            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $form->default_sourceid) echo "selected" ?>><?php echo $r->name ?></option>
           <?php endforeach; ?>
           </select>
        </div>
</div>
<hr>
<h3><?php echo lang("ctn_795") ?></h3>
<div id="fields">
<?php $count = 0; ?>
<?php foreach($fields->result() as $r) : ?>
<?php $count++; ?>
<div id="field-area-<?php echo $count ?>" class="field-area">
<input type="hidden" name="form_field_id_<?php echo $count ?>" value="<?php echo $r->ID ?>" />
<div class="form-group">
        <div class="col-md-4 ui-front">
           <input type="text" name="field_title_<?php echo $count ?>" class="form-control" placeholder="<?php echo lang("ctn_796") ?>" value="<?php echo $r->title ?>">
        </div>
        <div class="col-md-4 ui-front">
           <select name="field_type_<?php echo $count ?>" id="field_type_<?php echo $count ?>" class="form-control field_type">
           <option value="1"><?php echo lang("ctn_797") ?></option>
           <option value="2" <?php if($r->type == 2) echo "selected" ?>><?php echo lang("ctn_798") ?></option>
           <option value="3" <?php if($r->type == 3) echo "selected" ?>><?php echo lang("ctn_799") ?></option>
           <option value="4" <?php if($r->type == 4) echo "selected" ?>><?php echo lang("ctn_800") ?></option>
           <option value="5" <?php if($r->type == 5) echo "selected" ?>><?php echo lang("ctn_801") ?></option>
           </select>
        </div>
        <div class="col-md-4 ui-front">
           <select name="field_require_<?php echo $count ?>" class="form-control">
           <option value="0"><?php echo lang("ctn_802") ?></option>
           <option value="1" <?php if($r->required == 1) echo "selected" ?>><?php echo lang("ctn_803") ?></option>
           </select>
        </div>
</div>
<div class="form-group">
        <div class="col-md-4 ui-front">
           <input type="text" name="field_desc_<?php echo $count ?>" class="form-control" placeholder="<?php echo lang("ctn_804") ?>" value="<?php echo $r->description ?>">
        </div>
        <div class="col-md-4 ui-front">
           <input type="text" name="field_options_<?php echo $count ?>" id="field_options_<?php echo $count ?>" class="form-control <?php if($r->type <3) : ?>field_options<?php endif; ?>" placeholder="<?php echo lang("ctn_805") ?>" value="<?php echo $r->options ?>">
        </div>
        <div class="col-md-4 ui-front">
          <button type="button" onclick="remove_field(<?php echo $count ?>)" class="btn btn-danger"><?php echo lang("ctn_806") ?></button>
        </div>
</div>
</div>
<?php endforeach; ?>
</div>
<input type="hidden" name="field_count" value="<?php echo $count ?>" id="field_count" />
<p style="margin-top: 10px;"><input type="button" class="btn btn-success btn-sm" value="<?php echo lang("ctn_807") ?>" onclick="add_form_field()" /></p>

<hr>



<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_809") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('note-area', { height: '100'});
});
</script>
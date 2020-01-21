<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-th-list"></span> <?php echo lang("ctn_1215") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("services/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1221") ?></a>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("services/edit_service_pro/" . $service->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_792") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="title" value="<?php echo $service->title ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1222") ?></label>
        <div class="col-md-8 ui-front">
           <textarea name="welcome" id="note-area"><?php echo $service->welcome ?></textarea>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
        <div class="col-md-8">
            <input type="text" name="username" class="form-control" id="username-search" placeholder="<?php echo lang("ctn_592") ?>" value="<?php if(isset($service->username)) : ?><?php echo $service->username ?><?php endif; ?>">
            <span class="help-block"><?php echo lang("ctn_1223") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_261") ?></label>
        <div class="col-md-8">
            <input type="text" name="cost" class="form-control" value="<?php echo $service->cost ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_598") ?></label>
        <div class="col-md-8">
            <select name="currencyid" class="form-control">
            <?php foreach($currencies->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>" <?php if($r->ID == $service->currencyid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1224") ?></label>
        <div class="col-md-8">
            <input type="checkbox" name="invoice" class="form-control" value="1" <?php if($service->invoice) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_1225") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1329") ?></label>
        <div class="col-md-8">
            <select name="paying_accountid" class="form-control">
            <option vale="0"><?php echo lang("ctn_46") ?></option>
            <?php foreach($accounts->result() as $r) : ?>
                <option value="<?php echo $r->ID ?>" <?php if($r->ID == $service->paying_accountid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
            <span class="help-block"><?php echo lang("ctn_1330") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1226") ?></label>
        <div class="col-md-8 ui-front">
           <textarea name="invoice_message" id="invoice-area"><?php echo $service->invoice_message ?></textarea>
           <span class="help-block"><?php echo lang("ctn_1228") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1229") ?></label>
        <div class="col-md-8 ui-front">
          <input type="checkbox" name="require_login" value="1" <?php if($service->require_login) echo "checked" ?>> <?php echo lang("ctn_53") ?>
          <span class="help-block"><?php echo lang("ctn_1230") ?></span>
        </div>
</div>
<hr>
<h3><?php echo lang("ctn_795") ?></h3>
<p><?php echo lang("ctn_1231") ?></p>
<p><?php echo lang("ctn_1232") ?></p>
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
          <input type="text" name="field_cost_<?php echo $count ?>" id="field_cost_<?php echo $count ?>" class="form-control" placeholder="+<?php echo lang("ctn_1233") ?> ... 0.00" value="<?php echo $r->cost ?>">
        </div>
</div>
<button type="button" onclick="remove_field(<?php echo $count ?>)" class="btn btn-danger"><?php echo lang("ctn_806") ?></button>
</div>
<?php endforeach; ?>
</div>
<input type="hidden" name="field_count" value="<?php echo $count ?>" id="field_count" />

<p style="margin-top: 10px;"><input type="button" class="btn btn-success btn-sm" value="<?php echo lang("ctn_807") ?>" onclick="add_form_field()" /></p>

<hr>



<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1234") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('note-area', { height: '100'});
CKEDITOR.replace('invoice-area', { height: '100'});
});
</script>
<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-th-list"></span> <?php echo lang("ctn_1215") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("services/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1221") ?></a>
</div>
</div>


<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("services/add_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_792") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="title" value="">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1222") ?></label>
        <div class="col-md-8 ui-front">
           <textarea name="welcome" id="note-area"></textarea>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
        <div class="col-md-8">
            <input type="text" name="username" class="form-control" id="username-search" placeholder="<?php echo lang("ctn_592") ?>">
            <span class="help-block"><?php echo lang("ctn_1223") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_261") ?></label>
        <div class="col-md-8">
            <input type="text" name="cost" class="form-control" value="0.00">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_598") ?></label>
        <div class="col-md-8">
            <select name="currencyid" class="form-control">
            <?php foreach($currencies->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1224") ?></label>
        <div class="col-md-8">
            <input type="checkbox" name="invoice" class="form-control" value="1" checked>
            <span class="help-block"><?php echo lang("ctn_1225") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1329") ?></label>
        <div class="col-md-8">
            <select name="paying_accountid" class="form-control">
              <option vale="0"><?php echo lang("ctn_46") ?></option>
            <?php foreach($accounts->result() as $r) : ?>
                <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
            <span class="help-block"><?php echo lang("ctn_1330") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1226") ?></label>
        <div class="col-md-8 ui-front">
           <textarea name="invoice_message" id="invoice-area"><?php echo lang("ctn_1227") ?></textarea>
           <span class="help-block"><?php echo lang("ctn_1228") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1229") ?></label>
        <div class="col-md-8 ui-front">
          <input type="checkbox" name="require_login" value="1"> <?php echo lang("ctn_53") ?>
          <span class="help-block"><?php echo lang("ctn_1230") ?></span>
        </div>
</div>
<hr>
<h3><?php echo lang("ctn_795") ?></h3>
<p><?php echo lang("ctn_1231") ?></p>
<p><?php echo lang("ctn_1232") ?></p>
<div id="fields">

<div id="field-area-1" class="field-area">
<div class="form-group">
        <div class="col-md-4 ui-front">
           <input type="text" name="field_title_1" class="form-control" placeholder="<?php echo lang("ctn_796") ?>">
        </div>
        <div class="col-md-4 ui-front">
           <select name="field_type_1" id="field_type_1" class="form-control field_type">
           <option value="1"><?php echo lang("ctn_797") ?></option>
           <option value="2"><?php echo lang("ctn_798") ?></option>
           <option value="3"><?php echo lang("ctn_799") ?></option>
           <option value="4"><?php echo lang("ctn_800") ?></option>
           <option value="5"><?php echo lang("ctn_801") ?></option>
           </select>
        </div>
        <div class="col-md-4 ui-front">
           <select name="field_require_1" class="form-control">
           <option value="0"><?php echo lang("ctn_802") ?></option>
           <option value="1"><?php echo lang("ctn_803") ?></option>
           </select>
        </div>
</div>
<div class="form-group">
        <div class="col-md-4 ui-front">
           <input type="text" name="field_desc_1" class="form-control" placeholder="<?php echo lang("ctn_804") ?>">
        </div>
        <div class="col-md-4 ui-front">
           <input type="text" name="field_options_1" id="field_options_1" class="form-control field_options" placeholder="<?php echo lang("ctn_805") ?>">
        </div>
        <div class="col-md-4 ui-front">
          <input type="text" name="field_cost_1" id="field_cost_1" class="form-control" placeholder="+<?php echo lang("ctn_1233") ?> ... 0.00">
        </div>
</div>
<button type="button" onclick="remove_field(1)" class="btn btn-danger btn-sm"><?php echo lang("ctn_806") ?></button>
</div>

</div>
<input type="hidden" name="field_count" value="1" id="field_count" />
<p style="margin-top: 10px;"><input type="button" class="btn btn-success btn-sm" value="<?php echo lang("ctn_807") ?>" onclick="add_form_field()" /></p>

<hr>



<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1221") ?>">
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
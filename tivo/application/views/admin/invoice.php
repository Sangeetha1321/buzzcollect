<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_424") ?></li>
</ol>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("admin/invoice_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
    <label for="image-in" class="col-sm-2 control-label"><?php echo lang("ctn_425") ?></label>
    <div class="col-sm-10">
        <?php if(!empty($invoice->image)) : ?>
            <p><img src='<?php echo base_url().$this->settings->info->upload_path_relative . "/" . $invoice->image ?>'></p>
        <?php endif; ?>
        <input type="file" name="userfile" size="20" />
        <span class="help-block"><?php echo lang("ctn_426") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_1179") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_paypal" value="1" <?php if($invoice->enable_paypal == 1) echo "checked" ?> />
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_1180") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_stripe"  value="1" <?php if($invoice->enable_stripe == 1) echo "checked" ?> />
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_1182") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_checkout2" value="1" <?php if($invoice->enable_checkout2 == 1) echo "checked" ?> />
    </div>
</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>
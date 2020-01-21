<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_341") ?></li>
</ol>

<p><?php echo lang("ctn_342") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("admin/section_settings_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_343") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_calendar" value="1" <?php if($this->settings->info->enable_calendar) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_344") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_tasks" value="1" <?php if($this->settings->info->enable_tasks) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_345") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_files" value="1" <?php if($this->settings->info->enable_files) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_346") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_team" value="1" <?php if($this->settings->info->enable_team) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_347") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_time" value="1" <?php if($this->settings->info->enable_time) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_348") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_tickets" value="1" <?php if($this->settings->info->enable_tickets) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_349") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_finance" value="1" <?php if($this->settings->info->enable_finance) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_350") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_invoices" value="1" <?php if($this->settings->info->enable_invoices) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_351") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_notes" value="1" <?php if($this->settings->info->enable_notes) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_352") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_leads" value="1" <?php if($this->settings->info->enable_leads) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1191") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_services" value="1" <?php if($this->settings->info->enable_services) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1141") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_reports" value="1" <?php if($this->settings->info->enable_reports) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1526") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_docs" value="1" <?php if($this->settings->info->enable_docs) echo "checked" ?> /> <?php echo lang("ctn_353") ?>
    </div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>
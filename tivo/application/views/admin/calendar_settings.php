<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_436") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_436") ?></li>
</ol>

<p><?php echo lang("ctn_437") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/calendar_settings_pro"), array("class" => "form-horizontal")) ?>

<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_438") ?></label>
    <div class="col-sm-10">
    	<select name="type" class="form-control">
      <option value="0"><?php echo lang("ctn_439") ?></option>
      <option value="1" <?php if($this->settings->info->calendar_type == 1) echo "selected" ?>><?php echo lang("ctn_440") ?></option>
      </select>
    	<span class="help-block"><?php echo lang("ctn_441") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_442") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="google_calendar_id" value="<?php echo $this->settings->info->google_calendar_id ?>">
      <span class="help-block"><?php echo lang("ctn_443") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_123") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="google_client_id" value="<?php echo $this->settings->info->google_client_id ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_124") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="google_client_secret" value="<?php echo $this->settings->info->google_client_secret ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_444") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="google_calendar_api_key" value="<?php echo $this->settings->info->google_calendar_api_key ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_445") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="calendar_timezone" value="<?php echo $this->settings->info->calendar_timezone ?>">
      <span class="help-block"><?php echo lang("ctn_446") ?> <a href="http://php.net/manual/en/timezones.php">Timezones</a></span>
    </div>
</div>


<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>

</div>
</div>
</div>
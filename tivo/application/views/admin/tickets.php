<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_412") ?></li>
</ol>

<p><?php echo lang("ctn_413") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/ticket_settings_pro"), array("class" => "form-horizontal")) ?>

<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_414") ?></label>
    <div class="col-sm-10">
    	<select name="protocol">
        <option value="1" <?php if($this->settings->info->protocol) echo "selected" ?>>IMap</option>
      </select>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_415") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="protocol_path" value="<?php echo $this->settings->info->protocol_path ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_416") ?></label>
    <div class="col-sm-10">
      <select name="protocol_ssl" class="form-control">
      <option value="0"><?php echo lang("ctn_417") ?></option>
      <option value="1" <?php if($this->settings->info->protocol_ssl) echo "selected" ?>><?php echo lang("ctn_418") ?></option>
      </select>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_419") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="protocol_email" value="<?php echo $this->settings->info->protocol_email ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_420") ?></label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="name-in" name="protocol_password" value="<?php echo $this->settings->info->protocol_password ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_421") ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name-in" name="ticket_title" value="<?php echo $this->settings->info->ticket_title ?>">
    </div>
</div>
<h3><?php echo lang("ctn_1293") ?></h3>
<p><?php echo lang("ctn_1294") ?></p>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_1295") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="imap_ticket_string" value="<?php echo $this->settings->info->imap_ticket_string ?>">
      <span class="help-block"><?php echo lang("ctn_1296") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_1297") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="imap_reply_string" value="<?php echo $this->settings->info->imap_reply_string ?>">
      <span class="help-block"><?php echo lang("ctn_1298") ?></span>
    </div>
</div>
<h3>Crons</h3>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_422") ?></label>
    <div class="col-sm-10">
      wget <?php echo site_url("cron/ticket_replies") ?>
    </div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_423") ?>" />
<?php echo form_close() ?>

</div>
</div>
</div>
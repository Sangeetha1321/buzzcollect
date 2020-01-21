<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_1267") ?></li>
</ol>

<p><?php echo lang("ctn_1268") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/chat_settings_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1269") ?></label>
    <div class="col-sm-10">
       <input type="checkbox" name="enable_chat" value="1" <?php if($this->settings->info->enable_chat) echo "checked" ?>>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1270") ?></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="dpname-in" name="chat_update" value="<?php echo $this->settings->info->chat_update ?>" >
        <span class="help-block"><?php echo lang("ctn_1271") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1415") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="chat_online_client" value="1" <?php if($this->settings->info->chat_online_client) echo "checked" ?>>
        <span class="help-block"><?php echo lang("ctn_1416") ?></span>
    </div>
</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
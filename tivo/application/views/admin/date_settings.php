<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_1134") ?></li>
</ol>

<p><?php echo lang("ctn_1135") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/date_settings_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1136") ?></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="dpname-in" name="date_format" placeholder="" value="<?php echo $this->settings->info->date_format ?>" ><br />
        <span class="help-block"><?php echo lang("ctn_104") ?> <a href="http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a></span>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1137") ?></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="dpname-in" name="date_picker_format" placeholder="" value="<?php echo $this->settings->info->date_picker_format ?>" ><br />
        <span class="help-block"><?php echo lang("ctn_1138") ?>: <a href="http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a></span>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1139") ?></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="dpname-in" name="calendar_picker_format" placeholder="" value="<?php echo $this->settings->info->calendar_picker_format ?>" ><br />
        <span class="help-block"><?php echo lang("ctn_1140") ?> <a href="http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a></span>
    </div>
</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
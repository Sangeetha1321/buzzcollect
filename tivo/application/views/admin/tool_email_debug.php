<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/tools") ?>"><?php echo lang("ctn_1192") ?></a></li>
  <li class="active"><?php echo lang("ctn_1198") ?></li>
</ol>
<!--
<p><?php echo lang("ctn_1200") ?> <a href="https://www.codeigniter.com/user_guide/libraries/email.html">https://www.codeigniter.com/user_guide/libraries/email.html</a></p>

<hr>
<p><?php echo lang("ctn_1201") ?></p>
-->
<?php if(!empty($debug)) : ?>
	<hr>
<strong><?php echo lang("ctn_1195") ?></strong><br />
<pre><?php echo $debug ?></pre>
<hr>
<?php endif; ?>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/tool_email_debug"), array("class" => "form-horizontal")) ?>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_1202") ?></label>
    <div class="col-sm-10">
        <input type="text" name="email" class="form-control" placeholder="example@example.com">
    </div>
</div>
<input type="submit" class="btn btn-primary btn-xs form-control" value="<?php echo lang("ctn_1203") ?>">
<?php echo form_close() ?>

</div>
</div>
</div>
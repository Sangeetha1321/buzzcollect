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
  <li class="active"><?php echo lang("ctn_1193") ?></li>
</ol>

<p><?php echo lang("ctn_1194") ?></p>

<hr>


<?php if(!empty($debug)) : ?>
	<hr>
<strong><?php echo lang("ctn_1195") ?></strong><br />
<pre><?php echo $debug ?></pre>
<hr>
<?php endif; ?>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/tool_noti_sync"), array("class" => "form-horizontal")) ?>
<input type="submit" name="s" class="btn btn-primary btn-xs form-control" value="<?php echo lang("ctn_1196") ?>">
<?php echo form_close() ?>

</div>
</div>
</div>
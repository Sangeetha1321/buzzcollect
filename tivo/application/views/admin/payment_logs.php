<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_288") ?></li>
</ol>

<p><?php echo lang("ctn_290") ?></p>

<hr>

<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_25") ?></td><td><?php echo lang("ctn_24") ?></td><td><?php echo lang("ctn_292") ?></td><td><?php echo lang("ctn_293") ?></td><td><?php echo lang("ctn_1114") ?></td></tr>
<?php foreach($logs->result() as $r) : ?>
	<?php $types = array(0 => "PayPal", 1 => "Stripe", 2 => "2Checkout", 3 => lang("ctn_1420"), 4 => lang("ctn_1421"), 5 => lang("ctn_1422")); ?>
	<?php
	if(isset($types[$r->processor])) {
		$type = $types[$r->processor];
	} else {
		$type = $r->processor;
	}
	?>
<tr><td><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></td><td><?php echo $r->email ?></td><td><?php echo number_format($r->amount, 2) ?></td><td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td><td><?php echo $type ?></td></tr>
<?php endforeach; ?>
</table>

<?php echo $this->pagination->create_links() ?>

</div>
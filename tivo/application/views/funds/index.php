<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_250") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_250") ?></li>
</ol>

<p><?php echo lang("ctn_247") ?></p>

<p><?php echo lang("ctn_248") ?>: <?php echo number_format($this->user->info->points,2) ?></p>

<hr>

<p class="align-center"><img src="<?php echo base_url() ?>/images/paypal.png"></p>
		<center>
		<form method="post" action="https://www.paypal.com/cgi-bin/webscr" accept-charset="UTF-8" class="form-inline">
				<input type="hidden" name="charset" value="utf-8" />
				<input type="hidden" name="cmd" value="_xclick" />
				<input type="hidden" name="item_number" value="funds01" />
				<input type="hidden" name="item_name" value="<?php echo $this->settings->info->site_name ?> <?php echo lang("ctn_250") ?>" />
				<input type="hidden" name="quantity" value="1" />
				<input type="hidden" name="custom" value="<?php echo $this->user->info->ID ?>" />
				<input type="hidden" name="receiver_email" value="<?php echo $this->settings->info->paypal_email ?>" />
				<input type="hidden" name="business" value="<?php echo $this->settings->info->paypal_email ?>" />
				<input type="hidden" name="notify_url" value="<?php echo site_url("IPN/process2") ?>" />
				<input type="hidden" name="return" value="<?php echo site_url("funds") ?>" />
				<input type="hidden" name="cancel_return" value="<?php echo site_url("funds") ?>" />
				<input type="hidden" name="no_shipping" value="1" />
				<input type="hidden" name="currency_code" value="<?php echo $this->settings->info->paypal_currency ?>"> 
				<input type="hidden" name="no_note" value="1" />
		<div class="input-group col-md-4">
		    <select name="amount" class="form-control">
		    <option value="5.00"><?php echo $this->settings->info->payment_symbol ?>5.00</option>
		    <option value="10.00"><?php echo $this->settings->info->payment_symbol ?>10.00</option>
		    <option value="30.00"><?php echo $this->settings->info->payment_symbol ?>30.00</option>
		    <option value="100.00"><?php echo $this->settings->info->payment_symbol ?>100.00</option>
		    </select>
		  </div>
		  <button type="submit" class="btn btn-primary"><?php echo lang("ctn_249") ?></button>
		<?php echo form_close() ?>
		</center>
		

</div>
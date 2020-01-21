<script type="text/javascript">
$(document).ready(function() {
$('.dropdown-menu .stop_timer_button').click(function(e) {
	    e.stopPropagation();
	});
});
</script>
<?php if($timers->num_rows() > 0) : ?>
<?php foreach($timers->result() as $r) : ?>
	<?php
		if($r->end_time > 0) {
			$time_data = $this->common->convert_time_raw($r->end_time - $r->start_time);
			$time = $this->common->get_time_string($time_data);
			$rate = $time_data['days'] * (24 * $r->rate);
			$rate += $time_data['hours'] * $r->rate;
			$rate += $time_data['mins']  * ($r->rate/60);
			$rate += $time_data['secs'] * (($r->rate/60)/60);
			$rate = round($rate,2);
		} else {
			$time_data = $this->common->convert_simple_time($r->start_time);
			$time = $this->common->get_time_string($time_data);
			$rate = $time_data['days'] * (24 * $r->rate);
			$rate += $time_data['hours'] * $r->rate;
			$rate += $time_data['mins']  * ($r->rate/60);
			$rate += $time_data['secs'] * (($r->rate/60)/60);
			$rate = round($rate,2);
		}
	?>
<div class="notification-box-bit animation-fade clearfix">
  <div class="notification-icon-bit">
    <span class="glyphicon glyphicon-time" title="<?php echo $r->note ?>"></span>
  </div>
  <div class="projects-text-bit click">
    <?php echo $time ?>
    <p class="notification-datestamp"><?php echo $this->settings->info->fp_currency_symbol ?><?php echo number_format($rate,2) ?></p>
    <p><input type="button" onclick="stop_timer(<?php echo $r->ID ?>)" class="btn btn-danger btn-xs stop_timer_button" value="<?php echo lang("ctn_529") ?>"></p>
  </div>
</div>
<?php endforeach; ?>
<?php else : ?>
	<div class="notification-box-bit animation-fade clearfix">
<p><?php echo lang("ctn_530") ?></p>
</div>
<?php endif; ?>
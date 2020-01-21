<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-th-list"></span> <?php echo lang("ctn_1235") ?></div>
    <div class="db-header-extra form-inline"> 

</div>
</div>


<p><?php echo lang("ctn_1236") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($order->total_cost,2) ?></strong></p>



  <div class="panel panel-default">
<div class="panel-heading"><?php echo $order->title ?></div>
<div class="panel-body">
<?php echo form_open(site_url("services/edit_order_pro/" . $order->ID), array("class" => "form-horizontal")) ?>


<hr>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1237") ?></label>
        <div class="col-md-8 ui-front">
            <?php if(isset($order->invoiceid) && $order->invoiceid > 0) : ?>
            	<a href="<?php echo site_url("invoices/view/" . $order->invoiceid . "/" . $order->invoice_hash) ?>"><?php echo lang("ctn_665") ?></a>
            <?php endif; ?>
            <p><?php echo lang("ctn_1238") ?>: <input type="checkbox" name="invoice_generate" value="1"></p>
            <?php if(isset($order->invoiceid) && $order->invoiceid > 0) : ?>
	            <p><?php echo lang("ctn_1239") ?>: <input type="checkbox" name="invoice_delete" value="1"></p>
	        <?php endif; ?>
	        <p><?php echo lang("ctn_1240") ?>: <input type="checkbox" name="email_remind" value="1"></p>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1241") ?></label>
        <div class="col-md-8 ui-front">
            <strong><?php echo $currency->symbol ?><?php echo number_format($order->cost,2) ?></strong>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1242") ?></label>
        <div class="col-md-8 ui-front">
            <input type="email" class="form-control" name="email" value="<?php echo $order->email ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="name" value="<?php echo $order->name ?>">
        </div>
</div>
<?php foreach($fields->result() as $r) : ?>
<input type="hidden" id="field_price_<?php echo $r->ID ?>" value="<?php echo $r->cost ?>">
<?php if($r->type == 1) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="field_id_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>" value="<?php if(isset($r->answer)) : ?><?php echo $r->answer ?><?php endif; ?>">
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 2) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <textarea name="field_id_<?php echo $r->ID ?>" rows="8" class="form-control" id="field_id_<?php echo $r->ID ?>"><?php if(isset($r->answer)) : ?><?php echo $r->answer ?><?php endif; ?></textarea>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 3) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php $answers = explode(", ", $r->answer); ?>
            <?php if(count($options) > 0) : ?>
                <?php foreach($options as $k=>$v) : ?>
                <div class="form-group"><input type="checkbox" name="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" id="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>" <?php if(in_array($v,$answers)) : ?>checked<?php endif; ?>> <?php echo $v ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 4) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php $answers = explode(", ", $r->answer); ?>
            <?php if(count($options) > 0) : ?>
                <?php foreach($options as $k=>$v) : ?>
                <div class="form-group"><input type="radio" name="field_id_<?php echo $r->ID ?>" value="<?php echo $k ?>" id="field_radio_<?php echo $r->ID ?>_<?php echo $k ?>" class="field_radio_<?php echo $r->ID ?>" <?php if(in_array($v,$answers)) : ?>checked<?php endif; ?>> <?php echo $v ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 5) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <select name="field_id_<?php echo $r->ID ?>" class="form-control" id="field_id_<?php echo $r->ID ?>">
                <option value="-1"><?php echo lang("ctn_46") ?></option>
                <?php foreach($options as $k=>$v) : ?>
                <option value="<?php echo $k ?>" <?php if($v == $r->answer) echo "selected" ?>><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<hr>
<p>* = <?php echo lang("ctn_803") ?>.</p>




<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1244") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>
<?php echo form_open(site_url("invoices/edit_invoice_payment_pro/" . $payment->ID), array("class" => "form-horizontal", "ID" => "testtttttt")) ?>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1460") ?></h4>
      </div>
      <div class="modal-body">
         
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_292") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="amount" placeholder="0.00" value="<?php echo $payment->amount ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1458") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="type" class="form-control">
                        	<option value="0">PayPal</option>
                        	<option value="1" <?php if($payment->processor == 1) echo "selected" ?>>Stripe</option>
                        	<option value="2" <?php if($payment->processor == 2) echo "selected" ?>>2Checkout</option>
                        	<option value="3" <?php if($payment->processor == 3) echo "selected" ?>><?php echo lang("ctn_1420") ?></option>
                        	<option value="4" <?php if($payment->processor == 4) echo "selected" ?>><?php echo lang("ctn_1421") ?></option>
                        	<option value="5" <?php if($payment->processor == 5) echo "selected" ?>><?php echo lang("ctn_1422") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1459") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="email" value="<?php echo $payment->email ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_293") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control datepicker" name="date" value="<?php echo date($this->settings->info->date_picker_format,$payment->timestamp) ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_515") ?></label>
                    <div class="col-md-8 ui-front">
                        <textarea name="notes" rows="4" class="form-control"><?php echo $payment->notes ?></textarea>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_13") ?>">
        
      </div>
      <?php echo form_close() ?>
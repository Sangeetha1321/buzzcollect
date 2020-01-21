<form method="post" action="https://www.paypal.com/cgi-bin/webscr" accept-charset="UTF-8" class="form-horizontal">
     <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1431") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" name="amount" class="form-control" value="<?php echo $amount_left ?>">
                    </div>
            </div>
                    <input type="hidden" name="charset" value="utf-8" />
                    <input type="hidden" name="cmd" value="_xclick" />
                    <input type="hidden" name="item_number" value="<?php echo $invoice->ID ?>" />
                    <input type="hidden" name="item_name" value="<?php echo lang("ctn_1237") ?> #<?php echo $invoice->invoice_id ?>: <?php echo $invoice->client_username ?> <?php echo $invoice->client_first_name ?> <?php echo $invoice->client_last_name ?>" />
                    <input type="hidden" name="quantity" value="1" />
                    <input type="hidden" name="custom" value="<?php echo $invoice->hash ?>" />
                    <input type="hidden" name="business" value="<?php echo $invoice->paypal_email ?>" />
                    <input type="hidden" name="currency_code" value="<?php echo $invoice->code ?>" />
                    <input type="hidden" name="notify_url" value="<?php echo site_url("IPN/process") ?>" />
                    <input type="hidden" name="return" value="<?php echo site_url("invoices/view/" . $invoice->ID . "/" . $invoice->hash) ?>" />
                    <input type="hidden" name="cancel_return" value="<?php echo site_url("invoices/view/" . $invoice->ID . "/" . $invoice->hash) ?>" />
                    <input type="hidden" name="no_shipping" value="1" />
                    <input type="hidden" name="no_note" value="1" />
                    <input type="submit" name="button" value="<?php echo lang("ctn_662") ?>" class="btn btn-success" />
                </form>
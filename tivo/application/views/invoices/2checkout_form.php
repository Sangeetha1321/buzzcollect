             <h4>2CHECKOUT</h4>
            <img src="https://www.2checkout.com/upload/images/paymentlogoshorizontal.png" alt="2Checkout.com is a worldwide leader in online payment services" />
                <form action='https://sandbox.2checkout.com/checkout/purchase' method='post'>
                    <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1431") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" name="li_0_price" class="form-control" value="<?php echo $amount_left ?>">
                    </div>
            </div>
                <input type='hidden' name='sid' value='<?php echo $invoice->checkout2_account_number ?>' />
                <input type='hidden' name='mode' value='2CO' />
                <input type='hidden' name='li_0_type' value='product' />
                <input type='hidden' name='li_0_name' value='<?php echo lang("ctn_1237") ?> #<?php echo $invoice->invoice_id ?>: <?php echo $invoice->client_username ?> <?php echo $invoice->client_first_name ?> <?php echo $invoice->client_last_name ?>' />
                <input type='hidden' name='x_receipt_link_url' value="<?php echo site_url("IPN/checkout2") ?>">
                <input type="hidden" name="titan_invoice_hash" value="<?php echo $invoice->hash ?>" />
                <input type="hidden" name="titan_invoiceid" value="<?php echo $invoice->ID ?>" />
                <input type="hidden" name="currency_code" value="<?php echo $invoice->code ?>" />
                <input name='submit' type='submit' value='<?php echo lang("ctn_1214") ?>' />
                </form>
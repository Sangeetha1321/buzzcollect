<form action="<?php echo site_url("IPN/stripe/" . $invoice->ID . "/" . $invoice->hash) ?>" method="post">
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                          data-key="<?php echo $invoice->stripe_publishable_key ?>"
                          data-description="Invoice #<?php echo $invoice->invoice_id ?> @ <?php echo $this->settings->info->site_name ?>"
                          data-amount="<?php echo str_replace(".","", $amount) ?>"
                          data-currency="<?php echo $invoice->code ?>"
                          data-locale="auto" data-label="Pay With Stripe"></script>
                          <input type="hidden" name="charge_amount" value="<?php echo $amount ?>">
                </form>
                <hr>
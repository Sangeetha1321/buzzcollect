<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1431") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" name="amount" id="amount" class="form-control" value="<?php echo $amount_left ?>">
                    </div>
            </div>
            <input type="button" name="button" onclick="load_stripe_payment()" value="Use STRIPE" class="btn btn-primary" />


            <script type="text/javascript">
function load_stripe_payment() 
{
    var amount = $('#amount').val();
    $.ajax({
        url: global_base_url + "invoices/get_payment_gateway/<?php echo $invoice->ID ?>/<?php echo $invoice->hash ?>",
        data: { 
            type : 1,
            amount: amount
        },
        success: function(msg) {
            $('#ajax-body').html(msg);
        }
    });
}
</script>
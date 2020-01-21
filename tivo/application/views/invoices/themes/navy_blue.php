<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">

         <!-- Styles -->
        <link href="<?php echo base_url();?>styles/invoice_navy_blue.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

        <!-- SCRIPTS -->
        <script type="text/javascript">
        var global_base_url = "<?php echo site_url("/") ?>";
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        

        <!-- CODE INCLUDES -->
    </head>
    <body>

        <div class="container">
        <div class="row">
        <div class="col-md-12 document">

        <div class="row">
            <div class="col-md-12 invoice-header invoice-header-text">
                            <span class="invoice-header-logo"><?php echo $this->settings->info->site_name ?></span>
                            <div class="pull-right">
                                <?php echo strtoupper(lang("ctn_1237")) ?> / <?php echo $invoice->invoice_id ?>
                            </div>
        

            </div>
    </div>

    <div class="invoice-inner-top">
        <div class="row">
            <div class="col-md-12">
                <div class="invoice-inner">
                    <p><strong><?php echo lang("ctn_1465") ?>:</strong></p>
                    <?php if(!empty($invoice->client_first_name)) : ?><?php echo $invoice->client_first_name ?> <?php echo $invoice->client_last_name ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_address_1)) : ?><?php echo $invoice->client_address_1 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_address_2)) : ?><?php echo $invoice->client_address_2 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_city)) : ?><?php echo $invoice->client_city ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_state)) : ?><?php echo $invoice->client_state ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_zipcode)) : ?><?php echo $invoice->client_zipcode ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_country)) : ?><?php echo $invoice->client_country ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_email)) : ?><br /><?php echo $invoice->client_email ?><?php endif; ?>
            </p>
                </div>
                <div class="invoice-inner">
                    <p><strong><?php echo lang("ctn_1466") ?>:</strong></p>
        <?php if(!empty($invoice->first_name)) : ?><?php echo $invoice->first_name ?> <?php echo $invoice->last_name ?><br /><?php endif; ?>
        <?php if(!empty($invoice->address_line_1)) : ?><?php echo $invoice->address_line_1 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->address_line_2)) : ?><?php echo $invoice->address_line_2 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->city)) : ?><?php echo $invoice->city ?><br /><?php endif; ?>
        <?php if(!empty($invoice->state)) : ?><?php echo $invoice->state ?><br /><?php endif; ?>
        <?php if(!empty($invoice->zipcode)) : ?><?php echo $invoice->zipcode ?><br /><?php endif; ?>
        <?php if(!empty($invoice->country)) : ?><?php echo $invoice->country ?><br /><?php endif; ?>
        <?php if(!empty($invoice->email)) : ?><br /><?php echo $invoice->email ?><br /><?php endif; ?>
            </p>
                </div>
                <div class="invoice-inner-details">
                    <strong><?php echo $invoice->title ?></strong><br />
                    <?php echo lang("ctn_1467") ?> <?php echo date($this->settings->info->date_format, $invoice->timestamp) ?><br /><br />
                    <?php echo lang("ctn_545") ?>: <?php 
                    if($invoice->status == 1) {
                      $status = "<span class='label label-danger'>".lang("ctn_595")."</span>";
                  } elseif($invoice->status == 2) {
                      $status = "<span class='label label-success'>".lang("ctn_596")."</span>";
                  } elseif($invoice->status == 3) {
                      $status = "<span class='label label-default'>".lang("ctn_597")."</span>";
                  } elseif($invoice->status == 4) {
                      $status = "<span class='label label-warning'>".lang("ctn_1430")."</span>";
                  }
                    echo $status;
                  ?><br />
                  Due: <strong><?php echo date($this->settings->info->date_format, $invoice->due_date) ?></strong>
                    <?php if($invoice->due_date < time() && $invoice->status == 1) {
                        echo"<span class='overdue'>".lang("ctn_654")."</span>";
                    }
                    ?>

                    <table class="table"><tr><td>
           <?php if($invoice->status == 1 || $invoice->status == 4) : ?>
                <input type="button" class="btn btn-primary" value="<?php echo lang("ctn_1463") ?>" data-toggle="modal" data-target="#payModal">
           
          
        <?php elseif($invoice->status == 2) : ?>
            <h2 class="paid"><?php echo lang("ctn_651") ?></h2>
        <?php endif; ?>
    </td></tr></table>
                </div>

            </div>
        </div>
    </div>

    <?php if(!empty($invoice->notes)) : ?>
        <div class="container invoice-inner-top">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $invoice->notes ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="invoice-inner-top">
    <div class="row">
        <div class="col-md-12">
        <table class="table">
            <thead><tr class="table-heading"><th><?php echo lang("ctn_616") ?></th><th class="center-cell"><?php echo lang("ctn_617") ?></th><th class="center-cell"><?php echo lang("ctn_618") ?></th><th class="center-cell"><?php echo lang("ctn_619") ?></th></tr></thead>
            <?php $sub_total = 0; ?>
            <?php foreach($items->result() as $r) : ?>
                <?php $total = number_format($r->quantity*$r->amount,2);
                $sub_total += $r->amount*$r->quantity; ?>
                <tr class="noborder"><td><strong><?php echo $r->name ?></strong><br /><?php echo $r->description ?></td><td class="center-cell"><?php echo $r->quantity ?></td><td class="center-cell"><?php echo $invoice->symbol ?><?php echo $r->amount ?></td><td class="center-cell"><?php echo $invoice->symbol ?><?php echo $total ?></td></tr>
            <?php endforeach; ?>

            <tr class="warning align-right"><td colspan="4" class="sub-total-cell"><p><?php echo lang("ctn_625") ?>: <?php echo number_format($sub_total,2) ?></p>
            <?php $total = $sub_total; ?>
            <?php if(!empty($invoice->tax_name_1)) : ?>
            <?php
            $tax_addon = abs($sub_total/100*$invoice->tax_rate_1);
            $total += $tax_addon;
            ?>
            <p><?php echo lang("ctn_658") ?> (<?php echo $invoice->tax_name_1 ?>) @ <?php echo $invoice->tax_rate_1 ?>% : <?php echo number_format($tax_addon,2) ?></p>
            <?php endif; ?>
            <?php if(!empty($invoice->tax_name_2)) : ?>
            <?php
            $tax_addon = abs($sub_total/100*$invoice->tax_rate_2);
            $total += $tax_addon;
            ?>
            <p><?php echo lang("ctn_658") ?> (<?php echo $invoice->tax_name_2 ?>) @ <?php echo $invoice->tax_rate_2 ?>% : <?php echo number_format($tax_addon,2) ?></p>
            <?php endif; ?>
            <p><b><?php echo lang("ctn_628") ?>: <?php echo $invoice->symbol ?><?php echo number_format($total,2) ?></b></p>
            <?php if($payments_total > 0 && $invoice->status != 2) : ?>
                <br /><br /><b><?php echo lang("ctn_1166") ?></b>: -<?php echo $invoice->symbol ?><?php echo number_format($payments_total,2) ?><br />
                <?php $total = $total - $payments_total; ?>
                <b><?php echo lang("ctn_1456") ?></b>: <?php echo $invoice->symbol ?><?php echo number_format($total,2) ?>
            <?php endif; ?>
            </td></tr>
        </table>
        </div></div>

         <?php if($payments->num_rows() > 0) : ?>
            <?php $types = array(0 => "PayPal", 1 => "Stripe", 2 => "2Checkout", 3 => lang("ctn_1420"), 4 => lang("ctn_1421"), 5 => lang("ctn_1422")); ?>

        <h2><?php echo lang("ctn_1453") ?></h2>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr class="table-header"><th><?php echo lang("ctn_292") ?></th><th><?php echo lang("ctn_490") ?></th><th><?php echo lang("ctn_561") ?></th><th><?php echo lang("ctn_1455") ?></th></tr>
            </thead>
            <tbody>
                <?php foreach($payments->result() as $r) : ?>
                    <?php
    if(isset($types[$r->processor])) {
        $type = $types[$r->processor];
    } else {
        $type = $r->processor;
    }
    ?>
            <tr><td class="td-nopadding"><?php echo $invoice->symbol ?><?php echo $r->amount ?></td><td class="td-nopadding"><?php echo $type ?></td><td class="td-nopadding"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td><td class="td-nopadding"><?php echo $r->email ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <hr>

        <?php if(!empty($invoice->term_notes)) : ?>
            <div class="row">
            <div class="col-md-12">
            <p class="small-text"><i><?php echo $invoice->term_notes ?></i></p>
            </div>
            </div>
        <?php endif; ?>

        <a href="javascript:window.print()"><?php echo lang("ctn_664") ?></a>

        </div>
        </div></div>

    </div>
</div></div>


  <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_1463") ?></h4>
      </div>
      <div class="modal-body" id="ajax-body">
        <div class="form-horizontal">
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1464") ?> ...</label>
                    <div class="col-md-8 ui-front">
                        <select name="type" class="form-control" id="payment-type">
                            <?php if($paypal != null) : ?><option value="0">PayPal</option><?php endif; ?>
                            <?php if($stripe != null) : ?><option value="1">Stripe</option><?php endif; ?>
                            <?php if($checkout2 != null) : ?><option value="2">2Checkout</option><?php endif; ?>
                        </select>
                    </div>
            </div>

            <input type="button" class="btn btn-primary" value="Select" onclick="select_payment_method()">
      </div>
  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function select_payment_method() 
{
    var type = $('#payment-type').val();
    $.ajax({
        url: global_base_url + "invoices/get_payment_gateway/<?php echo $invoice->ID ?>/<?php echo $invoice->hash ?>",
        data: { 
            type : type
        },
        success: function(msg) {
            $('#ajax-body').html(msg);
        }
    });
}
</script>

    </body>
</html>
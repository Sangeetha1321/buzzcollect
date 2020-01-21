<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <link href="<?php echo base_url();?>styles/invoice_default_pdf.css" rel="stylesheet" type="text/css">
               

        <!-- CODE INCLUDES -->
    </head>
    <body>
    <div class="document">
    <div class="wrapper">
    <div class="part-1">
        <div class="pull-left"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $settings->image ?>" width="250">
    </div>
    </div>
    <div class="part-2-right">
    <table class="table"><tr>
        <td>
            <?php if($invoice->status == 1) : ?>
        <?php elseif($invoice->status == 2) : ?>
            <h2 class="paid"><?php echo lang("ctn_651") ?></h2>
        <?php endif; ?>
        </td>
        <td>
         <p class="small-text">
        <?php if(!empty($invoice->first_name)) : ?><strong><?php echo $invoice->first_name ?> <?php echo $invoice->last_name ?></strong><br /><?php endif; ?>
        <?php if(!empty($invoice->address_1)) : ?><?php echo $invoice->address_1 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->address_2)) : ?><?php echo $invoice->address_2 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->city)) : ?><?php echo $invoice->city ?><br /><?php endif; ?>
        <?php if(!empty($invoice->state)) : ?><?php echo $invoice->state ?><br /><?php endif; ?>
        <?php if(!empty($invoice->zipcode)) : ?><?php echo $invoice->zipcode ?><br /><?php endif; ?>
        <?php if(!empty($invoice->country)) : ?><?php echo $invoice->country ?><?php endif; ?>
            </p>
            </td><td>
                <p class="small-text">
        <?php if(!empty($invoice->client_first_name)) : ?><strong><?php echo $invoice->client_first_name ?> <?php echo $invoice->client_last_name ?></strong><br /><?php endif; ?>
        <?php if(!empty($invoice->client_address_1)) : ?><?php echo $invoice->client_address_1 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_address_2)) : ?><?php echo $invoice->client_address_2 ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_city)) : ?><?php echo $invoice->client_city ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_state)) : ?><?php echo $invoice->client_state ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_zipcode)) : ?><?php echo $invoice->client_zipcode ?><br /><?php endif; ?>
        <?php if(!empty($invoice->client_country)) : ?><?php echo $invoice->client_country ?><?php endif; ?>
            </p>
            </td>
            </tr>
            </table>
    </div>

     <?php if(!empty($invoice->notes)) : ?>
        <div class="wrapper">
        <?php echo $invoice->notes ?>
        </div>
    <?php endif; ?>

    <div class="wrapper">
    <div class="part-1">
    <p><?php echo lang("ctn_652") ?></p>
    <table class="table table-bordered">
        <tr><td><?php echo lang("ctn_588") ?></td><td><?php echo $invoice->invoice_id ?></td></tr>
        <tr><td><?php echo lang("ctn_653") ?></td><td><?php echo date($this->settings->info->date_format, $invoice->timestamp) ?></td></tr>
        <tr><td><?php echo lang("ctn_599") ?></td><td><?php echo date($this->settings->info->date_format, $invoice->due_date) ?>
        <?php if($invoice->due_date < time() && $invoice->status == 1) {
            echo"<span class='overdue'>".lang("ctn_654")."</span>";
        }
        ?>
        </td></tr>
        <tr><td><?php echo lang("ctn_646") ?></td><td> <?php 
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
          ?> </td></tr>
        </table>
    </div>
    <div class="part-2">
    <p><?php echo lang("ctn_655") ?></p>
    <table class="table table-bordered">
        <tr><td><?php echo lang("ctn_656") ?></td><td><?php echo $invoice->paypal_email ?> - <?php echo $invoice->acc_username ?> - <?php echo $invoice->first_name ?> <?php echo $invoice->last_name ?></td></tr>
        <tr><td><?php echo lang("ctn_657") ?></td><td><?php echo $invoice->client_email ?> - <?php echo $invoice->client_username ?> - <?php echo $invoice->client_first_name ?> <?php echo $invoice->client_last_name ?></td></tr>
        <tr><td><?php echo lang("ctn_589") ?></td><td> <?php echo $invoice->title ?></td></tr>
        <tr><td><?php echo lang("ctn_663") ?></td><td> <?php if($invoice->date_paid) echo date($this->settings->info->date_format . " h:i:s", $invoice->date_paid) ?> <?php if(!empty($invoice->paid_by)) : ?><?php echo $invoice->paid_by ?><?php endif; ?></td></tr>
        </table>
    </div>
    </div>

    <hr>

    <table class="table">
            <tr class="table-header"><td><?php echo lang("ctn_616") ?></td><td><?php echo lang("ctn_617") ?></td><td><?php echo lang("ctn_618") ?></td><td class="align-right"><?php echo lang("ctn_619") ?></td></tr>
            <?php $sub_total = 0; ?>
            <?php foreach($items->result() as $r) : ?>
                <?php $total = number_format($r->quantity*$r->amount,2);
                $sub_total += $r->amount*$r->quantity; ?>
                <tr><td><strong><?php echo $r->name ?></strong><br /><?php echo $r->description ?></td><td><?php echo $r->quantity ?></td><td><?php echo $r->amount ?></td><td class="align-right"><?php echo $total ?></td></tr>
            <?php endforeach; ?>

            <tr class="warning align-right"><td colspan="4" class="align-right"><?php echo lang("ctn_625") ?>: <?php echo number_format($sub_total,2) ?>
            <?php $total = $sub_total; ?>
            <br /><?php if(!empty($invoice->tax_name_1)) : ?>
            <?php
            $tax_addon = abs($sub_total/100*$invoice->tax_rate_1);
            $total += $tax_addon;
            ?>
            <?php echo lang("ctn_658") ?> (<?php echo $invoice->tax_name_1 ?>) @ <?php echo $invoice->tax_rate_1 ?>% : <?php echo number_format($tax_addon,2) ?><br />
            <?php endif; ?>
            <br /><?php if(!empty($invoice->tax_name_2)) : ?>
            <?php
            $tax_addon = abs($sub_total/100*$invoice->tax_rate_2);
            $total += $tax_addon;
            ?>
            <?php echo lang("ctn_658") ?> (<?php echo $invoice->tax_name_2 ?>) @ <?php echo $invoice->tax_rate_2 ?>% : <?php echo number_format($tax_addon,2) ?><br />
            <?php endif; ?>
            <b><?php echo lang("ctn_628") ?>: <?php echo $invoice->symbol ?><?php echo number_format($total,2) ?></b>
            <?php if($payments_total > 0 && $invoice->status != 2) : ?>
                <br /><br /><b><?php echo lang("ctn_1166") ?></b>: -<?php echo $invoice->symbol ?><?php echo number_format($payments_total,2) ?><br />
                <?php $total = $total - $payments_total; ?>
                <b><?php echo lang("ctn_1456") ?></b>: <?php echo $invoice->symbol ?><?php echo number_format($total,2) ?>
            <?php endif; ?>
            </td></tr>
        </table>

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
            <tr><td><?php echo $invoice->symbol ?><?php echo $r->amount ?></td><td><?php echo $type ?></td><td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td><td><?php echo $r->email ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <hr>

        <p class="small-text"><i><?php echo $invoice->term_notes ?></i></p>
    </div>
    </body>
    </html>
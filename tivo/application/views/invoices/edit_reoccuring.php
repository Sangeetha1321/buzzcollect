<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-credit-card"></span> <?php echo lang("ctn_634") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("invoices/edit_reoccur_invoice_pro/" . $invoice->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_635") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="client_username" value="<?php echo $invoice->username ?>" id="username-search" placeholder="<?php echo lang("ctn_592") ?>">
            <span class="help-block"><?php echo lang("ctn_636") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_600") ?></label>
        <div class="col-md-8 ui-front">
            <select name="templateid" class="form-control">
            <?php foreach($templates->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>" <?php if($r->ID == $invoice->templateid) echo "selected" ?>><?php echo $r->title ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_637") ?></label>
        <div class="col-md-5 ui-front">
            <?php echo lang("ctn_638") ?> <input type="text" name="amount" class="form-control" value="<?php echo $invoice->amount ?>">
        </div>
        <div class="col-md-3">
            <?php echo lang("ctn_639") ?>
            <select name="amount_time" class="form-control">
            <option value="0"><?php echo lang("ctn_640") ?></option>
            <option value="1" <?php if($invoice->amount_time == 1) echo "selected" ?>><?php echo lang("ctn_641") ?></option>
            <option value="2" <?php if($invoice->amount_time == 2) echo "selected" ?>><?php echo lang("ctn_642") ?></option>
            <option value="3" <?php if($invoice->amount_time == 3) echo "selected" ?>><?php echo lang("ctn_643") ?></option>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_644") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" name="start_date" class="form-control datepicker" value="<?php echo date($this->settings->info->date_picker_format,$invoice->start_date) ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_645") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" name="end_date" class="form-control datepicker" <?php if($invoice->end_date > 0) : ?> value="<?php echo date($this->settings->info->date_picker_format,$invoice->end_date) ?>" <?php endif; ?>>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_646") ?></label>
        <div class="col-md-8 ui-front">
            <select name="status" class="form-control">
            <option value="0"><?php echo lang("ctn_647") ?></option>
            <option value="1" <?php if($invoice->status == 1) echo "selected" ?>><?php echo lang("ctn_648") ?></option>
            <option value="2" <?php if($invoice->status == 2) echo "selected" ?>><?php echo lang("ctn_649") ?></option>
            </select>
        </div>
</div>  

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_650") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
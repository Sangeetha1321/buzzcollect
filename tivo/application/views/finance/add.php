<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_512") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("finance/add_finance") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_513") ?></a>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("finance/add_finance_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_514") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="title" value="">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_515") ?></label>
        <div class="col-md-8">
            <textarea name="notes" id="notes"></textarea>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_516") ?></label>
        <div class="col-md-8">
            <select name="catid" class="form-control">
                <option value="0">None</option>
            <?php foreach($categories->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_517") ?></label>
        <div class="col-md-8">
            <select name="projectid" class="form-control">
            <?php foreach($projects->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->user->info->active_projectid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_518") ?></label>
        <div class="col-md-8">
            <input type="text" name="amount" class="form-control" value="0.00">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_293") ?></label>
        <div class="col-md-8">
            <input type="text" name="date" class="form-control datepicker" value="<?php echo date($this->settings->info->date_picker_format) ?>">
        </div>
</div>
<h3><?php echo lang("ctn_1556"); ?></h3>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1557"); ?></label>
        <div class="col-md-4">
            <input type="text" name="days" class="form-control" value="0">
        </div>
        <div class="col-md-4">
            <select name="type" class="form-control">
                <option value="1"><?php echo lang("ctn_277"); ?></option>
                <option value="2"><?php echo lang("ctn_668"); ?></option>
                <option value="3"><?php echo lang("ctn_669"); ?></option>
                <option value="4"><?php echo lang("ctn_670"); ?></option>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_827"); ?></label>
        <div class="col-md-8">
            <input type="text" name="start_date" class="form-control datepicker" value="<?php echo date($this->settings->info->date_picker_format) ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_645"); ?></label>
        <div class="col-md-8">
            <input type="text" name="end_date" class="form-control datepicker" value="<?php echo date($this->settings->info->date_picker_format) ?>">
            <span class="help-block"><?php echo lang("ctn_1558"); ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_545"); ?></label>
        <div class="col-md-8">
            <select name="status" class="form-control">
                <option value="0"><?php echo lang("ctn_647"); ?></option>
                <option value="1"><?php echo lang("ctn_648"); ?></option>
                <option value="2"><?php echo lang("ctn_649"); ?></option>
            </select>
        </div>
</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_519") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('notes', { height: '100'});
});
</script>
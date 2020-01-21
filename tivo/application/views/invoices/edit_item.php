<div class="white-area-content">

<div class="db-header db-header-nomargin clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-credit-card"></span> <?php echo lang("ctn_586") ?></div>
    <div class="db-header-extra">
</div>
</div>

<hr>

<?php echo form_open(site_url("invoices/edit_item_pro/" . $item->ID), array("class" => "form-horizontal", "id" => "invoice_form")) ?>
<div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="name" value="<?php echo $item->name ?>">
            </div>
    </div>
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="description" value="<?php echo $item->description ?>">
            </div>
    </div>
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1450") ?></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="price" value="<?php echo $item->price ?>">
            </div>
    </div>
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_617") ?></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="quantity" value="<?php echo $item->quantity ?>">
            </div>
    </div>
    <div class="form-group">
            <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_753") ?></label>
            <div class="col-md-8">
                <select name="projectid" class="form-control">
                  <option value="0">None</option>
                  <?php foreach($projects->result() as $r) : ?>
                      <option value="<?php echo $r->ID ?>" <?php if($r->ID == $item->projectid) echo "selected" ?>><?php echo $r->name ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="help-text"><?php echo lang("ctn_1461") ?></span>
            </div>
    </div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
<?php echo form_close() ?>

</div>
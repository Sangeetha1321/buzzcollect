<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_945") ?></div>
    <div class="db-header-extra"> <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_962") ?></button>
</div>
</div>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("tickets/edit_custom_field_pro/" . $field->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_953") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $field->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_954") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="type" class="form-control">
                        <option value="0"><?php echo lang("ctn_948") ?></option>
                        <option value="1" <?php if($field->type == 1) echo "selected" ?>><?php echo lang("ctn_949") ?></option>
                        <option value="2" <?php if($field->type == 2) echo "selected" ?>><?php echo lang("ctn_950") ?></option>
                        <option value="3" <?php if($field->type == 3) echo "selected" ?>><?php echo lang("ctn_951") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_950") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="select_options" value="<?php echo $field->select_options ?>">
                        <span class="help-block"><?php echo lang("ctn_966") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_967") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="required" value="1" <?php if($field->required) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_968") ?>.</span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_969") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="help_text" value="<?php echo $field->help_text ?>">
                        <span class="help-block"><?php echo lang("ctn_970") ?></span>
                    </div>
            </div>


            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_971") ?>">
        <?php echo form_close() ?>
</div>
</div>


</div>
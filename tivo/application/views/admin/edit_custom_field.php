<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra form-inline">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_971") ?></li>
</ol>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/edit_custom_field_pro/" . $field->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name" value="<?php echo $field->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_954") ?></label>
                    <div class="col-md-9">
                        <select name="type" class="form-control">
                        <option value="0"><?php echo lang("ctn_1281") ?></option>
                        <option value="1" <?php if($field->type == 1) echo "selected" ?>><?php echo lang("ctn_798") ?></option>
                        <option value="2" <?php if($field->type == 2) echo "selected" ?>><?php echo lang("ctn_799") ?></option>
                        <option value="3" <?php if($field->type == 3) echo "selected" ?>><?php echo lang("ctn_800") ?></option>
                        <option value="4" <?php if($field->type == 4) echo "selected" ?>><?php echo lang("ctn_801") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_52") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="options" value="<?php echo $field->options ?>">
                        <span class="help-block"><?php echo lang("ctn_1283") ?></span>
                    </div>
            </div>
            <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_803") ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" name="required" value="1" <?php if($field->required) echo "checked" ?>> <?php echo lang("ctn_53") ?>
                            <span class="help-block"><?php echo lang("ctn_1284") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_1285") ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" name="edit" value="1" <?php if($field->edit) echo "checked" ?>> <?php echo lang("ctn_53") ?>
                            <span class="help-block"><?php echo lang("ctn_1286") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_1287") ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" name="profile" value="1" <?php if($field->profile) echo "checked" ?>> <?php echo lang("ctn_53") ?>
                            <span class="help-block"><?php echo lang("ctn_1288") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_151") ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" name="register" value="1" <?php if($field->register) echo "checked" ?>> <?php echo lang("ctn_53") ?>
                            <span class="help-block"><?php echo lang("ctn_1289") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_1290") ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" name="leads" value="1" <?php if($field->leads) echo "checked" ?>> <?php echo lang("ctn_53") ?>
                            <span class="help-block"><?php echo lang("ctn_1291") ?></span>
                        </div>
                </div>

                <div class="form-group">
                        <label for="cpassword-in" class="col-md-3 label-heading"><?php echo lang("ctn_947") ?></label>
                        <div class="col-md-9">
                            <input type="text" name="help_text" class="form-control" value="<?php echo $field->help_text ?>">
                            <span class="help-block"><?php echo lang("ctn_1292") ?></span>
                        </div>
                </div>
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_971") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
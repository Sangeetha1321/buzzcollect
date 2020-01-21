<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_890") ?></div>
    <div class="db-header-extra"> 
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("team/edit_role_pro/" . $role->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_891") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="name" value="<?php echo $role->name ?>">
        </div>
</div>
<h4><?php echo lang("ctn_892") ?></h4>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_893") ?></label>
        <div class="col-md-8 ui-front">
            <input type="checkbox" name="admin" value="1" <?php if($role->admin) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_894") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_895") ?></label>
        <div class="col-md-8">
            <input type="checkbox" name="team" value="1" <?php if($role->team) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_896") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_897") ?></label>
        <div class="col-md-8">
            <input type="checkbox" name="time" value="1" <?php if($role->time) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_898") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_899") ?></label>
        <div class="col-md-8">
            <input type="checkbox" name="file" value="1" <?php if($role->file) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_900") ?></span>
        </div>
</div>
<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_901") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="task" value="1" <?php if($role->task) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_902") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_903") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="calendar" value="1" <?php if($role->calendar) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_904") ?></span>
                    </div>
            </div>
			<!--
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_905") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="finance" value="1" <?php if($role->finance) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_906") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_907") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="notes" value="1" <?php if($role->notes) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_908") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1141") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="reports" value="1" <?php if($role->reports) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_1174") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1218") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="client" value="1" <?php if($role->client) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_1219") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1527") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="doc" value="1" <?php if($role->doc) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_1533") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1237") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="invoice" value="1" <?php if($role->invoice) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_1534") ?></span>
                    </div>
            </div>
			-->
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_909") ?>">
<?php echo form_close() ?>
</div>

</div>
</div>
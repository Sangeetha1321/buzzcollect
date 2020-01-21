<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_791") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("leads/add_form") ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_808") ?></a>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading"><?php echo $form->title ?></div>
<div class="panel-body">
<?php echo form_open(site_url("leads/process_form/" . $form->ID), array("class" => "form-horizontal")) ?>
<p><?php echo $form->welcome ?></p>
<hr>
<input type="hidden" name="admin_check" value="1">
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1348") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="username" value="" id="username-search">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="assigned_username" value="<?php if(isset($form->assigned_username)) echo $form->assigned_username ?>" id="username-search2">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_848") ?></label>
        <div class="col-md-8">
            <select name="statusid" class="form-control">
            <?php foreach($statuses->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>" <?php if($r->ID == $form->default_statusid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1349") ?></label>
        <div class="col-md-8">
            <select name="sourceid" class="form-control">
            <?php foreach($sources->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>" <?php if($r->ID == $form->default_sourceid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<?php if($form->collect_user) : ?>
<h4><?php echo lang("ctn_1350") ?></h4>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_419") ?>*</label>
        <div class="col-md-8 ui-front">
            <input type="email" class="form-control" name="email" value="">
            <span class="help-block"><?php echo lang("ctn_1358") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1250") ?>*</label>
        <div class="col-md-4">
            <input type="text" class="form-control" name="first_name" value="" placeholder="First name">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="last_name" value="" placeholder="Last name">
        </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_429") ?></label>
    <div class="col-md-8">
      <input type="text" name="address_1" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_430") ?></label>
    <div class="col-md-8">
      <input type="text" name="address_2" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_431") ?></label>
    <div class="col-md-8">
      <input type="text" name="city" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_432") ?></label>
    <div class="col-md-8">
      <input type="text" name="state" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_433") ?></label>
    <div class="col-md-8">
      <input type="text" name="zipcode" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_434") ?></label>
    <div class="col-md-8">
      <input type="text" name="country" class="form-control" value="">
    </div>
</div>
<?php foreach($cfields->result() as $r) : ?>
    <div class="form-group">

        <label for="name-in" class="col-md-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8">
            <?php if($r->type == 0) : ?>
                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php if(isset($_POST['cf_'. $r->ID])) echo $_POST['cf_' . $r->ID] ?>">
            <?php elseif($r->type == 1) : ?>
                <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php if(isset($_POST['cf_'. $r->ID])) echo $_POST['cf_' . $r->ID] ?></textarea>
            <?php elseif($r->type == 2) : ?>
                 <?php $options = explode(",", $r->options); ?>
                <?php if(count($options) > 0) : ?>
                    <?php foreach($options as $k=>$v) : ?>
                    <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(isset($_POST['cf_cb_' . $r->ID . "_" . $k])) echo "checked" ?>> <?php echo $v ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php elseif($r->type == 3) : ?>
                <?php $options = explode(",", $r->options); ?>
                <?php if(count($options) > 0) : ?>
                    <?php foreach($options as $k=>$v) : ?>
                    <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if(isset($_POST['cf_radio_' . $r->ID]) && $_POST['cf_radio_' . $r->ID] == $k) echo "checked" ?>> <?php echo $v ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php elseif($r->type == 4) : ?>
                <?php $options = explode(",", $r->options); ?>
                <?php if(count($options) > 0) : ?>
                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                    <?php foreach($options as $k=>$v) : ?>
                    <option value="<?php echo $k ?>" <?php if(isset($_POST['cf_' . $r->ID]) && $_POST['cf_'.$r->ID] == $k) echo "selected" ?>><?php echo $v ?></option>
                    <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            <?php endif; ?>
            <span class="help-text"><?php echo $r->help_text ?></span>
        </div>
</div>
<?php endforeach; ?>
<hr>
<?php endif; ?>
<?php foreach($fields->result() as $r) : ?>

<?php if($r->type == 1) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="field_id_<?php echo $r->ID ?>" value="">
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 2) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <textarea name="field_id_<?php echo $r->ID ?>" id="field-<?php echo $r->ID ?>-textarea"></textarea>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 3) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
            	<?php foreach($options as $k=>$v) : ?>
            	<div class="form-group"><input type="checkbox" name="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></div>
            	<?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 4) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
            	<?php foreach($options as $k=>$v) : ?>
            	<div class="form-group"><input type="radio" name="field_id_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></div>
            	<?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 5) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
            	<select name="field_id_<?php echo $r->ID ?>" class="form-control">
            	<?php foreach($options as $k=>$v) : ?>
            	<option value="<?php echo $k ?>"><?php echo $v ?></option>
            	<?php endforeach; ?>
            	</select>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<hr>
* = <?php echo lang("ctn_803") ?>.

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_61") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
	<?php foreach($fields->result() as $r) : ?>
	<?php if($r->type == 2) : ?>
CKEDITOR.replace('field-<?php echo $r->ID ?>-textarea', { height: '100'});
<?php endif; ?>
<?php endforeach; ?>
});
</script>
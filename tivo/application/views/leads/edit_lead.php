<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_811") ?></div>
    <div class="db-header-extra form-inline"> 

</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("leads/edit_lead_pro/" . $lead->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1348") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="username" value="<?php if(isset($lead->client_username)) echo $lead->client_username ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="assigned_username" value="<?php if(isset($lead->assigned_username)) echo $lead->assigned_username ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_848") ?></label>
        <div class="col-md-8 ui-front">
            <select name="statusid" class="form-control">
            <?php foreach($statuses->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>" <?php if($r->ID == $lead->statusid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1349") ?></label>
        <div class="col-md-8 ui-front">
            <select name="sourceid" class="form-control">
            <?php foreach($sources->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>" <?php if($r->ID == $lead->sourceid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<h4><?php echo lang("ctn_1350") ?></h4>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_24") ?></label>
        <div class="col-md-8">
            <input type="text" name="email" class="form-control" value="<?php echo $lead->email ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_29") ?></label>
        <div class="col-md-8">
            <input type="text" name="first_name" class="form-control" value="<?php echo $lead->first_name ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_30") ?></label>
        <div class="col-md-8">
            <input type="text" name="last_name" class="form-control" value="<?php echo $lead->last_name ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_429") ?></label>
        <div class="col-md-8">
            <input type="text" name="address_1" class="form-control" value="<?php echo $lead->address_1 ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_430") ?></label>
        <div class="col-md-8">
            <input type="text" name="address_2" class="form-control" value="<?php echo $lead->address_2 ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_431") ?></label>
        <div class="col-md-8">
            <input type="text" name="city" class="form-control" value="<?php echo $lead->city ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_432") ?></label>
        <div class="col-md-8">
            <input type="text" name="state" class="form-control" value="<?php echo $lead->state ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_433") ?></label>
        <div class="col-md-8">
            <input type="text" name="zipcode" class="form-control" value="<?php echo $lead->zipcode ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_434") ?></label>
        <div class="col-md-8">
            <input type="text" name="country" class="form-control" value="<?php echo $lead->country ?>">
        </div>
</div>
<?php foreach($cfields->result() as $r) : ?>
            <div class="form-group">

                <label for="name-in" class="col-sm-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                <div class="col-sm-8">
                    <?php if($r->type == 0) : ?>
                        <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
                    <?php elseif($r->type == 1) : ?>
                        <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
                    <?php elseif($r->type == 2) : ?>
                         <?php $options = explode(",", $r->options); ?>
                         <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 3) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 4) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        <?php if(count($options) > 0) : ?>
                            <select name="cf_<?php echo $r->ID ?>" class="form-control">
                            <?php foreach($options as $k=>$v) : ?>
                            <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="help-text"><?php echo $r->help_text ?></span>
                </div>
        </div>
    <?php endforeach; ?>
<h4><?php echo lang("ctn_1351") ?></h4>
<?php foreach($fields->result() as $r) : ?>

<?php if($r->type == 1) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="field_id_<?php echo $r->ID ?>" value="<?php if(isset($r->answer)) echo $r->answer ?>">
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 2) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <textarea name="field_id_<?php echo $r->ID ?>" id="field-<?php echo $r->ID ?>-textarea"><?php if(isset($r->answer)) echo $r->answer ?></textarea>
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
                <div class="form-group"><input type="checkbox" name="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(isset($r->answer) && $r->answer == $v) echo "checked" ?>> <?php echo $v ?></div>
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
                <div class="form-group"><input type="radio" name="field_id_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if(isset($r->answer) && $r->answer == $v) echo "checked" ?>> <?php echo $v ?></div>
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
                <option value="<?php echo $k ?>" <?php if(isset($r->answer) && $r->answer == $v) echo "checked" ?>><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php endif; ?>
<?php endforeach; ?>


<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1352") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>
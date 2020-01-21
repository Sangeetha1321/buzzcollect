
    <?php if($update) : ?><?php echo form_open(site_url("team/update_user/" . $user->ID), array("id" => "invoice_form")) ?><?php endif; ?>

    <div class="col-md-6">
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_429") ?></label>
        <input type="text" class="form-control input-sm" name="address_line_1" value="<?php echo $user->address_1 ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_430") ?></label>
        <input type="text" class="form-control input-sm" name="address_line_2" value="<?php echo $user->address_2 ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_431") ?></label>
        <input type="text" class="form-control input-sm" name="city" value="<?php echo $user->city ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_432") ?></label>
        <input type="text" class="form-control input-sm" name="state" value="<?php echo $user->state ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_433") ?></label>
        <input type="text" class="form-control input-sm" name="zipcode" value="<?php echo $user->zipcode ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_434") ?></label>
        <input type="text" class="form-control input-sm" name="country" value="<?php echo $user->country ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>

  </div><div class="col-md-6">
    <div class="form-group">
        <p><strong><?php echo lang("ctn_735") ?></strong>: <?php echo $user->username ?></p>
        <p><strong><?php echo lang("ctn_24") ?></strong>: <?php echo $user->email ?></p>
        <p><strong><?php echo lang("ctn_81") ?></strong>: <?php echo $user->first_name ?> <?php echo $user->last_name ?></p>
        <?php if($role->num_rows() > 0) : ?>
                <?php $role = $role->row(); ?>
            <p><strong><?php echo lang("ctn_322") ?></strong>: <?php echo $role->name ?></p>
        <?php endif; ?>
    </div>
    <hr>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_1505") ?></label>
        <input type="text" class="form-control input-sm" name="company_name" value="<?php echo $user_data->company_name ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_1506") ?></label>
        <input type="text" class="form-control input-sm" name="phone" value="<?php echo $user_data->phone ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_1392") ?></label>
        <input type="text" class="form-control input-sm" name="website" value="<?php echo $user_data->website ?>" <?php if(!$update) : ?>disabled<?php endif; ?>>
    </div>
  </div>

<?php if($update) : ?>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
<?php echo form_close() ?><?php endif; ?>
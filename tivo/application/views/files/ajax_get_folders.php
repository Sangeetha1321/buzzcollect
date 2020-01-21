        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_472") ?></label>
        <div class="col-md-8">
            <select name="folderid" class="form-control">
            <option value="-1"><?php echo lang("ctn_473") ?></option>
            <?php foreach($folders->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $folderid) echo "selected" ?>><?php echo $r->file_name ?></option>
            <?php endforeach; ?>
            </select>
            <span class="help-block"><?php echo lang("ctn_474") ?></span>
        </div>
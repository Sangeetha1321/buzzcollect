<label for="exampleInputEmail1" class="light-label"><?php echo lang("ctn_591") ?></label>
	    <select name="clientid" id="client" class="form-control">
                <option value="0"><?php echo lang("ctn_1434") ?> ...</option>
                <option value="-1"><?php echo lang("ctn_1435") ?></option>
                <option value="-2"><?php echo lang("ctn_592") ?> ...</option>
                <?php foreach($team->result () as $r) : ?>
                	<option value="<?php echo $r->userid ?>"><?php echo $r->username ?> (<?php echo $r->first_name ?> <?php echo $r->last_name ?>)</option>
                <?php endforeach; ?>
        </select>
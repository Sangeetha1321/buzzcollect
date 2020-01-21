<label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_1544") ?></label>
<div class="col-md-6">
    <select name="link_documentid" class="form-control">
    <?php foreach($documents->result() as $r) : ?>
    	<option value="<?php echo $r->ID ?>"><?php echo $r->title ?></option>
    <?php endforeach; ?>
    </select> 
</div>
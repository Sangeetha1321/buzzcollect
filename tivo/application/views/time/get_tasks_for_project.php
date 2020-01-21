<select name="taskid" class="form-control"><option value="0"><?php echo lang("ctn_990") ?></option>
<?php foreach($tasks->result() as $r) : ?>
<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
<?php endforeach; ?>
</select>
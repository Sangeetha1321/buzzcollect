<div class="chat-body-wrap">
<div id="chat-body-errors"></div>
<p><?php echo lang("ctn_1323") ?></p>
<p class="ui-front"><select id="start_chat_projectid" class="form-control">
<option value="0"><?php echo lang("ctn_1324") ?></option>
<?php foreach($projects->result() as $r) : ?>
<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
<?php endforeach; ?>
</select></p>
<p><input type="text" name="reply" class="form-control" id="start_chat_title" placeholder="<?php echo lang("ctn_1314") ?>"></p>
<p><input type="text" name="reply" class="form-control" id="start_chat_message" placeholder="<?php echo lang("ctn_1319") ?>"></p>
<p><input type="button" class="btn btn-default btn-sm form-control" value="<?php echo lang("ctn_1320") ?>" id="start_chat_button"></p>

</div>
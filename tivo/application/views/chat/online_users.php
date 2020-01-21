<?php if((!$this->common->has_permissions(array("client"),
		 $this->user)) || ($this->common->has_permissions(array("client"),
		 $this->user) && $this->settings->info->chat_online_client)) : ?>
<div class="online-users">
<h4 class="chat-title"><?php echo lang("ctn_1325") ?></h4>
<?php foreach($users->result() as $r) : ?>
<div class="chat-messages-block highlight-chat click" onclick="new_chat_username('<?php echo $r->username ?>')">
  <table class="table borderless marginless">
  <tr><td>
    <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?>
  </td><td>
    <?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->online_timestamp)) ?>
  </td></tr>
  </table>
  </div>
<?php endforeach; ?>
</div>
<?php else : ?>
<div class="online-users">
<p><?php echo lang("ctn_1426") ?></p>
</div>
<?php endif; ?>
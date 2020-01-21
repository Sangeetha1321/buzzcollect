<div id="chat_bar">
<div id="chat_start">
<button type="button" class="btn btn-round-chat" id="chat_start_button"><span class="glyphicon glyphicon-comment"></span></button>
</div>

<div id="chat_history_window">
<div class="chat-top-bar">
<?php echo lang("ctn_1265") ?> <div class="pull-right"><span class="glyphicon glyphicon-folder-open click chat-icon" data-toggle="tooltip" data-placement="top" title="<?php echo lang("ctn_1335") ?>" id="chat_project_button"></span> <span class="glyphicon glyphicon-user click chat-icon" data-toggle="tooltip" data-placement="top" title="<?php echo lang("ctn_1336") ?>" id="chat_multi_user_button"></span> <?php if((!$this->common->has_permissions(array("client"),
		 $this->user)) || ($this->common->has_permissions(array("client"),
		 $this->user) && $this->settings->info->chat_online_client)) : ?><span class="glyphicon glyphicon-globe click chat-icon" data-toggle="tooltip" data-placement="top" title="<?php echo lang("ctn_1325") ?>" id="chat_online_users_button"></span><?php endif; ?> <span class="glyphicon glyphicon-plus click chat-icon" data-toggle="tooltip" data-placement="top" title="<?php echo lang("ctn_1337") ?>" id="chat_new_button"></span> <span class="glyphicon glyphicon-remove click chat-icon" id="chat_close_button"></span></div>
</div>
<div class="chat-main-body" id="chat-main-body">
</div>

</div>

<div id="active_chats">

</div>

<audio id="bleep" controls>
    <source src="<?php echo base_url() ?>images/audio/beep.mp3" type="audio/mpeg">
</audio>

</div>
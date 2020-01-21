<?php foreach($mail->result() as $r) : ?>
	<?php
	if($r->userid == $this->user->info->ID) {
		$avatar = $r->avatar2;
		$username = $r->username2;
    	$read = $r->unread_userid;
	} else {
		$avatar = $r->avatar;
		$username = $r->username;
    	$read = $r->unread_toid;
	}

	$message = trim($r->body);
  $body = strip_tags($message);
  if(strlen($body) > 100) {
    $body = substr($body, 0, 100);
  }
  ?>
<div class="notification-box-bit animation-fade clearfix <?php if($read) : ?>active-noti<?php endif; ?>">
  <div class="notification-icon-bit">
    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $avatar ?>" class="user-icon">
  </div>
  <div class="notification-text-bit click" onclick="load_mail_url(<?php echo $r->ID ?>)">
    <p class="mail-box-username"><a href=""><?php echo $username ?></a></p>
	<p class="mail-box-title"><?php echo $r->title ?></p>
	<p class="mail-box-message"><?php echo $body ?></p>
    <p class="notification-datestamp small-text"><?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->last_reply_timestamp)) ?></p>
  </div>
</div>
<?php endforeach; ?>
<script type="text/javascript">
// Load default mail
$(document).ready(function() {
  <?php if($default_mail > 0) : ?>
load_mail(<?php echo $default_mail ?>);
<?php endif; ?>
<?php if(isset($_GET['username'])) : ?>
compose('<?php echo $this->common->nohtml($_GET['username']) ?>');
<?php endif; ?>
});
</script>

<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-envelope"></span> <?php echo lang("ctn_742") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="row">
<div class="col-md-5 mail-border-top mail-border-right no-padding">
<div class="mail-box-snippet">
<button onclick="compose()" class="btn btn-primary btn-sm"><?php echo lang("ctn_738") ?></button> <a href="<?php echo site_url("mail/blocked") ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_743") ?></a>
</div>
<div class="mail-box-snippet">
<?php echo form_open(site_url("mail/search/")) ?>
<div class="row">
  <div class="col-lg-12">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="<?php echo lang("ctn_744") ?>" name="search" <?php if(isset($search)) : ?>value="<?php echo $search ?>"<?php endif; ?>>
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><?php echo lang("ctn_745") ?></button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
<?php echo form_close() ?>
</div>
<?php foreach($mail->result() as $r) : ?>
	<?php if($r->userid == $this->user->info->ID) {
		$avatar = $r->avatar2;
		$username = $r->username2;
    $read = $r->unread_userid;
    $online_timestamp = $r->online_timestamp2;
	} else {
		$avatar = $r->avatar;
		$username = $r->username;
    $read = $r->unread_toid;
    $online_timestamp = $r->online_timestamp;
	}

  // Body
  $message = trim($r->body);
  $body = strip_tags($message);
  if(strlen($body) > 100) {
    $body = substr($body, 0, 100);
  }
	?>
<div class="mail-box-snippet click <?php if($read) echo "mail-unread-alert" ?>" onclick="load_mail(<?php echo $r->ID ?>)" id="mail-box-msg-<?php echo $r->ID ?>">
<div class="mail-box-timestamp">
<?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->last_reply_timestamp)) ?>
</div>
<div class="mail-box-avatar">
<?php echo $this->common->get_user_display(array("username" => $username, "avatar" => $avatar, "online_timestamp" => $online_timestamp)) ?>
</div>
<div class="mail-box-text">
<p class="mail-box-username"><a href="<?php echo site_url("profile/" . $username) ?>"><?php echo $username ?></a></p>
<p class="mail-box-title"><?php echo $r->title ?></p>
<p class="mail-box-message"><?php echo $body ?></p>
</div>
</div>
<?php endforeach; ?>
<div class="mail-box-snippet">
<?php if(isset($this->pagination)) : ?>
<?php echo $this->pagination->create_links() ?>
<?php endif; ?>
</div>


</div>
<div class="col-md-7 mail-border-top no-padding" id="mail-view">
<div id="loading_spinner_mail">
      <span class="glyphicon glyphicon-refresh" id="ajspinner_mail"></span>
</div>
</div>

</div>
</div>

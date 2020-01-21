<script type="text/javascript">
$("#pagination2 a").on('click',function(e){
    e.preventDefault();
    $.ajax({
    url: jQuery(this).attr("href"),
    success: function(msg){
      $('#mail-view').html(msg);
      CKEDITOR.replace('mail-reply-textarea', { height: '100'});
    }
    });
    return false;
});
</script>
<style type="text/css">
.pagination { margin: 0px; }
</style>
<div id="loading_spinner_mail">
      <span class="glyphicon glyphicon-refresh" id="ajspinner_mail"></span>
</div>

<div class="mail-header">
<?php echo $mail->title ?>

<div class="mail-header-timestamp">
<a href="<?php echo site_url("mail/delete/" . $mail->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> <?php echo lang("ctn_746") ?></a>
</div>
</div>
<?php if($this->pagination->create_links()) : ?>
<div class="mail-reply clearfix">

<div class="mail-pagination small-text">
<?php echo $this->pagination->create_links() ?>
</div>
</div>
<?php endif; ?>

<?php foreach($replies as $r) : ?>
<div class="mail-reply">

<div class="mail-reply-avatar">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
</div>
<div class="mail-reply-body">
<div class="mail-reply-timestamp">
<?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->timestamp)) ?>
</div>
<p class="mail-reply-user"><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a> <?php echo lang("ctn_747") ?></p>
<div class="mail-reply-message"><?php echo $r->body ?></div>
</div>

</div>
<?php endforeach; ?>
<div class="mail-reply-textbox">
<?php if($mail->delete_toid || $mail->delete_userid) : ?>
<p><?php echo lang("ctn_748") ?></p>
<?php else : ?>
<?php echo form_open(site_url("mail/reply/" . $mail->ID)) ?>
<textarea name="reply" rows="5" id="mail-reply-textarea"></textarea>
<p class="mail-reply-button"><input type="submit" name="s" value="<?php echo lang("ctn_749") ?>" class="btn btn-primary form-control"></p>
<?php echo form_close() ?>
<?php endif; ?>
</div>
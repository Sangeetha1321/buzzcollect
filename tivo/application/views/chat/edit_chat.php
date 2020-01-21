<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-comment"></span> <?php echo lang("ctn_1312") ?></div>
    <div class="db-header-extra form-inline"> 


<a href="<?php echo site_url("chat/view/" . $chat->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_1313") ?></a>

</div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
    <?php echo form_open(site_url("chat/edit_chat_pro/" . $chat->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_1314") ?></label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="title" value="<?php echo $chat->title ?>">
          </div>
      </div>

      <input type="submit" name="s" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
      <?php echo form_close() ?>
      </div>
</div>

<h4>Chat Users</h4>
<?php if($chat->userid == $this->user->info->ID || $this->common->has_permissions(array("admin"), $this->user)) : ?>
<?php echo form_open(site_url("chat/add_user/" . $chat->ID), array("class" => "form-inline")) ?>
<div class="form-group">
    <input type="text" class="form-control" id="username-search" name="username" placeholder="<?php echo lang("ctn_1315") ?>">
  </div>
  <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1316") ?>">
<?php echo form_close() ?>
<?php endif; ?>

<hr>

<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($users->result() as $r) : ?>
<tr><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?></td><td>
<?php if($chat->userid == $this->user->info->ID || $this->common->has_permissions(array("admin"), $this->user)) : ?>
<a href="<?php echo site_url("chat/remove_from_chat/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_1317") ?></a>
<?php endif; ?></td></tr>
<?php endforeach; ?>
</table>


</div>
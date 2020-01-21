<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_791") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("leads/add_form") ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_808") ?></a>
</div>
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_792") ?></td><td><?php echo lang("ctn_794") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($forms->result() as $r) : ?>
<tr><td><?php echo $r->title ?></td><td><?php echo $this->common->get_user_display(array("username" => $r->assigned_username, "avatar" => $r->assigned_avatar, "online_timestamp" => $r->assigned_online_timestamp)) ?></td><td><a href="<?php echo site_url("leads/view/" . $r->ID) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_1353") ?></a> <a href="<?php echo site_url("leads/view_form_full/" . $r->ID) ?>" class="btn btn-info btn-xs"><?php echo lang("ctn_810") ?></a> <a href="<?php echo site_url("leads/edit_form/" . $r->ID) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("leads/delete_form/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" onclick="return confirm('<?php echo lang("ctn_317") ?>')" class="btn btn-danger btn-xs" title="<?php echo lang("ctn_57") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>
</div>

</div>
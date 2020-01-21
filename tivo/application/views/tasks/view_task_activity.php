<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_820") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("tasks/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_821") ?></a>
</div>
</div>

<table class="table table-striped table-bordered table-hover">
<tr class="table-header"><td><?php echo lang("ctn_869") ?></td><td><?php echo lang("ctn_879") ?></td><td><?php echo lang("ctn_880") ?></td><td><?php echo lang("ctn_885") ?></td></tr>
<?php foreach($actions->result() as $r) : ?>
<tr><td><img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="user-icon" /> <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></td><td><?php echo $r->message ?></td><td><a href="<?php site_url($r->url) ?>"><?php echo lang("ctn_880") ?></a></td><td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td></tr>
<?php endforeach; ?>
</table>

<div class="align-center">
<?php echo $this->pagination->create_links(); ?>
</div>

</div>
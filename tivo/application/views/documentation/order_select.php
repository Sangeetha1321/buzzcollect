<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_1527") ?></div>
    <div class="db-header-extra form-inline"> <a href="<?php echo site_url("documentation/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1536") ?></a>

   
</div>
</div>

<p><?php echo lang("ctn_1547") ?></p>

<?php foreach($projects->result() as $r) : ?>
<div class="project-block align-center">
<a href="<?php echo site_url("documentation/order/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" width="80" height="80"></a>
<p><a href="<?php echo site_url("documentation/order/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
	</div>
<?php endforeach; ?>

</div>
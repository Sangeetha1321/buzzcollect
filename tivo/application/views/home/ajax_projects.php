<?php foreach($projects->result() as $r) : ?>
<div class="notification-box-bit animation-fade clearfix">
  <div class="notification-icon-bit">
    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" class="user-icon">
  </div>
  <div class="notification-text-bit click">
    <a href="<?php echo site_url("projects/make_active/" . $r->ID) ?>"><?php echo $r->name ?></a>
  </div>
</div>
<?php endforeach; ?>
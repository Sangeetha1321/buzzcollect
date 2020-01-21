<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_325") ?></li>
</ol>


<hr>


    <div class="align-center">
      <?php echo $this->pagination->create_links(); ?>
    </div>

<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_77") ?>
</td><td><?php echo lang("ctn_81") ?>
</td><td><?php echo lang("ctn_78") ?>
</td><td><?php echo lang("ctn_323") ?>
</td><td><?php echo lang("ctn_324") ?>
</td><td><?php echo lang("ctn_83") ?>
</td><td><?php echo lang("ctn_52") ?></td></tr>

<?php foreach($members->result() as $r) : ?>
    <?php $time = $this->common->convert_time($r->premium_time); 
    unset($time['mins']);
    unset($time['secs']);?>
<tr><td><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></td><td><?php echo $r->first_name . " " . $r->last_name ?></td><td><?php echo $r->email ?></td><td><?php echo $r->name ?></td><td><?php echo $this->common->get_time_string($time) ?></td><td><?php echo date($this->settings->info->date_format, $r->joined) ?></td><td><a href="<?php echo site_url("admin/edit_member/" . $r->ID) ?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("admin/delete_member/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" onclick="return confirm('Are you sure you want to delete this?')" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>

</table>

    <div class="align-center">
      <?php echo $this->pagination->create_links(); ?>
    </div>

    </div>
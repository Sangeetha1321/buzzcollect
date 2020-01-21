<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-envelope"></span> <?php echo lang("ctn_731") ?></div>
    <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#blockModal"><?php echo lang("ctn_732") ?></button>
</div>
</div>

<p><?php echo lang("ctn_733") ?></p>

<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_734") ?></td><td><?php echo lang("ctn_735") ?></td><td><?php echo lang("ctn_736") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($blocks->result() as $r) : ?>
<tr><td><img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="user-icon" width="40"></td><td><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></td><td><?php echo $r->reason ?></td><td><a href="<?php echo site_url("mail/remove_block/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_737") ?></a></td></tr>
<?php endforeach; ?>
</table>

</div>


<div class="modal fade" id="blockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_732") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("mail/add_blocked_user"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_735") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="username" value="" id="username-search">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_736") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="p-in" name="reason" value="">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_732") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
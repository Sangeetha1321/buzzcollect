<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-credit-card"></span> <?php echo lang("ctn_406") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_407") ?>" data-toggle="modal" data-target="#addModal" />
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_406") ?></li>
</ol>

<hr>

<table class="table table-striped table-hover table-bordered tbl">
<tr class="table-header"><td><?php echo lang("ctn_408") ?></td><td><?php echo lang("ctn_409") ?></td><td><?php echo lang("ctn_410") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($currencies->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><?php echo $r->symbol ?></td><td><?php echo $r->code ?></td><td><a href="<?php echo site_url("admin/edit_currency/" . $r->ID) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("admin/delete_currency/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_411") ?>')" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>

</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_407") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_currency_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_408") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name">
                    </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_409") ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="username" name="symbol">
                        </div>
            </div>
            <div class="form-group">
                        <label for="code-in" class="col-md-3 label-heading"><?php echo lang("ctn_410") ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="code">
                        </div>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_407") ?>" />
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
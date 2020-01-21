<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_728") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

</div>

<div class="row">
<div class="col-md-6">

<div class="white-area-content content-separator">

<div class="db-header clearfix">
    <div class="page-header-title"><?php echo lang("ctn_1354") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1316") ?>" data-toggle="modal" data-target="#addStatusModal" />
</div>
</div>

<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($statuses->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("leads/delete_status/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
<?php endforeach; ?>
</table>
</div>

</div>
<div class="col-md-6">

<div class="white-area-content content-separator">

<div class="db-header clearfix">
    <div class="page-header-title"><?php echo lang("ctn_1355") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1316") ?>" data-toggle="modal" data-target="#addSourceModal" />
</div>
</div>

<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($sources->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("leads/delete_source/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
<?php endforeach; ?>
</table>
</div>

</div>
</div>

<div class="modal fade" id="addStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_1356") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("leads/add_status"), array("class" => "form-horizontal")) ?>
     
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name">
                    </div>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1356") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addSourceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_1357") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("leads/add_source"), array("class" => "form-horizontal")) ?>
     
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name">
                    </div>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1357") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
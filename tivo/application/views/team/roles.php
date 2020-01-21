<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_890") ?></div>
    <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_914") ?></button>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<p><?php echo lang("ctn_915") ?></p>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_916") ?></td><td><?php echo lang("ctn_917") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($roles->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td>
<td>
<?php if($r->admin) : ?><label class="label label-default"><?php echo lang("ctn_893") ?></label> <?php endif; ?>
<?php if($r->team) : ?><label class="label label-default"><?php echo lang("ctn_895") ?></label> <?php endif; ?>
<?php if($r->time) : ?><label class="label label-default"><?php echo lang("ctn_897") ?></label> <?php endif; ?>
<?php if($r->file) : ?><label class="label label-default"><?php echo lang("ctn_899") ?></label> <?php endif; ?>
<?php if($r->task) : ?><label class="label label-default"><?php echo lang("ctn_901") ?></label> <?php endif; ?>
<?php if($r->calendar) : ?><label class="label label-default"><?php echo lang("ctn_903") ?></label> <?php endif; ?>
<?php if($r->finance) : ?><label class="label label-default"><?php echo lang("ctn_905") ?></label> <?php endif; ?>
<?php if($r->notes) : ?><label class="label label-default"><?php echo lang("ctn_907") ?></label> <?php endif; ?>
<?php if($r->reports) : ?><label class="label label-default"><?php echo lang("ctn_1141") ?></label> <?php endif; ?>
<?php if($r->client) : ?><label class="label label-default"><?php echo lang("ctn_1218") ?></label> <?php endif; ?>
<?php if($r->doc) : ?><label class="label label-default"><?php echo lang("ctn_1527") ?></label> <?php endif; ?>
<?php if($r->invoice) : ?><label class="label label-default"><?php echo lang("ctn_1237") ?></label> <?php endif; ?>
</td>
<td><a href="<?php echo site_url("team/edit_role/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("team/delete_role/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" onclick="return confirm('<?php echo lang("ctn_508") ?>')" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></a></td></tr>
<?php endforeach; ?>
</table>
</div>


</div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_914") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("team/add_role"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_891") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <h4><?php echo lang("ctn_892") ?></h4>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_893") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="admin" value="1">
                        <span class="help-block"><?php echo lang("ctn_894") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_895") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="team" value="1">
                        <span class="help-block"><?php echo lang("ctn_896") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_897") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="time" value="1">
                        <span class="help-block"><?php echo lang("ctn_898") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_899") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="file" value="1">
                        <span class="help-block"><?php echo lang("ctn_900") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_901") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="task" value="1">
                        <span class="help-block"><?php echo lang("ctn_902") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_903") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="calendar" value="1">
                        <span class="help-block"><?php echo lang("ctn_904") ?></span>
                    </div>
            </div>
			<!--
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_905") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="finance" value="1">
                        <span class="help-block"><?php echo lang("ctn_906") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_907") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="notes" value="1">
                        <span class="help-block"><?php echo lang("ctn_908") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1141") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="reports" value="1">
                        <span class="help-block"><?php echo lang("ctn_1174") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1218") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="client" value="1">
                        <span class="help-block"><?php echo lang("ctn_1219") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1527") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="doc" value="1">
                        <span class="help-block"><?php echo lang("ctn_1533") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1237") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="invoice" value="1">
                        <span class="help-block"><?php echo lang("ctn_1534") ?></span>
                    </div>
            </div>
			-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_914") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
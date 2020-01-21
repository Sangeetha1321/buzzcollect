<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_945") ?></div>
    <div class="db-header-extra"> <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_946") ?></button>
</div>
</div>

<hr>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_490") ?></td><td><?php echo lang("ctn_947") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($fields->result() as $r) : ?>
  <?php
    if($r->type == 0) {
      $type = lang("ctn_948");
    } elseif($r->type == 1) {
      $type = lang("ctn_949");
    } elseif($r->type == 2) {
      $type = lang("ctn_950");
    } elseif($r->type == 3) {
      $type = lang("ctn_951");
    } else {
      $type = lang("ctn_952");
    }
  ?>
<tr><td><?php echo $r->name ?></td><td><?php echo $type ?></td><td><?php echo $r->help_text ?></td><td><a href="<?php echo site_url("tickets/edit_custom_field/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("tickets/delete_custom_field/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>
</div>



</div>


<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_946") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("tickets/add_custom_field"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_953") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_954") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="type" class="form-control">
                        <option value="0"><?php echo lang("ctn_948") ?></option>
                        <option value="1"><?php echo lang("ctn_949") ?></option>
                        <option value="2"><?php echo lang("ctn_950") ?></option>
                        <option value="3"><?php echo lang("ctn_951") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_955") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="select_options">
                        <span class="help-block"><?php echo lang("ctn_956") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_957") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="required" value="1">
                        <span class="help-block"><?php echo lang("ctn_958") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_959") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="help_text">
                        <span class="help-block"><?php echo lang("ctn_960") ?></span>
                    </div>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_946") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
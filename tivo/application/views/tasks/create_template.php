<?php echo form_open(site_url("tasks/template_process/" . $task->ID), array("class" => "form-horizontal")) ?>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-cog"></span> <?php echo lang("ctn_1496") ?></h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1497") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_52") ?></label>
                    <div class="col-md-8 ui-front">
                        <?php echo lang("ctn_1498") ?> <input type="checkbox" name="import_objectives" value="1" checked><br />
                        <?php echo lang("ctn_1499") ?> <input type="checkbox" name="import_task_members" value="1" checked><br />
                        <?php echo lang("ctn_1500") ?> <input type="checkbox" name="import_team" value="1"><br />
                        <?php echo lang("ctn_1501") ?> <input type="checkbox" name="import_files" value="1" checked><br />
                        <?php echo lang("ctn_1502") ?> <input type="checkbox" name="import_messages" value="1" checked><br />
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_837") ?>">
        <?php echo form_close() ?>
      </div>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1606") ?></div>
    <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_1607") ?></button>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<p><?php //echo lang("ctn_764") ?></p>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_1608") ?></td>
<td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($softwares->result() as $r) : ?>
<tr>
	<td>
		<label class="label label-default"><?php echo $r->softwares_available ?></label>
	</td>
	<td>
		<a href="<?php echo site_url("machine/edit_softwares/" . $r->ID) ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>" class="btn btn-warning btn-xs">
			<span class="glyphicon glyphicon-cog"></span>
		</a>
		<a href="<?php echo site_url("machine/delete_softwares/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
<?php endforeach; ?>
</table>

</div>

</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_1606") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("machine/add_softwares"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="softwares" class="col-md-4 label-heading"><?php echo lang("ctn_1609") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="softwares" value="">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1607") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
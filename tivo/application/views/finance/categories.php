<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_520") ?></div>
    <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_521") ?></button>
</div>
</div>


<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_522") ?></td><td><?php echo lang("ctn_523") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($categories->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><?php echo $r->description ?></td><td><a href="<?php echo site_url("finance/edit_category/" . $r->ID) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>" data-toggle="tooltip" data-placement="right"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("finance/delete_category/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_524") ?>')" title="<?php echo lang("ctn_57") ?>" data-toggle="tooltip" data-placement="right"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach;?>
</table>
</div>

</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_525") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("finance/add_category_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_522") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_523") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="cat-description"></textarea>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_525") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('cat-description', { height: '100'});
});
</script>
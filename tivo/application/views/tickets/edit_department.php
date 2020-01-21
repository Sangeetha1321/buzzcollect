<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_961") ?></div>
    <div class="db-header-extra"> <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_962") ?></button>
</div>
</div>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("tickets/edit_department_pro/" . $department->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_964") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $department->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_965") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="desc" value="<?php echo $department->description ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1382") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="user_groups[]" multiple class="form-control chosen-select-no-single" id="ug" data-placeholder="<?php echo lang("ctn_1383") ?>">
                            <?php foreach($user_groups->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1384") ?></span>
                    </div>
            </div>

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_972") ?>">
        <?php echo form_close() ?>
</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
      $(".chosen-select-no-single").chosen({
    disable_search_threshold:10
});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('project-description', { height: '100'});
});
</script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1602") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("admin/edit_skills_pro/" . $skillset->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                    <label for="skills" class="col-md-4 label-heading"><?php echo lang("ctn_488") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="skills" value="<?php echo $skillset->skills ?>">
                    </div>
            </div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_784") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
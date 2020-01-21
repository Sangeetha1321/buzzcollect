<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_867") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="users[]" multiple class="form-control chosen-select-no-single" id="ug" data-placeholder="<?php echo lang("ctn_1378") ?>">
                            <?php foreach($team->result() as $r) : ?>
                                <option value="<?php echo $r->userid ?>"><?php echo $r->username ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1379") ?></span>
                    </div>
                    <link href="<?php echo base_url() ?>scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
            <script type="text/javascript" src="<?php echo base_url() ?>scripts/libraries/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

  $(".chosen-select-no-single").chosen({
    disable_search_threshold:10
});
});

</script>
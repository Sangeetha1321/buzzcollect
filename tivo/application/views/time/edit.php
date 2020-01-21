<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-time"></span> <?php echo lang("ctn_987") ?></div>
    <div class="db-header-extra"> </div>
</div>

<div class="panel panel-default">
<div class="panel panel-body">
 <?php echo form_open(site_url("time/edit_timer_pro/" . $timer->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_988") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="note" value="<?php echo $timer->note ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control" id="project-select">
                            <option value="0"><?php echo lang("ctn_46") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $timer->projectid) : ?>selected<?php endif;?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Title"; ?></label>
                    <div class="col-md-8 ui-front" id="task-area">
                        <select name="taskid" class="form-control"><option value="0"><?php echo lang("ctn_990") ?></option>
                        <?php foreach($tasks->result() as $r) : ?>
                        <option value="<?php echo $r->ID ?>" <?php if($r->ID == $timer->taskid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_991") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="rate" value="<?php echo $timer->rate ?>">
                        <span class="help-text"><?php echo lang("ctn_992") ?></span>
                    </div>
            </div>
            <?php
            if($timer->end_time <=0) {
            	$time_passed = 0;
            } else {
            	$time_passed = $timer->end_time - $timer->start_time;
        	}
            ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_993") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="time_passed" value="<?php echo $time_passed ?>">
                        <span class="help-text"><?php echo lang("ctn_994") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_594"); ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="status" class="form-control">
                          <option value="0"><?php echo lang("ctn_595"); ?></option>
                          <option value="1" <?php if($timer->status == 1) echo "selected" ?>><?php echo lang("ctn_596"); ?></option>
                        </select>
                    </div>
            </div>

             <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_995") ?>">
        <?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#project-select').change(function() {
    var projectid = $('#project-select').val();
    $.ajax({
      url: global_base_url + 'time/get_tasks_for_project/',
      type: 'GET',
      data: {
        projectid : projectid
      },
      success: function(msg) {
        $('#task-area').html(msg);
      }
    });
  });

});

  </script>
<?php if(isset($site['fullcalendar_lang']) && !empty($site['fullcalendar_lang'])) : ?>
        <script src="<?php echo base_url() . $site['fullcalendar_lang'] ?>"></script>
        <?php endif; ?>
        <?php if(isset($site['datetimepicker']) && !empty($site['datetimepicker'])) : ?>
        <script type="text/javascript">
          jQuery.datetimepicker.setLocale('<?php echo $site['datetimepicker'] ?>');
        </script>
        <?php endif; ?>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-time"></span> <?php echo lang("ctn_447") ?></div>
    <div class="db-header-extra">
<div class="btn-group">

  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php echo lang("ctn_448") ?> <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="<?php echo site_url("calendar/".$page."/") ?>"><?php echo lang("ctn_449") ?></a></li>
    <li><a href="<?php echo site_url("calendar/".$page."/0") ?>"><?php echo lang("ctn_450") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <li><a href="<?php echo site_url("calendar/".$page."/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>


</div>
</div>


<div id="calendar">

</div>


</div>

<!-- Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_451") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("calendar/add_site_event"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_452") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_453") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="description">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_454") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="start_date" id="start_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_455") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="end_date" id="end_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-8">
                        <select name="projectid" class="form-control"><option value="0"><?php echo lang("ctn_450") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_457") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_458") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("calendar/update_site_event"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_452") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="" id="event_name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_453") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="description" id="event_desc">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_454") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="start_date" id="event_start_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_455") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="end_date" id="event_end_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_459") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="delete" value="1">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-8">
                        <div id="project-name"></div>
                    </div>
            </div>
            <div class="form-group" id="task-area">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_847"); ?></label>
                    <div class="col-md-8">
                        <div id="task-name"></div>
                    </div>
            </div>
            <input type="hidden" name="eventid" id="event_id" value="0" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_462") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>



<script tye="text/javascript">
$(document).ready(function() {
    // page is now ready, initialize the calendar...
    var date_last_clicked = null;
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    $('.datetimepicker').datetimepicker({
      format : '<?php echo $this->settings->info->calendar_picker_format ?>'
    });

    $('#calendar').fullCalendar({
      eventSources: [
        <?php foreach($calendar_ids as $calendar) : ?>
        {
           events: function(start, end, timezone, callback) {
            $.ajax({
                url: global_base_url + 'calendar/get_site_events/',
                dataType: 'json',
                data: {
                    // our hypothetical feed requires UNIX timestamps
                    start: start.unix(),
                    end: end.unix(),
                    projectid : <?php echo $calendar['id'] ?>
                },
                success: function(msg) {
                    var events = msg.events;
                    callback(events);
                }
            });
          }
        },
        <?php endforeach; ?>
        <?php if($projectid == null) : ?>
        {
           events: function(start, end, timezone, callback) {
            $.ajax({
                url: global_base_url + 'calendar/get_site_events/',
                dataType: 'json',
                data: {
                    // our hypothetical feed requires UNIX timestamps
                    start: start.unix(),
                    end: end.unix(),
                    projectid : 0
                },
                success: function(msg) {
                    var events = msg.events;
                    callback(events);
                }
            });
          }
        }
        <?php endif; ?>
      ],
      timezone: 'UTC',
      dayClick: function(date, jsEvent, view) {
          var start_date = moment(date).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>');
          $('#start_date').val(start_date);
          $('#end_date').val(start_date);
          date_last_clicked = $(this);
          $(this).css('background-color', '#bed7f3');
          $('#addEventModal').modal();
       },
       columnFormat: {
           'month' : 'ddd'
       },
       timeFormat: 'HH:mm',
       eventClick: function(event, jsEvent, view) {
          $('#event_name').val(event.title);
          $('#event_desc').val(event.description);
          $('#event_start_date').val(moment(event.start).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          if(event.end) {
            $('#event_end_date').val(moment(event.end).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          } else {
            $('#event_end_date').val(moment(event.start).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          }
          $('#event_id').val(event.id);
          $('#project-name').html(event.project_name);
          if(event.taskid > 0) {
            $('#task-name').html('<a href="'+global_base_url+'tasks/view/'+event.taskid+'">'+event.task_name+'</a>');
          } else {
            $('#task-area').fadeOut(10);
          }
          $('#editEventModal').modal();
          if (event.url) {
              $('#event_url').attr("href", event.url);
              return false;
          }
       },
       nextDayThreshold : '01:00:00'
    })

    $('#addEventModal').on('hidden.bs.modal', function () {
        // do something…
        date_last_clicked.css('background-color', '#ffffff');
    });

});
</script>
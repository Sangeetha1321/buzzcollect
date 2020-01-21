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
         <?php echo form_open(site_url("calendar/add_google_event"), array("class" => "form-horizontal")) ?>
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
         <?php echo form_open(site_url("calendar/update_google_event"), array("class" => "form-horizontal")) ?>
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
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_460") ?></label>
                    <div class="col-md-8">
                        <a href="#" id="event_url" target="_blank"><?php echo lang("ctn_461") ?></a>
                    </div>
            </div>
            <?php foreach($calendar_ids as $calendar) : ?>
             <div class="form-group" id="project-id-<?php echo str_replace(".", "__", str_replace("@", "T-_-T", $calendar['calendar_id'])) ?>" style="display: none;">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-8">
                        <?php echo $calendar['project_name'] ?>
                    </div>
            </div>
            <?php endforeach; ?>
            <?php if(!empty($this->settings->info->google_calendar_id)) : ?>
              <div class="form-group" id="project-id-<?php echo str_replace(".", "__", str_replace("@", "T-_-T", $this->settings->info->google_calendar_id)) ?>" style="display: none;">
                      <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_456") ?></label>
                      <div class="col-md-8">
                          <?php echo lang("ctn_450") ?>
                      </div>
              </div>
            <?php endif; ?>
            <input type="hidden" name="eventid" id="event_id" value="0" />
            <input type="hidden" name="event_calendar_id" id="event_calendar_id" value="0" />
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
var previd = null;
$(document).ready(function() {
    // page is now ready, initialize the calendar...
    var date_last_clicked = null;
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    $('.datetimepicker').datetimepicker({
      format : '<?php echo $this->settings->info->calendar_picker_format ?>'
    });

    $('#calendar').fullCalendar({
        // put your options and callbacks here
        googleCalendarApiKey: '<?php echo $this->settings->info->google_calendar_api_key ?>',
        eventSources: [
            <?php foreach($calendar_ids as $calendar) : ?>
            {
                googleCalendarId: '<?php echo $calendar['calendar_id'] ?>',
                color : '#<?php echo $calendar['color'] ?>'
            },
            <?php endforeach; ?>
            <?php if($projectid == null && $this->settings->info->google_calendar_id) : ?>
            {
                googleCalendarId: '<?php echo $this->settings->info->google_calendar_id ?>',
                color : '#1caaf3'
            }
            <?php endif; ?>
        ],
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
       eventMouseover: function(event, jsEvent, view) {
          $(jsEvent.target).attr('title', event.description);
       },
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
          $('#event_calendar_id').val(event.source.googleCalendarId);
          if(previd != null) {
            $('#project-id-'+previd).css("display", "none");
          }
          var calname = event.source.googleCalendarId.replace(/\@/g, "T-_-T");
          var calname = calname.replace(/\./g, "__");
          $('#project-id-'+calname).css("display", "block");
          previd = calname;
          $('#editEventModal').modal();
          if (event.url) {
              $('#event_url').attr("href", event.url);
              return false;
          }
       }
    })

    $('#addEventModal').on('hidden.bs.modal', function () {
        // do something…
        date_last_clicked.css('background-color', '#ffffff');
    });

});
</script>
<div class="white-area-content">

<div class="db-header db-header-nomargin clearfix">
    <div class="page-header-title"> <?php echo $this->common->get_user_display(array("username" => $user->username, "avatar" => $user->avatar, "online_timestamp" => $user->online_timestamp)) ?> <?php echo $user->first_name ?> <?php echo $user->last_name ?> (@<a href="<?php echo site_url("profile/" . $user->username) ?>"><?php echo $user->username ?></a>)</div>
    <div class="db-header-extra form-inline"> <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#emailModal"><?php echo lang("ctn_24") ?></button> <a href="<?php echo site_url("mail/index?username=" . $user->username) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_582") ?></a> <a href="<?php echo site_url("profile/" . $user->username) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_200") ?></a>


</div>
</div>


</div>



<div class="white-area-content content-separator clearfix" id="invoice-area">

<ul class="nav nav-tabs invoice-heading-tabs" role="tablist" id="tabs">
    <li role="presentation" class="active invoice-tab"><a href="#details" aria-controls="home" role="tab" data-toggle="tab" data-url="details"><?php echo lang("ctn_652") ?></a></li>
    <li role="presentation" class=" invoice-tab"><a href="#projects" aria-controls="notes" role="tab" data-toggle="tab" data-url="projects"><?php echo lang("ctn_689") ?></a></li>
    <li role="presentation" class=" invoice-tab"><a href="#tasks" aria-controls="notes" role="tab" data-toggle="tab" data-url="tasks"><?php echo lang("ctn_696") ?></a></li>
    <li role="presentation" class=" invoice-tab"><a href="#invoices" aria-controls="themes" role="tab" data-toggle="tab" data-url="invoices"><?php echo lang("ctn_720") ?></a></li>
    <li role="presentation" class=" invoice-tab"><a href="#timers" id="tax-tab" aria-controls="payments" role="tab" data-toggle="tab" data-url="timers"><?php echo lang("ctn_675") ?></a>
    </li>
    <li role="presentation" class=" invoice-tab"><a href="#tickets" id="tax-tab" aria-controls="payments" role="tab" data-toggle="tab" data-url="tickets"><?php echo lang("ctn_711") ?></a>
    </li>
    <li role="presentation" class=" invoice-tab"><a href="#notes" id="tax-tab" aria-controls="payments" role="tab" data-toggle="tab" data-url="notes"><?php echo lang("ctn_725") ?></a>
    </li>
    <li role="presentation" class=" invoice-tab"><a href="#logs" id="tax-tab" aria-controls="payments" role="tab" data-toggle="tab" data-url="logs"><?php echo lang("ctn_1509") ?></a>
    </li>
  </ul>

  <br>


  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="details">
    </div>

    <div role="tabpanel" class="tab-pane active" id="tasks">
    </div>
    <div role="tabpanel" class="tab-pane active" id="invoices">
    </div>
    <div role="tabpanel" class="tab-pane active" id="timers">
    </div>
    <div role="tabpanel" class="tab-pane active" id="tickets">
    </div>
    <div role="tabpanel" class="tab-pane active" id="logs">
    </div>
    <div role="tabpanel" class="tab-pane active" id="projects">
    </div>
    <div role="tabpanel" class="tab-pane active" id="notes">
    </div>
  </div>


</div>

<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_962") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("team/email_user/" . $user->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_652") ?></label>
                    <div class="col-md-8 ui-front">
                        <?php echo lang("ctn_657") ?>: <?php echo $user->email ?><br />
                        <?php echo lang("ctn_560") ?>: <?php echo $this->settings->info->site_email ?>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1510") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="subject" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_24") ?></label>
                    <div class="col-md-8 ui-front">
                        <textarea name="email" class="form-control" rows="8"></textarea>
                    </div>
            </div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_50") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    $('#tabs li a').on('click',function (e) {
        e.preventDefault();
        var url = $(this).attr("data-url");

       

        if (typeof url !== "undefined") {
            var pane = $(this), href = this.hash;

            // ajax load from data-url
            $.ajax({
                url: global_base_url + "team/load_ajax_"+url+"/" + <?php echo $user->ID ?>,
                type: 'GET',
                data: {

                },
                success: function(msg) {
                    $(href).html(msg);
                    pane.tab('show');
                }
            });
        } else {
            $(this).tab('show');
        }
    });

    $('#tabs .active a').click();
});
</script>
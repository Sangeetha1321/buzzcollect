<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-credit-card"></span> <?php echo lang("ctn_1331") ?></div>
    <div class="db-header-extra form-inline"> 

             <div class="form-group has-feedback no-margin">
<div class="input-group">
<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_354") ?>" id="form-search-input" />
<div class="input-group-btn">
    <input type="hidden" id="search_type" value="0">
        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
        <ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
          <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
          <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="name-exact"></span> <?php echo lang("ctn_81") ?></a></li>
          <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="paypal-exact"></span> <?php echo lang("ctn_253") ?></a></li>
          <li><a href="#" onclick="change_search(5)"><span class="glyphicon glyphicon-ok no-display" id="address-exact"></span>  <?php echo lang("ctn_1116") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>
  
<input type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" value="<?php echo lang("ctn_1334") ?>">

</div>
</div>

<div class="table-responsive">
<table id="acc-table" class="table table-striped table-hover table-bordered">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_253") ?></td><td><?php echo lang("ctn_429") ?></td><td><?php echo lang("ctn_434") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_1334") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("invoices/add_payment_account"), array("class" => "form-horizontal")) ?>
          <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1332") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_29") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="first_name">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_30") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="last_name">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_24") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="email">
                    </div>
            </div>
            <hr>
            <h4><?php echo lang("ctn_1333") ?></h4>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_253") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="paypal_email">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1112") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="stripe_secret_key">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1113") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="stripe_publishable_key">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1183") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="checkout2_account_number">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1184") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="checkout2_secret_key">
                    </div>
            </div>
            <hr>
            <h4><?php echo lang("ctn_1116") ?></h4>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_429") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="address_line_1">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_430") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="address_line_2">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_431") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="city">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_432") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="state">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_433") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="zip">
                    </div>
            </div>
            <div class="form-group ui-front">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_434") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="country">
                    </div>
            </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1334") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {


   var st = $('#search_type').val();
    var table = $('#acc-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
        ],
        "columns": [
        null,
        null,
        null,
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("invoices/paying_account_page") ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#form-search-input').on('keyup change', function () {
    table.search(this.value).draw();
});

} );
function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "name-exact",
        "email-exact",
        "address-exact",
      ];
      set_search_icon(options[search], options);
        $('#search_type').val(search);
        $( "#form-search-input" ).trigger( "change" );
    }

function set_search_icon(icon, options) 
    {
      for(var i = 0; i<options.length;i++) {
        if(options[i] == icon) {
          $('#' + icon).fadeIn(10);
        } else {
          $('#' + options[i]).fadeOut(10);
        }
      }
    }
</script>
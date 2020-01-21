<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-credit-card"></span> <?php echo lang("ctn_1447") ?></div>
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
        </ul>
      </div><!-- /btn-group -->
</div>
</div>
   
<?php if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) : ?>
    <a href="<?php echo site_url("invoices/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_587") ?></a>
  <?php endif; ?> <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_1462") ?></button>
</div>
</div>

<div class="table-responsive">
<table id="invoices-table" class="table small-text table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_271") ?></td><td><?php echo lang("ctn_1450") ?></td><td><?php echo lang("ctn_617") ?></td><td><?php echo lang("ctn_753") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_1429") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("invoices/add_item"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="description">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1450") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="price">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_617") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="quantity">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_753") ?></label>
                    <div class="col-md-8">
                        <select name="projectid" class="form-control">
                          <option value="0"><?php echo lang("ctn_990") ?></option>
                          <?php foreach($projects->result() as $r) : ?>
                              <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="help-text"><?php echo lang("ctn_1461") ?></span>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_1462") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#invoices-table').DataTable({
        "dom" : "B<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        buttons: [
          { "extend": 'copy', "text":'<?php echo lang("ctn_1551") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'csv', "text":'<?php echo lang("ctn_1552") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'excel', "text":'<?php echo lang("ctn_1553") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'pdf', "text":'<?php echo lang("ctn_1554") ?>',"className": 'btn btn-default btn-sm' },
          { "extend": 'print', "text":'<?php echo lang("ctn_1555") ?>',"className": 'btn btn-default btn-sm' }
        ],
        "order": [
          [0, "desc" ]
        ],
        "columns": [
        null,
        { "orderable": false },
        null,
        null,
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("invoices/item_page") ?>",
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
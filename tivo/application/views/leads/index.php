<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-blackboard"></span> <?php echo lang("ctn_811") ?></div>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="form-exact"></span> <?php echo lang("ctn_812") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_357") ?></a></li>
          <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="assigned-exact"></span> <?php echo lang("ctn_794") ?></a></li>
          <li><a href="#" onclick="change_search(5)"><span class="glyphicon glyphicon-ok no-display" id="ip-exact"></span> <?php echo lang("ctn_813") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

<a href="<?php echo site_url("leads/forms") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1353") ?></a>

</div>
</div>

<div class="table-responsive">
<table id="quotes-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_812") ?></td><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_1110") ?></td><td><?php echo lang("ctn_545") ?></td><td><?php echo lang("ctn_1349") ?></td><td><?php echo lang("ctn_814") ?></td><td><?php echo lang("ctn_813") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody class="small-text">
</tbody>
</table>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#quotes-table').DataTable({
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
          [4, "desc" ]
        ],
        "columns": [
        null,
        { "orderable": false },
        { "orderable": false },
        null,
        null,
        null,
        { "orderable": false },
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("leads/lead_page/" . $page) ?>",
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
    		"form-exact",
    		"user-exact",
    		"assigned-exact",
    		"ip-exact"
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
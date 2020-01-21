<div class="table-responsive">
<table id="tickets-table" class="table small-text table-bordered table-striped table-hover">
<thead>
<tr class="table-header small-text"><td><?php echo lang("ctn_514") ?></td><td><?php echo lang("ctn_553") ?></td><td><?php echo lang("ctn_926") ?></td><td><?php echo lang("ctn_935") ?></td><td width="40"><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_925") ?></td><td><?php echo lang("ctn_944") ?></td><td width="130"><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#tickets-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [6, "desc" ]
        ],
        "columns": [
        null,
        null,
        null,
        { "orderable": false },
        { "orderable": false },
        { "orderable": false },
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("tickets/tickets_page/0/" . $page . "/" . $user->ID) ?>",
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
        "title-exact",
        "user-exact",
        "assigned-exact"
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

<div class="table-responsive">
<table id="projects-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td width="50"><?php echo lang("ctn_734") ?></td><td><?php echo lang("ctn_767") ?></td><td><?php echo lang("ctn_775") ?></td><td><?php echo lang("ctn_703") ?></td><td><?php echo lang("ctn_771") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>
<script type="text/javascript">
$(document).ready(function() {   
    

    var st = $('#search_type').val();

    var table = $('#projects-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order" : [],
        "columns": [
        { "orderable": false },
        null,
        null,
        { "orderable": false },
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("projects/projects_page/" . $page . "/0?userid=" . $user->ID) ?>",
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
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_1527") ?></div>
    <div class="db-header-extra form-inline"> <a href="<?php echo site_url("documentation/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1536") ?></a>

<div class="btn-group" role="group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	<?php if($project == null) : ?>
        <?php $projectid = 0; ?>
		     <?php echo lang("ctn_470") ?>
		  <?php else : ?>
        <?php $projectid = $project->ID; ?>
		  	<?php echo $project->name ?>
		  <?php endif; ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
    	<?php if($projects->num_rows() == 0) : ?>
    		<li><a href="<?php echo site_url("projects") ?>"><?php echo lang("ctn_1529") ?></a></li>
    	<?php else : ?>
    		<li><a href="<?php echo site_url("documentation/index/-1") ?>"><?php echo lang("ctn_1000"); ?></a></li>
	      <?php foreach($projects->result() as $r) : ?>
	      	<li><a href="<?php echo site_url("documentation/index/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
	      <?php endforeach; ?>
	  <?php endif; ?>
    </ul>
  </div>
   
</div>
</div>

<div class="table-responsive">
<table id="cat-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_11") ?></td><td><?php echo lang("ctn_456") ?></td><td><?php echo lang("ctn_564") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>
<script type="text/javascript">
$(document).ready(function() {
   var st = $('#search_type').val();
    var table = $('#cat-table').DataTable({
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
        	[2,'desc']
        ],
        "columns": [
        null,
        null,
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("documentation/documentation_page/" . $projectid) ?>",
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
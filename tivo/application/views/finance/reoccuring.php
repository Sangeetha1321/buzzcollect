<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_1559"); ?></div>
    <div class="db-header-extra form-inline">

     <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_448") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("finance/" . $page) ?>"><?php echo lang("ctn_449") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <li><a href="<?php echo site_url("finance/".$page."/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
</div>

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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_514") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="project-exact"></span> <?php echo lang("ctn_517") ?></a></li>
          <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="category-exact"></span> <?php echo lang("ctn_516") ?></a></li>
          <li><a href="#" onclick="change_search(5)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_492") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

     <a href="<?php echo site_url("finance/add_finance") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_513") ?></a>
</div>
</div>

<div class="table-responsive">
<table id="finance-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_514") ?></td><td><?php echo lang("ctn_518") ?></td><td><?php echo lang("ctn_517") ?></td><td><?php echo lang("ctn_545"); ?></td><td><?php echo lang("ctn_1560"); ?></td><td><?php echo lang("ctn_645"); ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>



</div>
<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#finance-table').DataTable({
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
        ],
        "columns": [
        null,
        null,
        null,
        null,
        { "orderable": false },
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("finance/reoccur_finance_page/" . $projectid) ?>",
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
        "project-exact",
        "category-exact",
        "user-exact",
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
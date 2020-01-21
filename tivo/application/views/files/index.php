<style>
table#files-table{text-transform:uppercase;}
table#files-table tr td:last-child {
    width: 73px !important;
}
/* table#files-table tr td {
    width: 50px !important;
} */
#files-table tr.table-header td {
    padding: 8px 1px;
}
.dt-buttons{display:none;}
#files-table tr.table-header td:nth-child(1) {
    width: 66px !important;
}
/* #files-table tr.table-header td:nth-child(3) {
    width: 75px !important;
} */
</style>
<div class="white-area-content">
<div class="db-header clearfix">
    <!-- <div class="page-header-title" style="margin-right: 30px;"> 
		<span class="glyphicon glyphicon-file"></span> 
		<?php echo "File Monitoring"; ?>
	</div> -->
	<div class="page-header-title" style="margin-right:3px;"> 
		<span class="glyphicon glyphicon-filter" style="margin-right:4px;"></span>  
	</div> 
	<a href="<?php echo site_url("files/".$page."/0") ?>" class="btn btn-round btn-sm"><?php echo "All Files"; ?></a> 
	<?php foreach($project_status->result() as $r) : ?>
	<a href="<?php echo site_url("files/".$page."/0/-1/" . $r->ID) ?>" class="btn btn-round btn-sm" style="border-color: #<?php echo $r->status_color ?>; color: #<?php echo $r->status_color ?>"><?php echo $r->status_name ?><span class="badge" style="background: #<?php echo $r->status_color ?>;"><?php echo $r->status_count; ?></span></a> 
	<?php endforeach; ?>
</div>
<!--
<ol class="breadcrumb">
  <?php if(count($folders) == 0) : ?>
  <li class="active"><?php echo lang("ctn_487") ?></li>
<?php else : ?>
	<li><a href="<?php echo site_url("files") ?>"><?php echo lang("ctn_487") ?></a></li>
<?php endif; ?>
  <?php foreach($folders as $folder) : ?>
  	<?php if($folder->ID == $folder_parent) : ?>
  		<li class="active"><?php echo $folder->file_name ?></li>
  	<?php else : ?>
  		<li><a href="<?php echo site_url("files/".$page."/" . $folder->ID) ?>"><?php echo $folder->file_name ?></a></li>
  	<?php endif; ?>
  <?php endforeach; ?>
</ol> -->

<!-- <div class="file_convert">
	<div class="file_monitoring"> 
		<div class="row">
		<?php  foreach($project_status->result() as $prostat){ ?>
			<div class="col-md-1">
				<div class="file-window clearfix" style="background: <?php echo '#'.$prostat->status_color.';'; ?>">
					<div class="d-w-text monitor_files">
						<span class="d-w-num"><?php echo $prostat->status_count; ?></span>
						<br><?php echo $prostat->status_name; ?>  
					</div>
				</div>
			</div>
		<?php } ?>  
		</div>
	</div>
</div> -->

<div class="table-responsive">
<div class="db-header-extra form-inline" style="float:left;" > 
	<div class="btn-group">
		<div class="dropdown">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<?php echo lang("ctn_448") ?>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				<li><a href="<?php echo site_url("files/" . $page) ?>"><?php echo lang("ctn_449") ?></a></li>
				<li><a href="<?php echo site_url("files/" . $page . "/0/0") ?>"><?php echo lang("ctn_483") ?></a></li>
				<?php foreach($projects->result() as $r) : ?>
				<li><a href="<?php echo site_url("files/".$page."/0/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

    <div class="form-group has-feedback no-margin">
		<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="Search ..." id="form-search-input" />
			<div class="input-group-btn">
				<input type="hidden" id="search_type" value="0">
					<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					<ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
					  <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
					  <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
					  <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="name-exact"></span> <?php echo lang("ctn_484") ?></a></li>
					  <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="type-exact"></span> <?php echo lang("ctn_485") ?></a></li>
					  <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_357") ?></a></li>
					</ul>
				  </div><!-- /btn-group -->
		</div>
	</div>
<!-- <a href="<?php echo site_url("files/add_file/" . $folder_parent) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_486") ?></a> -->
</div>
<a style="float:right;" id="export_csv" class="btn btn-primary" href="<?php echo site_url('files/exportallfiles')?>" title="Export Csv">
	Export CSV
</a>
<table id="files-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
	<!-- <td><?php echo lang("ctn_489") ?></td>
	<td><?php echo lang("ctn_490") ?></td> -->
	<td><?php echo lang("ctn_491") ?></td>
	<td><?php echo "File Format"; ?></td>
	<td><?php echo "Team"; ?></td>
	<td><?php echo "Status"; ?></td>
	<!-- <td><?php echo "File Name"; ?></td> -->
	<td><?php echo "Reporter"; ?></td>
	<td><?php echo "Created Date"; ?></td>
	<td><?php echo lang("ctn_52") ?></td>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo "Comment and Delete" ?></h4>
      </div>
      <div class="modal-body">
		<?php echo form_open_multipart(site_url("files/all"), array("class" => "form-horizontal")) ?>
		<div class="form-group">
			<label for="p-in" class="col-md-4 label-heading"><?php echo "File Comment"; ?></label>
			<div class="col-md-8 ui-front">
				<textarea required class="form-control" name="file_comment" id="file_comment"></textarea>
				<input type="hidden" id="ridfiles"></input>
			</div>
		</div>				
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
          <input id="but_filecomment" type="submit" class="btn btn-primary" value="<?php echo "Submit"; ?>">
          <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    var st = $('#search_type').val();
    var table = $('#files-table').DataTable({
        "dom" : "B<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 50,
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
          [5, "desc" ]
        ],
        "columns": [ 
        null,
        { "orderable": false },
        null,
        null, 
		null,
        null,
        { "orderable": false }
		],
        "ajax": {
            url : "<?php echo site_url("files/file_page/" . $page . "/" . $folder_parent . "/" . $projectid . "/" . $filter ) ?>",
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
});
function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "name-exact",
        "type-exact",
        "user-exact"
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
<script type="text/javascript">
$(document).on("click",".file_del",function(){   
	var ridfile = this.id;
	$("#ridfiles").val(ridfile);
});
$(document).on("click","#but_filecomment",function(){   
    	$.ajax({
	        url : "<?php echo site_url("files/del_comment") ?>",
	        type : 'GET',
	        data : { 
	          dl : $(".file_del").attr('dl'),
	          dlhash : $(".file_del").attr('dlhash'),
	          flcomment : $("#file_comment").val(),
	          rid : $("#ridfiles").val(),
	        },
	        dataType: 'JSON',
	        success: function(data) {
				console.log(data);  
				/* bootbox.alert(data); */
	        },
			error : function(data) {
				console.log('error'); 
			}
	    });
}); 
</script>	
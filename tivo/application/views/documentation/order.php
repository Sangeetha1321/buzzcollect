<script type="text/javascript">
   $(document).ready(function() { 
    $( "#sortable" ).sortable({
    	update: function(event, ui) {
    		var data = $(this).sortable('serialize');
    		var projectid = $('#projectid').val();
	        $.ajax({
	            data: data,
	            type: 'GET',
	            url: global_base_url + 'documentation/update_order/' + projectid
	        });
    	}
    });
    $( "#sortable" ).disableSelection();
});
</script>
<style type="text/css">
#sortable { margin: 0px; padding: 0px; }
#sortable li { list-style: none; border: 1px solid #DDD; padding: 10px; margin: 5px; background: #f6f6f6; }
.sortable-link { float: right; }
</style>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_1527") ?></div>
    <div class="db-header-extra form-inline"> <a href="<?php echo site_url("documentation/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1536") ?></a>

   
</div>
</div>

<input type="hidden" id="projectid" value="<?php echo $project->ID ?>" />
<h3><?php echo lang("ctn_1548") ?> <b><?php echo $project->name ?></b></h3>

<p><?php echo lang("ctn_1549") ?></p>

<ul id="sortable">
<?php foreach($documents->result() as $r) : ?>
  <li id="document-<?php echo $r->ID ?>"><span class="glyphicon glyphicon-resize-vertical"></span> <?php echo $r->title ?> <div class="sortable-link"><a href="<?php echo site_url("documents/edit/" . $r->ID) ?>"><?php echo lang("ctn_55") ?></a></div></li>
<?php endforeach; ?>
</ul>

</div>
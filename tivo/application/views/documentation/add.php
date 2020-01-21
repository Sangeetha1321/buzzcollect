<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_1527") ?></div>
    <div class="db-header-extra form-inline"> <a href="<?php echo site_url("documentation/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1536") ?></a>

   
</div>
</div>



<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("documentation/add_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_1538") ?></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="p-in" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_1539") ?></label>
                    <div class="col-md-10">
                        <textarea name="document" id="document-area"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_456") ?></label>
                    <div class="col-md-10">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <hr>
            <h3><?php echo lang("ctn_1540") ?></h3>
            <div id="files">
            <div class="form-group">
                    <label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_465") ?> #1</label>
                    <div class="col-md-10">
                        <input type="file" name="userfile_1" class="form-control">
                    </div>
            </div>
            </div>
            <input type="hidden" name="file_count" value="1" id="file_count" />
            <input type="button" class="btn btn-info btn-sm" value="<?php echo lang("ctn_941") ?>" onclick="add_file()">
            <hr>
            <h3><?php echo lang("ctn_1541") ?></h3>
            <p><?php echo lang("ctn_1542") ?></p>
            <div class="form-group">
                    <label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_470") ?></label>
                    <div class="col-md-6">
                        <select name="projectid_link" class="form-control" id="document-search">
                        <option value="0"><?php echo lang("ctn_1543") ?></option>
                            <?php foreach($projects->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select> 
                    </div>
            </div>
            <div class="form-group" id="link_documents">
                    <label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_1544") ?></label>
                    <div class="col-md-6">
                        <select name="link_documentid" class="form-control">
                        <option value="0"><?php echo lang("ctn_1545") ?></option>
                        </select> 
                    </div>
            </div>
           

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1536") ?>" />
            <?php echo form_close() ?>
</div>
</div>


</div>


<script type="text/javascript">
CKEDITOR.replace('document-area', { height: '300'});

function add_file() 
{
	var count = $('#file_count').val();
	count++;
	$('#files').append('<div class="form-group">'
                    +'<label for="p-in" class="col-md-2 label-heading"><?php echo lang("ctn_465") ?> #'+count+'</label>'
                    +'<div class="col-md-10">'
                    +'<input type="file" name="userfile_'+count+'" class="form-control">'
                    +'</div>'
            +'</div>');
	$('#file_count').val(count);
}

$(document).ready(function() { 
  /* Get list of usernames */
  $('#document-search').change(function() {
    var projectid = $('#document-search').val();
    $.ajax({
      url: global_base_url + 'documentation/get_documents/' + projectid,
      type: 'GET',
      success: function(msg) {
        $('#link_documents').html(msg);
      }
    })
  });
});

</script>
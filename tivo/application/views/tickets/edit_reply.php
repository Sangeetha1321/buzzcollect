<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_922") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<h4>Edit Ticket Reply</h4>
<?php echo form_open_multipart(site_url("tickets/edit_ticket_reply_pro/" . $reply->ID), array("class" => "form-horizontal")) ?>
<p><textarea name="body" id="ticket-body"><?php echo $reply->body ?></textarea></p>
<?php if(!$this->settings->info->disable_ticket_upload) : ?>
                <hr>
                <h4><?php echo lang("ctn_939") ?></h4>
                <input type="hidden" name="file_count" value="1" id="file_count">
                <div id="file_block">
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_940") ?></label>
                        <div class="col-md-8">
                            <input type="file" name="user_file_1" class="form-control">
                        </div>
                </div>
                </div>
                <input type="button" name="s" value="<?php echo lang("ctn_941") ?>" class="btn btn-info btn-xs" onclick="add_file()">
                <hr>
            <?php endif; ?>
<p><input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_973") ?>"></p>
<?php echo form_close() ?>
</div>


<script type="text/javascript">
CKEDITOR.replace('ticket-body', { height: '100'});

function add_file() 
{
    var count = $('#file_count').val();
    count++;
    var html = '<div class="form-group">'+
                    '<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_940") ?></label>'+
                    '<div class="col-md-8">'+
                        '<input type="file" name="user_file_'+count+'" class="form-control">'+
                    '</div>'+
            '</div>';
    $('#file_block').append(html);
    $('#file_count').val(count);
}
</script>
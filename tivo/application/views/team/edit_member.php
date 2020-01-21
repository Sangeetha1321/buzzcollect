<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_887") ?></div>
    <div class="db-header-extra"> 
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("team/edit_member_pro/" . $team->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_878") ?></label>
                    <div class="col-md-8 ui-front">
                        <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $team->avatar ?>" class="user-icon" /> <?php echo $team->username ?>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $team->projectid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_888") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="roleid" class="form-control">
                        <?php foreach($roles->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $team->roleid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
			<!-- <div class="form-group">
					<label for="reporting" class="col-md-4 label-heading"><?php echo lang("ctn_1576") ?></label>
					<div class="col-md-8 ui-front">
						<select name="reporting" class="form-control">
                        <?php foreach($reporting as $r) : ?>
                        	<option value="<?php echo $r['reporting'] ?>" <?php if($r['reporting'] == $current_reporter) echo "selected" ?>><?php echo $r['reporting'] ?></option>
                        <?php endforeach; ?>
                        </select>
					</div>
			</div> -->
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_889") ?>">
<?php echo form_close() ?>
</div>

</div>
</div>
<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("admin/member_user_groups/" . $member->ID) ?>" class="btn btn-info btn-sm">User Groups</a>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/members") ?>"><?php echo lang("ctn_21") ?></a></li>
  <li class="active"><?php echo lang("ctn_22") ?></li>
</ol>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<p><?php echo lang("ctn_23") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("admin/edit_member_pro/" . $member->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
	<label for="employee-id" class="col-md-3 label-heading"><?php echo lang("ctn_1571") ?></label>
	<div class="col-md-9">
		<input type="text" class="form-control required digits" id="employee-id" name="employee_id" maxlength="6" value="<?php echo $member->employee_id ?>">
	</div>
</div>
<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_24") ?></label>
        <div class="col-md-9">
            <input type="email" class="form-control" id="email-in" name="email" value="<?php echo $member->email ?>">
        </div>
</div>
<div class="form-group">

            <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_25") ?></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $member->username ?>">
                <div id="username_check"></div>
            </div>
</div>
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_26") ?></label>
        <div class="col-sm-9">
        <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $member->avatar ?>" />
            <input type="file" name="userfile" /> 
        </div>
    </div>
<div class="form-group">

            <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_27") ?></label>
            <div class="col-md-9">
                <input type="password" class="form-control" id="password-in" name="password" value="">
                <span class="help-text"><?php echo lang("ctn_28") ?></span>
            </div>
    </div>

<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_29") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name-in" name="first_name" value="<?php echo $member->first_name ?>">
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_30") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name-in" name="last_name" value="<?php echo $member->last_name ?>">
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_327") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name-in" name="credits" value="<?php echo $member->points ?>">
        </div>
</div>
<div class="form-group">
	<label for="designation" class="col-md-3 label-heading"><?php echo lang("ctn_1572") ?></label>
	<div class="col-md-9">
	<input type="text" class="form-control required" id="designation" name="designation" maxlength="128" value="<?php echo $member->designation ?>">
	</div>
</div> 
<!-- <div class="form-group">
	<label for="department" class="col-md-3 label-heading"><?php echo lang("ctn_1573") ?></label>
	<div class="col-md-9">
	<input type="text" class="form-control required" id="department" name="department" maxlength="128" value="<?php echo $member->department ?>">
	</div>
</div> -->
<div class="form-group">
	<label for="category" class="col-md-3 label-heading"><?php echo lang("ctn_1574") ?></label>
	<div class="col-md-9">
	<input type="text" class="form-control required" id="category" name="category" maxlength="128" value="<?php echo $member->category ?>">
	</div>
</div> 
<div class="form-group">
	<label for="doj" class="col-md-3 label-heading"><?php echo lang("ctn_1575") ?></label>
	<div class="col-md-9">
		<input name="doj" class="form-control datepicker" id="doj" type="text" value="<?php echo $member->doj ?>">
	</div>
</div>	
<div class="form-group">
	<label for="reporting" class="col-md-3 label-heading"><?php echo lang("ctn_1576") ?></label>
	<div class="col-md-9">
		<input type="text" class="form-control required" id="reporting" name="reporting" maxlength="128" value="<?php echo $member->reporting ?>">
	</div>
</div> 
<div class="form-group">
	<label for="team_name" class="col-md-3 label-heading"><?php echo lang("ctn_1626") ?></label>
	<div class="col-md-9">
            <select name="team_name" class="form-control">
            <?php foreach($team_name->result() as $r) : ?>
            	<option value="<?php echo $r->team_name ?>" <?php if($r->team_name == $member->team_name) echo "selected" ?>><?php echo $r->team_name ?></option>
            <?php endforeach; ?>
            </select>
    </div>
</div> 
<div class="form-group">
	<label for="gender" class="col-md-3 label-heading"><?php echo lang("ctn_1577") ?></label>
	<div class="col-md-9 radio">
		<label>
			<input name="gender" id="male" value="male" type="radio"  <?php if(($member->gender == 'male')){echo "checked=checked";} ?>>
			Male
		</label>
		<label>
			<input name="gender" id="female" value="female" type="radio"  <?php if(($member->gender == 'female')){echo "checked=checked";} ?>>
			Female
		</label>
	</div>
</div>  
<div class="form-group">
	<label for="mobile" class="col-md-3 label-heading"><?php echo lang("ctn_1578") ?></label>
	<div class="col-md-9">
	<input type="text" class="form-control required digits" id="mobile" name="mobile" maxlength="10" value="<?php echo $member->mobile ?>">
	</div>
</div>	
<div class="form-group">
	<label for="bloodGroup" class="col-md-3 label-heading"><?php echo lang("ctn_1579") ?></label>
	<div class="col-md-9">
	<!-- <input type="text" class="form-control required" name="bloodGroup" maxlength="128" value="<?php echo $member->bloodGroup ?>">
	-->
	<select name="bloodGroup" class="form-control">
		<option value="<?php echo "A+"; ?>" <?php if($member->bloodGroup == 'A+') echo "selected" ?>>
		<?php echo "A+"; ?>
		</option>
		<option value="<?php echo "O+"; ?>" <?php if($member->bloodGroup == 'O+') echo "selected" ?>>
		<?php echo "O+"; ?>
		</option>
		<option value="<?php echo "B+"; ?>" <?php if($member->bloodGroup == 'B+') echo "selected" ?>>
		<?php echo "B+"; ?>
		</option>
		<option value="<?php echo "AB+"; ?>" <?php if($member->bloodGroup == 'AB+') echo "selected" ?>>
		<?php echo "AB+"; ?>
		</option>
		<option value="<?php echo "A-"; ?>" <?php if($member->bloodGroup == 'A-') echo "selected" ?>>
		<?php echo "A-"; ?>
		</option>
		<option value="<?php echo "O-"; ?>" <?php if($member->bloodGroup == 'O-') echo "selected" ?>>
		<?php echo "O-"; ?>
		</option>
		<option value="<?php echo "B-"; ?>" <?php if($member->bloodGroup == 'B-') echo "selected" ?>>
		<?php echo "B-"; ?>
		</option>
		<option value="<?php echo "AB-"; ?>" <?php if($member->bloodGroup == 'AB-') echo "selected" ?>>
		<?php echo "AB-"; ?>
		</option>
    </select>
	</div>
</div>  

<div class="form-group">
	<label for="skillSet" class="col-md-3 label-heading"><?php echo lang("ctn_1580") ?></label>
	<div class="col-md-9">
	<select name="skillSet[]" multiple="multiple" class="form-control">
	<?php
		$target = explode(",",$member->skillSet);
		$arr = [];
		foreach($target as $tar){
			$arr[] = trim($tar,"Average Good Excellent");
		} 
		$total = json_decode(json_encode($member_skills->result()), true);
		$skillSets = (array_column($total, 'skills'));  	
		foreach ($skillSets as $needle)
		{
			if (in_array($needle, $arr)){ $selected = 'selected="selected"'; }
			else{ $selected = '';}
			echo "<option value='$needle' $selected>$needle</option>"; 
		} 
	?>
    </select>
	</div>
</div>  

<div class="form-group">
	<label for="skillSet" class="col-md-3 label-heading"><?php echo lang("ctn_1580") ?></label>
	<div class="col-md-9">
	<select name="langOptgroup[]" multiple="multiple" id="langOptgroup">
	<!-- <option value="<?php echo $needle?>" <?php echo $selected; ?>><?php echo $needle?></option>		
		 -->
		<?php 
		$avg_skills = [];
		foreach($member_skills->result() as $r) :
			$avg_skills[] = $r->skills.'-Average';
		endforeach; 
		$good_skills = [];
		foreach($member_skills->result() as $r) :
			$good_skills[] = $r->skills.'-Good';
		endforeach; 
		$exc_skills = [];
		foreach($member_skills->result() as $r) :
			$exc_skills[] = $r->skills.'-Excellent';
		endforeach; 
		$res = array_merge($avg_skills, $good_skills, $exc_skills);
		$target = explode(",",$member->skillSet);
		 /* print_r($member_skills->result()); 
		 print_r($target); */ 
		?>
		<?php foreach($member_skills->result() as $r){ 
			foreach ($res as $needle){
				if(in_array($needle, $target)){ 
					if(strpos($needle, 'Average') !== false) {
						$choptext = trim($needle,"Average");
						$fulltext = $needle;
					}elseif(strpos($needle, 'Good') !== false) {
						$choptext = trim($needle,"Good"); 
						$fulltext = $needle;
					}else{
						$choptext = trim($needle,"Excellent");
						$fulltext = $needle;
					}	  
					
					/* echo $choptext.'=>'.$r->skills;
					echo "<br>"; */
					if($r->skills.'-' == $choptext){  
					?>
						<optgroup label="<?php echo $r->skills ?>">
							<option value="<?php echo $r->skills.'-Average'; ?>" <?php if($r->skills.'-' == $choptext){ if($fulltext == $r->skills.'-Average'){echo 'selected="selected"';}} ?>>Average</option>
					<option value="<?php echo $r->skills.'-Good'; ?>" <?php if($r->skills.'-' == $choptext){ if($fulltext == $r->skills.'-Good'){echo 'selected="selected"';}} ?>>Good</option>
				<option value="<?php echo $r->skills.'-Excellent'; ?>" <?php if($r->skills.'-' == $choptext){ if($fulltext == $r->skills.'-Excellent'){echo 'selected="selected"';} }?>>Excellent</option>
						</optgroup> 
					<?php 
				    }
					else{
						echo $r->skills;
					}
				}			
			} 
		} ?>
	</select> 
	</div>
</div> 
<div class="form-group">
	<label for="education" class="col-md-3 label-heading"><?php echo lang("ctn_1598") ?></label>
	<div class="col-md-9">
	<input type="text" class="form-control required" name="education" maxlength="128" value="<?php echo $member->education ?>">
	</div>
</div> 	
<div class="form-group">
        <label for="status_catid" class="col-md-3 label-heading"><?php echo lang("ctn_776") ?></label>
        <div class="col-md-9">
            <select name="status_catid" class="form-control">
            <?php foreach($member_categories->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $member->cat_stat_id) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_31") ?></label>
        <div class="col-sm-9">
          <textarea class="form-control" name="aboutme" rows="8"><?php echo nl2br($member->aboutme) ?></textarea>
        </div>
</div>
<h4><?php echo lang("ctn_1273") ?></h4>
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_429") ?></label>
        <div class="col-sm-9">
          <input type="text" name="address_1" class="form-control" value="<?php echo $member->address_1 ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_430") ?></label>
        <div class="col-sm-9">
          <input type="text" name="address_2" class="form-control" value="<?php echo $member->address_2 ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_431") ?></label>
        <div class="col-sm-9">
          <input type="text" name="city" class="form-control" value="<?php echo $member->city ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_432") ?></label>
        <div class="col-sm-9">
          <input type="text" name="state" class="form-control" value="<?php echo $member->state ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_433") ?></label>
        <div class="col-sm-9">
          <input type="text" name="zipcode" class="form-control" value="<?php echo $member->zipcode ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_434") ?></label>
        <div class="col-sm-9">
          <input type="text" name="country" class="form-control" value="<?php echo $member->country ?>">
        </div>
    </div>
    <h4><?php echo lang("ctn_714") ?></h4>
    <?php foreach($fields->result() as $r) : ?>
            <div class="form-group">

                <label for="name-in" class="col-sm-3 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                <div class="col-sm-9">
                    <?php if($r->type == 0) : ?>
                        <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
                    <?php elseif($r->type == 1) : ?>
                        <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
                    <?php elseif($r->type == 2) : ?>
                         <?php $options = explode(",", $r->options); ?>
                         <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 3) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 4) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        <?php if(count($options) > 0) : ?>
                            <select name="cf_<?php echo $r->ID ?>" class="form-control">
                            <?php foreach($options as $k=>$v) : ?>
                            <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="help-text"><?php echo $r->help_text ?></span>
                </div>
        </div>
    <?php endforeach; ?>
    <p>* = <?php echo lang("ctn_957") ?></p>
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_328") ?></label>
        <div class="col-sm-9">
          <select name="active" class="form-control">
          <option value="0"><?php echo lang("ctn_329") ?></option>
          <option value="1" <?php if($member->active) echo "selected" ?>><?php echo lang("ctn_330") ?></option>
          </select>
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_322") ?></label>
        <div class="col-md-9">
            <select id="user_role" name="user_role" class="form-control">
            <option value="0" <?php if($member->user_role == 0) echo "selected" ?>><?php echo lang("ctn_471") ?></option>
            <?php foreach($user_roles->result() as $r) : ?>
                <option value="<?php echo $r->ID ?>" <?php if($member->user_role == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_36") ?></label>
        <div class="col-md-9">
            <?php echo lang("ctn_37") ?> : <?php echo $member->IP ?> <br />
            <?php echo lang("ctn_38") ?> : <?php echo date($this->settings->info->date_format, $member->joined) ?><br />
            <?php echo lang("ctn_39") ?> : <?php echo date($this->settings->info->date_format, $member->online_timestamp) ?>
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_40") ?></label>
        <div class="col-md-9">
            <?php foreach($user_groups->result() as $r) : ?>
                <p><a href="<?php echo site_url("admin/view_group/" . $r->groupid) ?>"><?php echo $r->name ?></a></p>
            <?php endforeach; ?>
        </div>
</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>
<script type="text/javascript">
$('#langOptgroup').fSelect();
</script>
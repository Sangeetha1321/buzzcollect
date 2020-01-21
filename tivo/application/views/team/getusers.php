<?php
echo $_POST["teamname"];
echo $_GET["teamname"];
echo $_REQUEST["teamname"];
echo "test";
$get_string = "pg_id=2&parent_id=2&document&video";
parse_str($get_string, $get_array);
print_r($get_array);
die;
if(!empty($_POST["teamname"])) {
	$string_version = "'" . implode( "','", $_POST["teamname"]) . "'";
	$sql ="SELECT username,team_name FROM users WHERE team_name IN (" . $string_version . ")";
	$users = $this->db->query($sql);
		if ($users->num_rows() > 0) { 
			?>
				<option value="">Select User(s)</option>
			<?php
				foreach($users->result() as $r) {
			?>
				<!-- <option value="<?php echo $r->username; ?>"><?php echo $r->username; ?></option> -->
			<?php
 				}
		}
} 
?>
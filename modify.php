<?php
function db_iconnect($dbName)
{
	$un="grader";
	$pw="jUmqCtPbRlLi.2BX";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
$dblink=db_iconnect("test");
if(isset($_POST["Manufacture"]) && $_POST["Manufacture"] == "Manufacture") {
	echo '<form method="post" action="">';
	$sql="Select distinct(`manufacture`) from `Manufacture`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<select name="manufacture">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<p>Type in a name to modify the selected Manufacturer and press enter</p>';
	echo '<input type="text" size="50" maxlength="32" name="ModManu">';
	echo '<p>Inactive: ';
	echo '<input type="radio" name="inactive_manu" value="true"';
	echo '</p>';
	echo '<br><br>';
	echo '<button type="submit">Submit</button>';
	echo '</form>';
}
else if(isset($_POST["Type"]) && $_POST["Type"] == "Type") {
	echo '<form method="post" action="">';
	$sql="Select distinct(`type`) from `Type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<select name="type">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<p>Type in a name to modify the selected Type and press enter</p>';
	echo '<input type="text" size="50" maxlength="32" name="ModType">';
	echo '<p>Inactive: ';
	echo '<input type="radio" name="inactive_manu" value="true"';
	echo '</p>';
	echo '<br><br>';
	echo '<button type="submit">Submit</button>';
	echo '</form>';
}
else if(isset($_POST["Device"]) && $_POST["Device"] == "Device") {
	echo '<form method="post" action="">';
	$sql="Select distinct(`type`) from `Type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<p>Type in a SN to modify the Type and Manufacturer for that SN</p>';
	echo '<input type="text" size="50" maxlength="32" name="ModDevice">';
	echo '<p>Inactive: ';
	echo '<input type="radio" name="inactive_manu" value="true"';
	echo '</p>';
	echo '<p>Type</p>';
	echo '<select name="type">';
	echo '<option value="NULL">No Change</option>';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	$sql="Select distinct(`manufacture`) from `Manufacture`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<p><br>Manufacturer</p>';
	echo '<select name="manufacture">';
	echo '<option value="NULL">No Change</option>';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<br><br>';
	echo '<button type="submit">Submit</button>';
	echo '</form>';
}
else if(isset($_POST["ModType"])){
	echo '<input type="hidden" name="ModManu" value='.$_POST['ModType'].'/>';
	echo '<input type="hidden" name="type" value='.$_POST['type'].'/>';
	echo '<input type="hidden" name="inactive_manu" value='.$_POST['inactive_manu'].'/>';
	$mod = $_POST['ModType'];
	$type = $_POST["type"];
	$status = $_POST["inactive_manu"];
	$time_start=microtime(true);
	if($status == "true") {
		$sql="Select `id` from `Type` where `type`='$type'";
		$result=$dblink->query($sql);
		$id=$result->fetch_array()[0];
		$sql="Insert into `InactiveType` values ('$id')";
		$result=$dblink->query($sql);
	}
	else {
		$sql="Select `id` from `Type` where `type`='$type'";
		$result=$dblink->query($sql);
		$id=$result->fetch_array()[0];
		$sql="Delete from `InactiveType` where `id`='$id'";
		$result=$dblink->query($sql);
	}
	if($mod!=null) {
		$sql="Update `Type` set `type`='$mod' where `type`='$type'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo '<h3>Type has been modified</h3>';
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST["ModManu"])){
	echo '<input type="hidden" name="ModManu" value='.$_POST['ModManu'].'/>';
	echo '<input type="hidden" name="manufacture" value='.$_POST['manufacture'].'/>';
	echo '<input type="hidden" name="inactive_manu" value='.$_POST['inactive_manu'].'/>';
	$mod = $_POST['ModManu'];
	$manu = $_POST["manufacture"];
	$status = $_POST["inactive_manu"];
	$time_start=microtime(true);
	if($status == "true") {
		$sql="Select `id` from `Manufacture` where `manufacture`='$manu'";
		$result=$dblink->query($sql);
		$id=$result->fetch_array()[0];
		$sql="Insert into `InactiveManu` values ('$id')";
		$result=$dblink->query($sql);
	}
	else {
		$sql="Select `id` from `Manufacture` where `manufacture`='$manu'";
		$result=$dblink->query($sql);
		$id=$result->fetch_array()[0];
		$sql="Delete from `InactiveManu` where `id`='$id'";
		$result=$dblink->query($sql);
	}
	if($mod!=null) {
	$sql="Update `Manufacture` set `manufacture`='$mod' where `manufacture`='$manu'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo '<h3>Manufacturer has been modified</h3>';
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST["ModDevice"])){
	echo '<input type="hidden" name="ModDevice" value='.$_POST['ModDevice'].'/>';
	echo '<input type="hidden" name="type" value='.$_POST['type'].'/>';
	echo '<input type="hidden" name="manufacture" value='.$_POST['manufacture'].'/>';
	echo '<input type="hidden" name="inactive_manu" value='.$_POST['inactive_manu'].'/>';
	$sn = $_POST['ModDevice'];
	$type = $_POST["type"];
	$manu = $_POST["manufacture"];
	$status = $_POST["inactive_manu"];
	$time_start=microtime(true);
	$sql="Select * from `equipment` where `serial_num`='$sn'";
	$result=$dblink->query($sql);
	$current=$result->fetch_array();
	if($current == null) {
		echo '<h3>Serial Number does not exist, either create it first or type in another serial number</h3>';
		die();
	}
	if($manu=="NULL") {
		$manu=$current[2];
	}
	else {
		$sql="Select `id` from `Manufacture` where `manufacture`='$manu'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$manu=$result->fetch_array()[0];
	}
	if($type=="NULL") {
		$type=$current[1];
	}
	else {
		$sql="Select `id` from `Type` where `type`='$type'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$type=$result->fetch_array()[0];
	}
	$id=$current[0];
	if($status == "true") {
		$sql="Insert into `InactiveDevice` values ('$id')";
		$result=$dblink->query($sql);
	}
	else {
		$sql="Delete from `InactiveDevice` where `id`='$id'";
		$result=$dblink->query($sql);
	}
	$sql="Update `equipment` set `type_id`='$type', `manufacture_id`='$manu' where `serial_num`='$sn'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo '<h3>Device has been modified</h3>';
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else {
	echo '<form method="post" action="">';
	echo '<h3>Choose a Modify Option</h3>';
	echo '</select>';
	echo '<button type="submit" name="Manufacture" value="Manufacture">Manufacture</button>';
	echo '<button type="submit" name="Type" value="Type">Type</button>';
	echo '<button type="submit" name="Device" value="Device">Device</button>';
	echo '</form>';
}
?>
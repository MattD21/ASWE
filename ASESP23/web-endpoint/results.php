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
if(isset($_POST["NewManu"])) 
{	
	echo '<input type="hidden" name="inactive_manu" value='.$_POST['inactive_manu'].'/>';
	$manu = $_POST["NewManu"];
	$status=$_POST["inactive_manu"];
	$time_start=microtime(true);
	$sql="Select exists(Select `manufacture` from `Manufacture` where `manufacture`='$manu')";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	if($result->fetch_array()[0]!=0) {
		echo "<h3>The Type $manu already exists in table</h3>";
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
		die();
	}
	$sql="Insert into `Manufacture` (`manufacture`) values ('$manu')";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
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
	echo '<p>'.$_POST["NewManu"].' has been inserted into Manufactre Table</p>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST["NewType"])) 
{	
	echo '<input type="hidden" name="inactive_manu" value='.$_POST['inactive_manu'].'/>';
	$type = $_POST["NewType"];
	$status=$_POST["inactive_type"];
	$time_start=microtime(true);
	$sql="Select exists(Select `type` from `Type` where `type`='$type')";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	if($result->fetch_array()[0]!=0) {
		echo "<h3>The Type $type already exists in table</h3>";
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
		die();
	}
	$sql="Insert into `Type` (`type`) values ('$type')";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
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

	echo '<p>'.$_POST["NewType"].' has been inserted into Type Table</p>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST["NewDevice"])) 
{	
	echo '<input type="hidden" name="inactive_manu" value='.$_POST['inactive_manu'].'/>';
	echo '<input type="hidden" name="NewDevice" value='.$_POST['NewDevice'].'/>';
	echo '<input type="hidden" name="manufacture" value='.$_POST['manufacture'].'/>';
	$sn = $_POST["NewDevice"];
	$manu = $_POST["manufacture"];
	$type = $_POST["type"];
	$status=$_POST["inactive_device"];
	$time_start=microtime(true);
	if(strlen($sn) != 32) {
		echo '<h3>The Serial Number length must be 32 characters to enter into the database. Try again.</h3>';
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
		die();
	}
	$sql="Select `id` from `Manufacture` where `manufacture`='$manu'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$manu=$result->fetch_array();
	$sql="Select `id` from `Type` where `type`='$type'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$type=$result->fetch_array();
	$sql="Select * from `equipment` where `serial_num`='$sn'";
	$result=$dblink->query($sql);
	$tmp=$result->fetch_array()[0];
	if($tmp!=null) {
		echo '<h3>This Serial Number already exists, make a unique one.</h3>';
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
		die();
	}
	$sql="Insert into `equipment` (`type_id`,`manufacture_id`,`serial_num`) values ('$type[0]','$manu[0]','$sn')";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	if($status == "true") {
		$sql="Select `auto_id` from `equipment` where `serial_num`='$sn'";
		$result=$dblink->query($sql);
		$id=$result->fetch_array()[0];
		$sql="Insert into `InactiveDevice` values ('$id')";
		$result=$dblink->query($sql);
	}
	else {
		$sql="Select `auto_id` from `equipment` where `serial_num`='$sn'";
		$result=$dblink->query($sql);
		$id=$result->fetch_array()[0];
		$sql="Delete from `InactiveDevice` where `id`='$id'";
		$result=$dblink->query($sql);
	}
	echo '<h3>Inserted device into end of Table<br>Manufactrer: '.$manu[0].'<br>Type: '.$type[0].'<br>SN: '.$sn.' into equipment</h3>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else 
{
	echo '<h3>No post data recieved</h3>';
}
?>
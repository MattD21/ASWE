<?php
$dblink=db_iconnect("test");
if(isset($_REQUEST["serial_num"])) 
{	
	$sn = $_REQUEST["serial_num"];
	$status = $_REQUEST["status"];
	$manu=str_replace("_"," ",$_REQUEST['manu']);
	$type=str_replace("_"," ",$_REQUEST['type']);
	$time_start=microtime(true);
	if(strlen($sn) != 32) {
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: ERROR';
		$output[]= "MSG: The Serial Number length must be 32 characters to enter into the database. Try again.";
		$output[]='Execution Time: '.$execution_time.' seconds';
		$responseData=json_encode($output);
		echo $responseData;
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
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: ERROR';
		$output[]= "MSG: This serial Number already exists in the Database, use a different serial number.";
		$output[]='Execution Time: '.$execution_time.' seconds';
		$responseData=json_encode($output);
		echo $responseData;
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
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	$output[]='Status: Success';
	$output[]= "MSG: Inserted New Device $type[0] $manu[0] $sn";
	$output[]='Execution Time: '.$execution_time.' seconds';
	$responseData=json_encode($output);
	echo $responseData;
}
?>
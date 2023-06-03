<?php
$dblink=db_iconnect("test");
if(isset($_REQUEST["type"])) 
{	
	$type = $_REQUEST["type"];
	$status=$_REQUEST["status"];
	$time_start=microtime(true);
	$sql="Select `type` from `Type` where `type`='$type'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$result->fetch_array();
	if($tmp[0]!=null) {
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: Error';
		$output[]="MSG: Type $type Already Exists in Table";
		$output[]='Execution Time: '.$execution_time.' seconds';
		$responseData=json_encode($output);
		echo $responseData;	
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
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	$output[]='Status: Success';
	$output[]="MSG: Inserted New Type $type";
	$output[]='Execution Time: '.$execution_time.' seconds';
	$responseData=json_encode($output);
	echo $responseData;
}
?>
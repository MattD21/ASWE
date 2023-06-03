<?php
$dblink=db_iconnect("test");
if(isset($_REQUEST["manufacturer"])) 
{	
	$manu = $_REQUEST["manufacturer"];
	$status=$_REQUEST["status"];
	$time_start=microtime(true);
	$sql="Select `manufacture` from `Manufacture` where `manufacture`='$manu'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$result->fetch_array();
	if($tmp[0]!=null) {
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: Error';
		$output[]="MSG: Manufacturer $manu Already Exists in Table";
		$output[]='Execution Time: '.$execution_time.' seconds';
		$responseData=json_encode($output);
		echo $responseData;	
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
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	$output[]='Status: Success';
	$output[]='MSG: Inserted New Manufacturer';
	$output[]='Execution Time: '.$execution_time.' seconds';
	$responseData=json_encode($output);
	echo $responseData;
	
}
?>
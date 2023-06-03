<?php
$dblink=db_iconnect("test");
if(!isset($_REQUEST['manufacturer'])) 
{
	$output[]='Status: ERROR';
	$output[]='MSG: Manufacturer data NULL';
	$output[]='Action: Resend Manufacturer data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if(!isset($_REQUEST['type'])) {
	$output[]='Status: ERROR';
	$output[]='MSG: Type data NULL';
	$output[]='Action: Resend Type data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
$time_start=microtime(true);
if($_REQUEST['serial_num'] != null){
	$sn=str_replace("_"," ",$_REQUEST['serial_num']);
	if(strlen($sn) != 32 && $sn != null) {
		$output[]='Status: ERROR';
		$output[]='MSG: Serial Number data != 32';
		$output[]='Action: Resend Serial Number data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	else {
			$sql="Select exists(Select * from `equipment` where `serial_num`='$sn')";
		if($dblink->query($sql) == 0) {
			$time_end=microtime(true);
			$seconds=$time_end-$time_start;
			$execution_time=($seconds)/60;
			$output[]='Status: ERROR';
			$output[]="MSG: Serial Number: $sn does not exist in DataBase";
			$output[]='Execution Time: '.$execution_time.' seconds';
			$output[]=$by_manu;
			$output[]=$by_type;
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		$sql="Select * from `equipment` where `serial_num`='$sn'";
	}
}
else {
	$manu=str_replace("_"," ",$_REQUEST['manufacturer']);
	$type=str_replace("_"," ",$_REQUEST['type']);
	$time_end=microtime(true);
	$by_manu=false;
	$by_type=false;
	if ($_REQUEST['manufacturer']=="all")
		$manu="`manufacture_id` like '%'";
	else {
		$sql="Select `id` from `Manufacture` where `manufacture`='$manu'";
		$result=$dblink->query($sql);
		$manu_id=$result->fetch_array()[0];
		$manu="`manufacture_id`='$manu_id'";
		$by_manu=true;
	}
	if ($_REQUEST['type']=="all")
		$type="`type_id` like '%'";
	else {
		$sql="Select `id` from `Type` where `type`='$type'";
		$result=$dblink->query($sql);
		$type_id=$result->fetch_array()[0];
		$type="`type_id`='$type_id'";
		$by_type=true;
	}
	$sql="Select * from `equipment` where $manu and $type limit 1000";
}
$info=array();
$result=$dblink->query($sql);
if($by_manu==true) {
	while($data=$result->fetch_array(MYSQLI_ASSOC)) {
		$sql="Select `manufacture` from `Manufacture` where `id`='$data[manufacture_id]'";
		$rst=$dblink->query($sql);
		$tmp=$rst->fetch_array();
		$manufacturer=$tmp[0];
		$sql="Select `type` from `Type` where `id`='$data[type_id]'";
		$rst=$dblink->query($sql);
		$tmp=$rst->fetch_array();
		$type=$tmp[0];
		$info[]=array($type,$manufacturer,$data['serial_num']);
	}
}
else if($by_type==true) {
	while($data=$result->fetch_array(MYSQLI_ASSOC)) {
		$sql="Select `type` from `Type` where `id`='$data[type_id]'";
		$rst=$dblink->query($sql);
		$tmp=$rst->fetch_array();
		$type=$tmp[0];
		$sql="Select `manufacture` from `Manufacture` where `id`='$data[manufacture_id]'";
		$rst=$dblink->query($sql);
		$tmp=$rst->fetch_array();
		$manufacturer=$tmp[0];
		$info[]=array($type,$manufacturer,$data['serial_num']);
	}
}
else {
	while($data=$result->fetch_array(MYSQLI_ASSOC)) {
		$sql="Select `type` from `Type` where `id`='$data[type_id]'";
		$rst=$dblink->query($sql);
		$tmp=$rst->fetch_array();
		$type=$tmp[0];
		$sql="Select `manufacture` from `Manufacture` where `id`='$data[manufacture_id]'";
		$rst=$dblink->query($sql);
		$tmp=$rst->fetch_array();
		$manufacturer=$tmp[0];
		$info[]=array($type,$manufacturer,$data['serial_num']);
	}
}
$infoJson=json_encode($info);
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
$output[]='Status: Success';
$output[]='MSG: '.$infoJson;
$output[]='Execution Time: '.$execution_time.' seconds';
$output[]=$by_manu;
$output[]=$by_type;
$responseData=json_encode($output);
echo $responseData;
?>
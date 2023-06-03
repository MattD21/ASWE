<?php
function db_iconnect($dbName)
{
	$un="root";
	$pw="Dylan1006";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
$dblink=db_iconnect("test");
echo "Hello from php process $argv[1] about to process file:$argv[2]\n";
$fp=fopen("/home/ubuntu/$argv[2]","r");
$count=0;
$time_start=microtime(true);
echo "PHP ID:$argv[1]-Start time is: $time_start\n";
while (($row=fgetcsv($fp)) !== FALSE)
{
	$row[2]=str_replace("SN-",'',$row[2]);
	$row[0]=preg_replace( '/[^a-z0-9 ]/i', '', $row[0]);
	$row[1]=preg_replace( '/[^a-z0-9 ]/i', '', $row[1]);
	$row[2]=preg_replace( '/[^a-z0-9 ]/i', '', $row[2]);
	try {
		$sql="Select `id` from `Manufacture` where `manufacture`='$row[1]'";
		$result=$dblink->query($sql);
		if($result != NULL)
			$manufacture_id = $result->fetch_array()[0];
		else 
			$manufacture_id = NULL;
	}
	catch(MySQLi_Sql_Exception $e) {
		$manufacture_id = NULL;
	}
	
	try{
		$sql="Select `id` from `Type` where `type`='$row[0]'";
		$result=$dblink->query($sql);
		if($result != NULL)
			$type_id = $result->fetch_array()[0];
		else 
			$type_id = NULL;

	}
	catch(MySQLi_Sql_Exception $e) {
		$type_id = NULL;
	}
	
	if($type_id == NULL or $manufacture_id == NULL or $row[2] == NULL)
		continue;
	$sql="Insert into `equipment` (`type_id`,`manufacture_id`,`serial_num`) values ($type_id,$manufacture_id,'$row[2]')";
	$dblink->query($sql);
	$count++;
}
$time_end=microtime(true);
echo "PHP ID:$argv[1]-End Time:$time_end\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "PHP ID:$argv[1]-Execution time: $execution_time minutes or $seconds seconds.\n";
$rowsPerSeonds=$count/$seconds;
echo "PHP ID:$argv[1]-Insert rate: $rowsPerSecond per second\n";
fclose($fp);
?>
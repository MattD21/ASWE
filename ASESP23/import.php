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
$fp=fopen("var/www/html/","r");
$count=0;
$time_start=microtime(true);
echo "<p>Start time is: $time_start</p>";
while (($row=fgetcsv($fp)) !== FALSE)
{
	$sql="Insert into `equipment2` (`type`, `manufacture`, `serial_num`) values ('$row[0]','$row[1]','$row[2]')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$count++;
}
$time_end=microtime(true);
echo "<p>End Time:$time_end</p>\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
$rowsPerSeonds=$count/$seconds;
echo "<p>Insert rate: $rowsPerSeonds per second</p>\n";
fclose($fp);
?>
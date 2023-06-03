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
if (isset($_POST['submit']) && ($_POST['submit'] =="submit"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['manufacture'];
	$name=$query;
	$sql="Select `id` from `Manufacture` where `manufacture`='$query'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$query=$result->fetch_array();
	$sql="Select `type_id`,`serial_num` from `equipment` where `manufacture_id`='$query[0]'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<h3>Search by Manufacturer: '.$name.'</h3>';
	echo '<table>';
	echo '<tr><td>Type</td><td>Serial Number</td></tr>';
	while ($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		/*
		$sql="Select `type` from `Type` where '$data[type_id]'=`id`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$type=$result->fetch_array()[0];
		$sql="Select `manufacture` from `Manufacture` where '$data[manufacture_id]'=`id`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$manufacture=$result->fetch_array()[0];
		*/
		echo '<tr>';
		echo "<td>$data[type_id]</td>";
		echo "<td>$data[serial_num]</td>";
		echo "</tr>";
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	$dblink=db_iconnect("test");
	$sql="Select distinct(`manufacture`) from `Manufacture`";
	$time_start=microtime(true);
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<select name="manufacture">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';

	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
?>
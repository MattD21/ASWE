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
if(isset($_POST['all']) && ($_POST['all'] == "all")) 
{
	echo '<input type="hidden" name="serial_num" value='.$_POST['serial_num'].'>';
	echo '<input type="hidden" name="type" value='.$_POST["type"].'/>';
	$time_start=microtime(true);
	$serialnum = $_POST['serial_num'];
	$by_manu=false;
	$by_type=false;
	$manu=$_POST['manufacturer'];
	$type=$_POST['type'];
	echo '<h3>Manufacturer: '.$_POST['manufacturer'].'<br>Type: '.$_POST['type'].'</h3>';
	if($manu != null) {
		$manu=str_replace("/",'',$_POST['manufacturer']);
		$manu=str_replace("/",'',$_POST['manufacturer']);
	}
	if($type != null) {
		$type=str_replace("/",'',$_POST['type']);
		$type=str_replace("/",'',$_POST['type']);
	}
	if ($_POST['manufacturer']=="all") {
		$manu="`manufacture_id` like '%'";
	}
	else {
		$sql="Select `id` from `Manufacture` where `manufacture`='$_POST[manufacturer]'";
		$result=$dblink->query($sql);
		$manu_id=$result->fetch_array()[0];
		$manu="`manufacture_id`='$manu_id'";
		$by_manu=true;
	}
	if ($_POST['type']=="all") {
		$type="`type_id` like '%'";
	}
	else {
		$sql="Select `id` from `Type` where `type`='$_POST[type]'";
		$result=$dblink->query($sql);
		$type_id=$result->fetch_array()[0];
		$type="`type_id`='$type_id'";
		$by_type=true;
	}
	$time_start=microtime(true);
	if ($serialnum != null) {
		$sql = "Select * from `equipment` where `serial_num`='$serialnum'";
		$result = $dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<h3>Search By Serial Number: '.$serialnum.'</h3>';
		echo '<table>';
		echo '<tr><td><b>Auto Id</td><td><b>Type</td><td><b>Manufacturer</td></tr>';
		while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
			$sql="Select `manufacture` from `Manufacture` where `id`='$data[manufacture_id]'";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			$manu=$result->fetch_array();
			$sql="Select `type` from `Type` where `id`='$data[type_id]'";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			$type=$result->fetch_array();
			echo '<tr>';
			echo "<td>$data[auto_id]</td>";
			echo "<td>$type[0]</td>";
			echo "<td>$manu[0]</td>";
			echo "</tr>";
		}
		echo "<p>Note if table is empty there are no results for Serial Number: $serialnum</p>";
	}
	else{
		$sql="Select * from `equipment` where $manu and $type limit 1000";
		$result=$dblink->query($sql);
		if($by_manu==true) {
			echo '<h3>Search by Manufacture:</h3>';
			echo '<table>';
			echo '<tr><td><b>Auto Id</td><td><b>Type</td><td><b>Serial Number</td></tr>';
			while($data=$result->fetch_array(MYSQLI_ASSOC)) {
				$sql="Select `type` from `Type` where `id`='$data[type_id]'";
				$rst=$dblink->query($sql);
				$tmp=$rst->fetch_array();
				$type=$tmp[0];
				echo '<tr>';
				echo "<td>$data[auto_id]</td>";
				echo "<td>$type</td>";
				echo "<td>$data[serial_num]</td>";
				echo "</tr>";	
			}
			echo "<p>Note that if the table is empty there are no results for query</p>";
		}
		else if($by_type==true) {
			echo '<h3>Search by Manufacture:</h3>';
			echo '<table>';
			echo '<tr><td><b>Auto Id</td><td><b>Manufacturer</td><td><b>Serial Number</td></tr>';
			while($data=$result->fetch_array(MYSQLI_ASSOC)) {
				$sql="Select `manufacture` from `Manufacture` where `id`='$data[manufacture_id]'";
				$rst=$dblink->query($sql);
				$tmp=$rst->fetch_array();
				$manufacturer=$tmp[0];
				echo '<tr>';
				echo "<td>$data[auto_id]</td>";
				echo "<td>$manufacturer</td>";
				echo "<td>$data[serial_num]</td>";
				echo "</tr>";	
			}
			echo "<p>Note that if the table is empty there are no results for query</p>";
		}
		else {
			echo '<h3>Search Results with a Manufacturer and Type</h3>';
			echo '<table>';
			echo '<tr><td><b>Auto Id</td><td><b>Type</td><td><b>Manufacturer</td><td><b>Serial Number</td></tr>';
			while($data=$result->fetch_array(MYSQLI_ASSOC)) {
				$sql="Select `type` from `Type` where `id`='$data[type_id]'";
				$rst=$dblink->query($sql);
				$tmp=$rst->fetch_array();
				$type=$tmp[0];
				$sql="Select `manufacture` from `Manufacture` where `id`='$data[manufacture_id]'";
				$rst=$dblink->query($sql);
				$tmp=$rst->fetch_array();
				$manufacturer=$tmp[0];
				echo '<tr>';
				echo "<td>$data[auto_id]</td>";
				echo "<td>$type</td>";
				echo "<td>$manufacturer</td>";
				echo "<td>$data[serial_num]</td>";
				echo "</tr>";	
			}
			echo "<p>Note that if the table is empty there are no results for query</p>";
		}
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	echo '<form method="post" action="">';
	echo '<h3>Search For Equipment</h3>';
	echo '</select>';
	echo '<form method="post" action="">';
	echo '<h3>Select a Manufacture:</h3>';
	$sql="Select distinct(`manufacture`) from `Manufacture`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<select name="manufacturer">';
	echo '<option value="all">All</option>';
		while($data=$result->fetch_array(MYSQLI_NUM))
		{
			echo '<option value="'.$data[0].'">'.$data[0].'</option>';
		}
	echo '</select>';
	echo '<h3>Select a Type:</h3>';
	$sql="Select distinct(`type`) from `Type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<select name="type">';
	echo '<option value="all">All</option>';
		while($data=$result->fetch_array(MYSQLI_NUM))
		{
			echo '<option value="'.$data[0].'">'.$data[0].'</option>';
		}
	echo '</select>';
	echo '<h3>Type in a Serial Number:</h3>';
	echo '<input type="text" size="50" maxlength="32" name="serial_num">';
	echo '<button type="submit" name="all" value="all">Submit</button>';
	echo '<p><b>Note: ';
	echo 'If you do not type in a serial number it will not effect the query,</p>';
	echo '<p>so if you do not have a specific serial number it is okay!</p>';
	echo '</form>';
}
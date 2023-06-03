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
if(isset($_REQUEST['all']))
{
	$manu=$_POST['manufacturer'];
	$type=$_POST['type'];
	$sn=$_POST['serial_num'];
	$manu = preg_replace('/\s+/', '_', $manu);
	$type = preg_replace('/\s+/', '_', $type);
	$sn = preg_replace('/\s+/', '_', $sn);
	$curl=curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-34-207-57-125.compute-1.amazonaws.com/api/search?manufacturer=$manu&type=$type&serial_num=$sn",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER=> false
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);

	if($err) {
		echo"<h3>cURL Error Search API #: $err</h3>";
		die();
	}
	else {
		$results=json_decode($response, true);
	}
	$tmp=explode(":",$results[0]);
	$status=trim($tmp[1]);
	if($status=="Success") {
		if($results[1] == "MSG: []") {
			echo '<p>No results for this query, please try again</p>';
			echo "<p>$results[2]";
			die();
		}
		else {
			if($results[3]=='by_manu') {
				$tmp=explode(":",$results[1]);
				$data=json_decode($tmp[1],true);
				echo '<h3>Search Results By Manufacturer: '.$data[0][1].'</h3>';
				echo "<p>$results[2]</p>";
				echo '<table>';
				echo '<tr><td><b>Type</td><td><b>Serial Number</td></tr>';
				foreach($data as $key=>$value) {
					echo '<tr>';
					echo "<td>$value[0]</td>";
					echo "<td>$value[2]</td>";
					echo "</tr>";	
				}
			}
			else if($results[4]=='by_type') {
				$tmp=explode(":",$results[1]);
				$data=json_decode($tmp[1],true);
				echo '<h3>Search Results By Type: '.$data[0][0].'</h3>';
				echo "<p>$results[2]</p>";
				echo '<table>';
				echo '<tr><td><b>Manufacturer</td><td><b>Serial Number</td></tr>';
				foreach($data as $key=>$value) {
					echo '<tr>';
					echo "<td>$value[1]</td>";
					echo "<td>$value[2]</td>";
					echo "</tr>";	
				}
			}
			else {
				$tmp=explode(":",$results[1]);
				$data=json_decode($tmp[1],true);
				echo '<h3>Search Results</h3>';
				echo "<p>$results[2]</p>";
				echo '<table>';
				echo '<tr><td><b>Type</td><td><b>Manufacturer</td><td><b>Serial Number</td></tr>';
				foreach($data as $key=>$value) {
					echo '<tr>';
					echo "<td>$value[0]</td>";
					echo "<td>$value[1]</td>";
					echo "<td>$value[2]</td>";
					echo "</tr>";	
				}
			}
			
		}
	}
	else {
		echo "<h3>$results[0]<br>$results[1]<br>$results[2]</h3>";
	}
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
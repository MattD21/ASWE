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
if(isset($_REQUEST['Manufacture'])) 
{
	echo '<form method="post" action="">';
	echo '<h3>New Manufacture</h3>';
	echo '<p>Type in a new Manufacture</p>';
	echo '<input type="text" size="50" maxlength="45" name="NewManu">';
	echo '<p>Inactive: ';
	echo '<input type="radio" name="inactive_manu" value="true"';
	echo '</p>';
	echo '<br><br>';
	echo '<button type="submit">Submit</button>';
	echo '</form>';
}
else if(isset($_REQUEST['Type'])) 
{
	echo '<form method="post" action="">';
	echo '<h3>New Type</h3>';
	echo '<p>Type in a new Type</p>';
	echo '<input type="text" size="50" maxlength="45" name="NewType">';
	echo '<p>Inactive: ';
	echo '<input type="radio" name="inactive_type" value="true"';
	echo '</p>';
	echo '<br><br>';
	echo '<button type="submit">Submit</button>';
	echo '</form>';
}
else if(isset($_REQUEST['Device'])) 
{
	echo '<form method="post" action="">';
	echo '<h3>New Device</h3>';
	echo '<p>Select a Manufacter and Type from drop down, type in SN and press enter to go to next page</p>';
	echo '<p>Type in a new SN</p>';
	echo '<input type="text" size="50" maxlength="32" name="NewDevice">';
	$sql="Select distinct(`manufacture`) from `Manufacture`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<select name="manufacture">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	$sql="Select distinct(`type`) from `Type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<select name="type">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<p>Inactive: ';
	echo '<input type="radio" name="inactive_device" value="true"';
	echo '</p>';
	echo '<br><br>';
	echo '<button type="submit">Submit</button>';
	echo '</form>';
}
else if(isset($_REQUEST['NewManu'])) {
	$manu=trim($_POST['NewManu']);
	if(isset($_POST['inactive_manu']))
		$status=$_POST["inactive_manu"];
	else 
		$status=false;
	$curl=curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-34-207-57-125.compute-1.amazonaws.com/api/insert_manu?manufacturer=$manu&status=$status",
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
	echo "<p><b>$results[0]<br>$results[1]<br>$results[2]</p>";
	
}
else if(isset($_REQUEST['NewType'])) {
	$type=trim($_POST['NewType']);
	if(isset($_POST['inactive_type']))
		$status=$_POST["inactive_type"];
	else 
		$status=false;
	$curl=curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-34-207-57-125.compute-1.amazonaws.com/api/insert_type?type=$type&status=$status",
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
	echo "<p><b>$results[0]<br>$results[1]<br>$results[2]</p>";
}
else if(isset($_REQUEST['NewDevice'])) {
	$sn = trim($_POST["NewDevice"]);
	$manu = $_POST["manufacture"];
	$type = $_POST["type"];
	if(isset($_POST['inactive_device']))
		$status=$_POST["inactive_device"];
	else 
		$status=false;
	$manu = preg_replace('/\s+/', '_', $manu);
	$type = preg_replace('/\s+/', '_', $type);
	$curl=curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-34-207-57-125.compute-1.amazonaws.com/api/insert_device?type=$type&manu=$manu&serial_num=$sn&status=$status",
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
	echo "<h3>$results[0]<br>$results[1]<br>$results[2]</h3>";
}


else
{
	echo '<form method="post" action="">';
	echo '<h3>Choose a Insert Option</h3>';
	echo '</select>';
	echo '<button type="submit" name="Manufacture" value="Manufacture">Manufacture</button>';
	echo '<button type="submit" name="Type" value="Type">Type</button>';
	echo '<button type="submit" name="Device" value="Device">Device</button>';
	echo '</form>';
}
?>
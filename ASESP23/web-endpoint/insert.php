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
if(isset($_POST['Manufacture']) && $_POST["Manufacture"] == "Manufacture") 
{
	echo '<form method="post" action="results.php">';
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
else if(isset($_POST['Type']) && $_POST["Type"] == "Type") 
{
	echo '<form method="post" action="results.php">';
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
else if(isset($_POST['Device']) && $_POST["Device"] == "Device") 
{
	echo '<form method="post" action="results.php">';
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
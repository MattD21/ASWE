<?php
header('Content-Type: application/json');
header('HTTP/1.1 200 OK');
$url=$_SERVER['REQUEST_URI']; //request URI component of URL
$path=parse_url($url,PHP_URL_PATH);
$pathComponents=explode("/",trim($path,"/"));
$endPoint=$pathComponents[1];//take the value at index 1 in the array and assign to endPoint var
function db_iconnect($dbName)
{
	$un="grader";
	$pw="jUmqCtPbRlLi.2BX";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
switch($endPoint)
{
	case "search":
		include("search.php");
		break;
	case "insert_manu":
		include("insert_manu.php");
		break;
	case "insert_type":
		include("insert_type.php");
		break;
	case "insert_device":
		include("insert_device.php");
		break;
	default:
		$output[]='Status: Error';
		$output[]='MSG: '.$endPoint.' Endpoint Not Found';
		$output[]='Action: None';
		$responseData=json_encode($output);
		echo $responseData;
}
?>
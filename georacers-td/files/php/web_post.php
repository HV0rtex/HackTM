<?php

$json = file_get_contents('php://input');

$con = mysqli_connect('192.168.0.100', 'jvpro_itc', 'itc_9610', 'jvpro_itc');
if(! $con ) {
	die('Could not connect: ' . mysqli_error($con));
}

//$myObj->CLOSEST_DEATH = "0.25";
//$myObj->OBJECTS[0]->TYPE = "1";
//$myObj->OBJECTS[0]->LAT = "0.15";
//$myObj->OBJECTS[0]->LNG = "0.35";
//$myObj->OBJECTS[1]->TYPE = "2";
//$myObj->OBJECTS[1]->LAT = "0.5";
//$myObj->OBJECTS[1]->LNG = "0.6";
//
//$myJSON = json_encode($myObj);

//read
$sql3 = "SELECT `JSON-ai-web` FROM `GeoRacers-TD` WHERE `JSON-ai-web`!=''";
$retval = mysqli_query( $con, $sql3 );

if(! $retval ) {
	die('Could not get data: ' . mysqli_error($retval));
}

$row = mysqli_fetch_assoc($retval);
$data_send = $row['JSON-ai-web'];
$sql4 = "DELETE FROM `GeoRacers-TD` WHERE `JSON-ai-web`!=''";
if ($con->query($sql4) === TRUE) {}

//write
$sql1 = "DELETE FROM `GeoRacers-TD` WHERE `JSON-web-ai`!=''";
$sql2 = "INSERT INTO `GeoRacers-TD` (`JSON-web-ai`) VALUES ('".$json."')";

if ($con->query($sql1) === TRUE) {}
//	echo "saved";
//} else echo "error_system";

if ($con->query($sql2) === TRUE) {}
//	echo "saved";
//} else echo "error_system";

//send
echo $data_send;

?>
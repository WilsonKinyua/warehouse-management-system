<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
//include( "initialize.php");
include( "DBConn.php");

 $data= json_decode($_POST['json'], true);
  
	
	$vc = new DBConn();
	$result = $vc->syncSales($data);
	$retValue["success"] = $result;	 
	echo json_encode($retValue);
?>

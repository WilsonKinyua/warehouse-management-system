<?php
error_reporting(E_ALL);
include( "DBConn.php");

	 $data= json_decode($_POST['json'], true);
	 
	$vc = new DBConn();
	$result = $vc->FlagAddition($data);
	
	$retValue["success"] = $result;
	 
	echo json_encode($retValue);
?>
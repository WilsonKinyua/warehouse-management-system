<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
// include( "initialize.php");


include( "DBConn.php");

	$name = $_POST['username'];
	$pw = $_POST['password'];
	
	$vc = new DBConn();
	$result = $vc->login($name, $pw);

	$retValue["success"] = is_array($result);
	if (is_array($result)) {
		$retValue["info"] = $result;
	} else {
		$retValue["info"] = $result;
	}	
	 
	$retValue["shop_details"]= $vc->getShops();
    
    
	echo json_encode($retValue);
?>

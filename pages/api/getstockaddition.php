<?php
// include( "initialize.php");
include( "DBConn.php");

$vc = new DBConn();


$id = $_GET['salesmanid'];
$result = $vc->getStockAddition($id );


 
echo json_encode($result);
 
?>

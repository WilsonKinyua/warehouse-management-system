<?php

include( "DBConn.php");

$vc = new DBConn();


$id = $_GET['salesmanid'];
$result = $vc->getStock($id );


 
echo json_encode($result);
 
?>

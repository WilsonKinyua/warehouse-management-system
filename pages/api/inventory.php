<?php
include '../utils/conn.php';
$customers = $conn->query("SELECT * FROM stock_mobile") or die($Conn->error);
$json = array();
while ($row = $customers->fetch_assoc()) {
  $json[] = $row;

}
header('Content-Type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);
 ?>

<?php
include '../utils/conn.php';
$customers = $conn->query("SELECT customers.id,customers.cust_name, customers.cust_email, customers.cust_contact,customers.code, pricelist.name as pricelist FROM `customers` LEFT JOIN cust_groups ON customers.cust_group=cust_groups.id LEFT JOIN pricelist ON cust_groups.pricelist=pricelist.id") or die($Conn->error);
$json = array();
while ($row = $customers->fetch_assoc()) {
  $json[] = $row;

}
header('Content-Type: application/json');
echo json_encode($json, JSON_PRETTY_PRINT);
 ?>

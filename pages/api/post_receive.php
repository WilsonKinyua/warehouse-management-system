<?php
include "../../conn.php";
$json = file_get_contents('php://input');
// Converts it into a PHP object
$data = json_decode($json);



$storeowner = $data["salesman"];
postSales($storeowner);
public function postSales($value)
{
  for ($i=0; $i <count($data['products']) ; $i++) {
    $id = $data['product_id'];
    $quantity_sold = $data['quantity'];
    $conn->query("UPDATE new_sales SET quantity_sold='$quantity_sold' WHERE product='$id' AND store_owner='$storeowner'") or die($conn->error);

  }

  echo "Sales successfully Posted";
}
 ?>

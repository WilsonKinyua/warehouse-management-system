<?php
include '../utils/conn.php';
$output = '<thead>
<tr>
  <th>Product Name</th>
  <th>Quantity<small>(In Stock)</small></th>
  <th>Unit Cost</th>
  <th>Total <small>(Stock Value)</small> </th>
</tr>
</thead><tbody>';
if (isset($_POST['salesperson']) and isset($_POST['store_code'])) {
  $sql = "";
  if ($_POST['salesperson'] !== "all" and $_POST['store_code'] !== "all") {

    $salesperson = $_POST['salesperson'];
    $store_code = $_POST['store_code'];
    $clear_status = 0;

    $sql = "SELECT *,products.name as pname, products.price as unit_cost, (stock_mobile.quantity_available * products.price) total_cost  FROM stock_mobile INNER JOIN products ON stock_mobile.product=products.id  WHERE store_owner='$salesperson' AND products.vendor='$store_code' AND clear_status='$clear_status' ORDER BY products.name";
    
  } else if ($_POST['salesperson'] !== "all" and $_POST['store_code'] == "all") {

    $salesperson = $_POST['salesperson'];
    $store_code = $_POST['store_code'];
    $clear_status = 0;

    $sql = "SELECT *,products.name as pname, products.price as unit_cost, (stock_mobile.quantity_available * products.price) total_cost   FROM stock_mobile INNER JOIN products ON stock_mobile.product=products.id  WHERE store_owner='$salesperson' AND clear_status='$clear_status' ORDER BY products.name ";
  } else if ($_POST['salesperson'] == "all" and $_POST['store_code'] !== "all") {
    $store_code = $_POST['store_code'];

    $sql = "SELECT *, products.name as pname, products.price as unit_cost, (stock.quantity_available * products.price) total_cost   FROM stock INNER JOIN products ON stock.product=products.id WHERE products.vendor='$store_code' ORDER BY products.name";
  } else {
    $sql = "SELECT *, products.name as pname, products.price as unit_cost, (stock.quantity_available * products.price) total_cost   FROM stock INNER JOIN products ON stock.product=products.id ORDER BY products.name";
  }
  $result = $conn->query($sql) or die($conn->error);
  if (mysqli_num_rows($result)<1) {

    $output .= "<tr><td colspan='2'>No data available =====</td></tr>";

  }else {
    while ($row = $result->fetch_assoc()) {
      $output .= "<tr>";
      $output .= "<td>".$row['pname']."</td>";
      $output .= "<td>".$row['quantity_available']."</td>";
      $output .= "<td>".$row['unit_cost']."</td>";
      $output .= "<td>".$row['total_cost']."</td>";
      $output .= "</tr>";
    }
  }
  $output .= '</tbody>
  <tfoot>
  <tr>
    <th>Product Name</th>
    <th>Quantity<small>(In Stock)</small></th>
    <th>Unit Cost</th>
  <th>Total <small>(Stock Value)</small> </th>
  </tr>
  </tfoot>';
  echo $output;
}else{
  $sql = "SELECT *,products.name as pname FROM stock INNER JOIN products ON stock.product=products.id";
  $result = $conn->query($sql) or die($conn->error);
  if (mysqli_num_rows($result)<1) {
    $output .= "<tr><td colspan='2'>No data available</td></tr>";
  }else {
    while ($row = $result->fetch_assoc()) {
      $output .= "<tr>";
      $output .= "<td>".$row['pname']."</td>";
      $output .= "<td>".$row['quantity_available']."</td>";
      $output .= "</tr>";
    }
  }
  $output .= '</tbody>
  <tfoot>
  <tr>
    <th>Product Name</th>
    <th>Quantity<small>(In Stock)</small></th>
    <th>Unit Cost</th>
  <th>Total <small>(Stock Value)</small> </th>
  </tr>
  </tfoot>';
  echo $output;
}
 ?>

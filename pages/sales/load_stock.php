<?php
include '../utils/conn.php';
$output = '<thead>
<tr>
  <th>Product Name</th>
  <th>Quantity<small>(In Stock)</small></th>
</tr>
</thead><tbody>';
if (isset($_POST['salesperson'])) {
  $sql = "";
  if ($_POST['salesperson'] != 0) {
    $clear_status = 0;
    $sql = "SELECT *,products.name as pname FROM stock_mobile INNER JOIN products ON stock_mobile.product=products.id  WHERE store_owner=".$_POST['salesperson']." AND clear_status='$clear_status'";
  }else{
    $sql = "SELECT *,products.name as pname FROM stock INNER JOIN products ON stock.product=products.id";
  }
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
  </tr>
  </tfoot>';
  echo $output;
}
 ?>

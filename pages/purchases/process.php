<?php
include '../utils/conn.php';
if(isset($_POST['update_cart'])){
foreach($_POST['quantity'] as $key => $val) {
    if($val==0) {
        unset($_SESSION['purchases_cart'][$key]);
    }else{
        $_SESSION['purchases_cart'][$key]['quantity']=$val;
    }
}
header('location: addsales.php');
}

if(isset($_POST['clear_cart'])){
  unset($_SESSION['purchases_cart']);
  header('location: addsales.php');
}

if(isset($_POST['save_sale'])){
  $doc_number = $_POST['doc_number'];
  $customer = $_POST['customer_name'];
  $s = $_POST['salesman'];
  $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
  $user_date = $user->fetch_array();
  $user = $user_date['id'];

  $sql="SELECT * FROM products WHERE id IN (";

          foreach($_SESSION['purchases_cart'] as $id => $value) {
              $sql.=$id.",";
          }

          $sql=substr($sql, 0, -1).") ORDER BY name ASC";
          $query=$conn->query($sql);
          $totalprice = 0;
          while($row=$query->fetch_assoc()){
              $subtotal = $_SESSION['purchases_cart'][$row['id']]['quantity']*$row['price'];
              $totalprice += $subtotal;
              $id= $row['id'];
              $quantity = $_SESSION['purchases_cart'][$row['id']]['quantity'];
              $conn->query("INSERT INTO `new_sale`(`product`, `quantity`,`doc_number`, `salesman`) VALUES ('$id','$quantity','$doc_number','$user')") or die($conn->error);
            }
  unset($_SESSION['purchases_cart']);
  header('location: addsales.php');

  }

if (isset($_POST['save_purchase_print'])) {
  $doc_number = $_POST['doc_number'];
  $supplier = $_POST['supplier'];
  if (empty($supplier)) {
    $supplier_err = "Supplier must be selected";
    $valid = false;
  }
  // $s = $_POST['salesman'];
  // $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
  // $user_date = $user->fetch_array();
  // $user = $user_date['id'];
// var_dump($_POST)'';
$date = date('Y-m-d');
if ($valid) {

$sql="SELECT * FROM products WHERE id IN (";

        foreach($_SESSION['purchases_cart'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY name ASC";
        $query=$conn->query($sql);
          $totalprice = 0;
          while($row=$query->fetch_assoc()){
              $subtotal = $_SESSION['purchases_cart'][$row['id']]['quantity']*$row['price'];
              $totalprice += $subtotal;
              $id= $row['id'];
              $quantity = $_SESSION['purchases_cart'][$row['id']]['quantity'];
              $conn->query("INSERT INTO `stock_receive`(`supplier`, `doc_number`, `product`, `number_of_products`, `total_cost`, `date`) VALUES ('$supplier','$doc_number','$id','$quantity','$subtotal','$date')") or die($conn->error);
              //Check if the product exists
              $res = $conn->query("SELECT quantity_available FROM stock WHERE product='$id'") or die($conn->error);
              if (mysqli_num_rows($res) > 0) {
                // Update Stock
                $row = $res->fetch_array();
                $quantity += $row['quantity_available'];
                $conn->query("UPDATE `stock` SET `quantity_available`='$quantity' WHERE product='$id'") or die($conn->error);
              }else{
                $conn->query("INSERT INTO `stock`(`product`, `quantity_available`,`date`) VALUES ('$id','$quantity','$date')") or die($conn->error);
              }
            }
        header('location: print.php');
      }

  }


 ?>

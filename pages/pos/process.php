<?php
include '../utils/conn.php';

if(isset($_POST['update_cart'])){
foreach($_POST['quantity'] as $key => $val) {
    if($val==0) {
        unset($_SESSION['cart'][$key]);
    }else{
        $_SESSION['cart'][$key]['quantity']=$val;
    }
}
header('location: index.php');
}

if(isset($_POST['clear_cart'])){
  unset($_SESSION['cart']);
  header('location: index.php');
}

if(isset($_POST['save_sale'])){
  $doc_number = $_POST['doc_number'];
  $customer = $_POST['customer_name'];
  $s = $_POST['salesman'];
  $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
  $user_date = $user->fetch_array();
  $user = $user_date['id'];

  $sql="SELECT * FROM products WHERE id IN (";

          foreach($_SESSION['cart'] as $id => $value) {
              $sql.=$id.",";
          }

          $sql=substr($sql, 0, -1).") ORDER BY name ASC";
          $query=$conn->query($sql);
          $totalprice = 0;
          while($row=$query->fetch_assoc()){
              $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['price'];
              $totalprice += $subtotal;
              $id= $row['id'];
              $quantity = $_SESSION['cart'][$row['id']]['quantity'];
              $conn->query("INSERT INTO `new_sale`(`product`, `quantity`,`doc_number`, `salesman`) VALUES ('$id','$quantity','$doc_number','$user')") or die($conn->error);
            }
  unset($_SESSION['cart']);
  header('location: index.php');

  }
 ?>

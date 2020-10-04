<?php
require '../utils/conn.php';

$name = $contact = $refno =$email = $opening_balance = $payterm = "";
$update = false;
if (isset($_POST['supplier_submit'])) {
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $refno = $_POST['refno'];
  $opening_balance = $_POST['opening_balance'];
  $payterm = $_POST['payterm'];
  $email = $_POST['email'];

  if($conn->query("INSERT INTO `supplier`(`name`, `kra_pin`, `contact`, `email`, `balance`, `pay_term`) VALUES ('$name','$refno','$contact','$email','$opening_balance','$payterm')") or die($conn->error)){
    header('location: suppliers.php');
  }
}

//customers
$name = $contact = $refno = $code =$opening_balance =$payterm = "";
$group = $credit = $address = "";

if (isset($_POST['customer_submit'])) {
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $refno = $_POST['refno'];
  $code = $_POST['code'];
  $opening_balance = $_POST['opening_balance'];
  $payterm = intval($_POST['payterm']);
  $group = $_POST['group'];
  $credit = intval($_POST['credit']);
  $address = $_POST['address'];

  $sql = $conn->query("INSERT INTO `customers`(`cust_name`, `cust_contact`, `kra_pin`, `code`, `pay_term`, `credit_limit`, `cust_group`, `total_owed`, `total_paid`, `shipping_address`) VALUES ('$name','$contact','$refno','$code','$payterm','$credit','$group','$opening_balance','$opening_balance','$address')") or die($conn->error);
  if ($sql) {
    header('location: customers.php');
  }
}

//Groups
$name = $percentage = $price_list ="";
$name_err = $percentage_err = $price_list_err = "";
$valid = true;
if (isset($_POST['groups_submit'])) {
  $name = $_POST['name'];
  $percentage = $_POST['percentage'];
  $price_list = $_POST['price_list'];

  //Validation
  if (empty($name)) {
    $name_err = "Name must be entered";
    $valid = false;
  }if (!is_numeric($percentage)) {
    $percentage_err = "Percentage invalid";
    $valid = false;
  }if (empty($price_list)) {
    $price_list_err = "Pricelist must be selected";
    $valid = false;
  }

  if ($valid) {
    $sql = $conn->query("INSERT INTO `cust_groups`(`name`,`percentage`,`pricelist`) VALUES ('$name','$percentage','$price_list')")or die($conn->error);
    if ($sql) {
      // var_dump($_POST);
      header('location: groups.php');
    }
  }

}
 ?>

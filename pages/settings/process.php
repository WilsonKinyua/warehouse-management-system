<?php
// $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
include '../utils/conn.php';
$update = false;

$name = "";
$rate = "";
$option = "";

$rate_err = $tax_name_err = "";
$valid = true;

if (isset($_POST['taxes_submit'])) {
	$name = $_POST['name'];
  $rate = $_POST['rate'];
	if (empty($name)) {
		$tax_name_err = "Name is required";
		$valid = false;
	}
  if (empty($rate)) {
		$rate_err = "Rate is required";
		$valid = false;
	}
	if ($valid) {
		$conn->query("INSERT INTO `tax_rates`(`name`, `tax_rate`) VALUES ('$name','$rate')") or die($conn->error);

		header('location: tax_rates.php');
	}


}
if (isset($_GET['taxes_edit'])) {
	$id = $_GET['taxes_edit'];
	$res = $conn->query("SELECT * FROM tax_rates WHERE id='$id'") or die($conn->error);
	if (mysqli_num_rows($res) == 1){
        $row = $res->fetch_array();
        $name = $row['name'];
        $update=true;
        $rate = $row['tax_rate'];
    }
}
if (isset($_GET['taxes_delete'])) {
	$id = $_GET['taxes_delete'];
	$conn->query("DELETE FROM tax_rates WHERE id='$id'") or die($conn->error);

	header('location: tax_rates.php');
}
if (isset($_POST['taxes_update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
  $rate = $_POST['rate'];
	$conn->query("UPDATE `tax_rates` SET `name`='$name',`tax_rate`='$rate' WHERE id='$id'") or die($conn->error);

	header('location: tax_rates.php');
}
// End of Taxes processes

$discount_name_err = $discount_rate_err = "";
$valid = true;

if (isset($_POST['discount_submit'])) {
	$name = $_POST['name'];
  $rate = $_POST['rate'];
	if (empty($name)) {
		$tax_name_err = "Name is required";
		$valid = false;
	}
  if (empty($rate)) {
		$rate_err = "Rate is required";
		$valid = false;
	}
	if ($valid) {
		$conn->query("INSERT INTO `discounts`(`name`, `discount_rate`) VALUES ('$name','$rate')") or die($conn->error);

		header('location: discounts.php');
	}


}
if (isset($_GET['discounts_edit'])) {
	$id = $_GET['discounts_edit'];
	$res = $conn->query("SELECT * FROM discounts WHERE id='$id'") or die($conn->error);
	if (mysqli_num_rows($res) == 1){
        $row = $res->fetch_array();
        $name = $row['name'];
        $update=true;
        $rate = $row['discount_rate'];
    }
}
if (isset($_GET['discounts_delete'])) {
	$id = $_GET['discounts_delete'];
	$conn->query("DELETE FROM discounts WHERE id='$id'") or die($conn->error);

	header('location: discounts.php');
}
if (isset($_POST['discount_update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
  $rate = $_POST['rate'];
	$conn->query("UPDATE `discounts` SET `name`='$name',`discount_rate`='$rate' WHERE id='$id'") or die($conn->error);

	header('location: discounts.php');
}

// Pay Terms
$term_name = $pay_terms = "";
$term_name_err = $term_err = "";
$valid = true;
if (isset($_POST['terms_submit'])) {
	$term_name = trim($_POST['term_name']);
	$pay_terms = trim($_POST['pay_terms']);

	if (empty($term_name)) {
		$term_name_err = "Name is required";
		$valid = false;
	}
	if(!is_numeric($pay_terms)){
		$term_err = "Invalid input. Number is required";
		$valid = false;
	}
	if ($valid) {
		$sql = $conn->query("INSERT INTO pay_terms (`name`,`pay_terms`) VALUES ('$term_name','$pay_terms')") or die($conn->error);
		if ($sql) {
			header('location: pay_terms.php');
		}
	}
}

// stores
$store_name = $store_name_err = "";
$is_mobile = 0;
$valid = true;
if (isset($_POST['stores_submit'])) {
	$store_name = $_POST['store_name'];
	if (empty($store_name)) {
		$store_name_err = "Name is required";
		$valid = false;
	}
	if(!empty($_POST['is_mobile'])){ $is_mobile =1; }
	if ($valid) {
		$conn->query("INSERT INTO `stores`(`name`,`is_mobile`) VALUES ('$store_name','$is_mobile')") or die($conn->error);
		header('location: stores.php');
	}

}
?>

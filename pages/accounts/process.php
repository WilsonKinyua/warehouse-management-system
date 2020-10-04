<?php
// $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
include '../utils/conn.php';

$category = $name = "";
$amount = 0;
$cat_err  = $amount_err = $name_err = $id_err = "";
$valid = true;

if (isset($_POST['account_create'])) {
	$category = $_POST['category'];
	$name = $_POST['name'];
	$id = $_POST['account_id'];

	if (empty($category)) {
		$cat_err = "Category is Required";
		$valid = false;
	}
	if (empty($name)) {
		$name_err = "Account name is Required";
		$valid = false;
	}
	if (empty($id)) {
		$id_err = "Account id is required";
		$valid = false;
	}

	if (mysqli_num_rows($conn->query("SELECT id FROM coa where id='$id'")) > 0){
		$id_err = "Account id is already taken";
		$valid = false;
	}

	if ($valid) {
		echo "Is valid";
		$sql = "INSERT INTO `coa`(`id`,`catId`, `name`) VALUES ('$id','$category','$name')";
		$_SESSION['success'] = 'The account was created successfully';
		$conn->query($sql)or die($conn->error);
	}
}


// Categories

$name = $code = "";
$group_name_err =$group_type_err = $code_err = "";
$valid = true;
if (isset($_POST['cat_submit'])) {
	$name = $_POST['name'];
	$type = $_POST['type'];

	if (empty($name)) {
		$group_name_err = "Name is required";
		$valid = false;
	}
	if (empty($type)) {
		$group_type_err = "Type is required";
		$valid = false;
	}
	if ($valid) {
		$conn->query("INSERT INTO `coa_group` (`name`, `type`) VALUES ('$name', '$type')") or die($conn->error);
		$_SESSION['success'] = 'The account group was created';
		header('location: account_groups.php');
		exit();
	}
}

if (isset($_GET['cat_delete'])){
	$catId = $_GET['cat_delete'];
	if (empty($catId)){
		$_SESSION['error'] = 'Please provide a group to delete';
	}
	$res = $conn->query("SELECT * FROM coa_group WHERE cat_id='$catId'");
	if (mysqli_num_rows($res) === 0){
		$_SESSION['error'] = 'There is no such account group';
	}else{
		$conn->query("DELETE FROM coa_group WHERE cat_id='$catId'");
		$_SESSION['success'] = 'The account group was deleted';
	}

	header('location: account_groups.php');
	exit();
}

if (isset($_GET['acc_delete'])){
	$accId = $_GET['acc_delete'];
	if (empty($accId)){
		$_SESSION['error'] = 'Please provide an account to delete';
	}
	$res = $conn->query("SELECT * FROM coa WHERE id='$accId'");
	if (mysqli_num_rows($res) === 0){
		$_SESSION['error'] = 'There is no such account';
	}else{
		$_SESSION['success'] = 'The account was deleted';
		$conn->query("DELETE FROM coa WHERE id='$accId'");
	}

	header('location: account_create.php');
	exit();
}

?>

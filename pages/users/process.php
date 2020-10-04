<?php
include '../utils/conn.php';
session_start();
$group_name_err = "";
$valid= true;
if (isset($_POST['user_groups_submit'])) {
	$name = trim($_POST['name']);
	if (empty($name)) {
		$group_name_err = "Group name must be entered";
		$valid = false;
	}
	$group_nm = $conn->query("SELECT id FROM security_groups WHERE group_name='$name' ") or die($conn->error);
	if (mysqli_num_rows($group_nm)>= 1) {
		$group_name_err = "Group name already taken";
		$valid = false;
	}
	$manage_categories = 0;
	$manage_products = 0;
	$manage_suppliers = 0;
	$receive_stock = 0;
	$transfer_stock = 0;

	$issue_stock = 0;
	$request_stock = 0;
	$make_sales = 0;


	$clear_sales = 0;
	$credit_sales = 0;
	$manage_accounts = 0;
	$manage_account_categories = 0;
	$manage_expenses = 0;

	$reports = 0;
	$sales_reports = 0;


	$transfer_reports = 0;
	$issue_reports = 0;
	$recieve_reports = 0;
	$manage_users = 0;
	$manage_user_groups = 0;
	$manage_user_rights = 0;
	$manage_customer_groups = 0;
	$manage_customers = 0;
	$manage_price_rules = 0;
	$all = 0;
	$nanyuki = 0;
	$isiolo = 0;
	$timau = 0;

	if(!empty($_POST['manage_categories'])){ $manage_categories =1; }
	if(!empty($_POST['manage_products'])){ $manage_products =1; }
	if(!empty($_POST['manage_suppliers'])){ $manage_suppliers =1; }
	if(!empty($_POST['receive_stock'])){ $receive_stock =1; }
	if(!empty($_POST['transfer_stock'])){ $transfer_stock =1; }

	if(!empty($_POST['issue_stock'])){ $issue_stock =1; }
	if(!empty($_POST['request_stock'])){ $request_stock =1; }
	if(!empty($_POST['make_sales'])){ $make_sales =1; }

	if(!empty($_POST['clear_sales'])){ $clear_sales =1; }
	if(!empty($_POST['credit_sales'])){ $credit_sales =1; }
	if(!empty($_POST['manage_accounts'])){ $manage_accounts =1; }
	if(!empty($_POST['manage_expenses'])){ $manage_expenses =1; }
	if(!empty($_POST['manage_account_categories'])){ $manage_account_categories =1; }
	if(!empty($_POST['sales_reports'])){ $sales_reports =1; }
	if(!empty($_POST['reports'])){ $reports =1; }
	if(!empty($_POST['transfer_reports'])){ $transfer_reports =1; }
	if(!empty($_POST['issue_reports'])){ $issue_reports =1; }
	if(!empty($_POST['recieve_reports'])){ $recieve_reports =1; }
	if(!empty($_POST['manage_users'])){ $manage_users =1; }
	if(!empty($_POST['manage_user_groups'])){ $manage_user_groups =1; }
	if(!empty($_POST['manage_user_rights'])){ $manage_user_rights =1; }
	if(!empty($_POST['manage_customer_groups'])){ $manage_customer_groups =1; }
	if(!empty($_POST['manage_customers'])){ $manage_customers =1; }
	if(!empty($_POST['manage_price_rules'])){ $manage_price_rules =1; }
	if(!empty($_POST['all'])){ $all =1; }
	if(!empty($_POST['nanyuki'])){ $nanyuki =1; }
	if(!empty($_POST['isiolo'])){ $isiolo =1; }
	if(!empty($_POST['timau'])){ $timau =1; }
	if ($valid) {
		$conn->query("INSERT INTO `security_groups`(`group_name`, `manage_categories`, `manage_products`, `manage_suppliers`, `receive_stock`, `transfer_stock`, `issue_stock`, `request_stock`, `make_sales`, `clear_sales`, `credit_sales`, `manage_accounts`, `manage_account_categories`, `sales_reports`, `transfer_reports`, `issue_reports`, `recieve_reports`, `manage_users`, `manage_user_groups`, `manage_user_rights`, `manage_customer_groups`, `manage_customers`, `manage_price_rules`, `all_stores`, `nanyuki`, `isiolo`, `timau`,`manage_expenses`,`reports`) VALUES ('$name','$manage_categories','$manage_products','$manage_suppliers','$receive_stock','$transfer_stock','$issue_stock','$request_stock','$make_sales','$clear_sales','$credit_sales','$manage_accounts','$manage_account_categories','$sales_reports','$transfer_reports','$issue_reports','$recieve_reports','$manage_users','$manage_user_groups','$manage_user_rights','$manage_customer_groups','$manage_customers','$manage_price_rules','$all','$nanyuki','$isiolo','$timau','$manage_expenses','$reports')") or die($conn->error);
		header('location: groups.php');
	}

}
// Edit User Rights
if (isset($_POST['group_rights_edit_submit'])) {
	$id=$_POST['group_id'];
	$name = trim($_POST['name']);
	if (empty($name)) {
		$group_name_err = "Group name must be entered";
		$valid = false;
	}
	$group_nm = $conn->query("SELECT id FROM security_groups WHERE group_name='$name' ") or die($conn->error);
	if (mysqli_num_rows($group_nm)>= 1) {
		$group_name_err = "Group name already taken";
		$valid = false;
	}
	$manage_categories = 0;
	$manage_products = 0;
	$manage_suppliers = 0;
	$receive_stock = 0;
	$transfer_stock = 0;

	$issue_stock = 0;
	$request_stock = 0;
	$make_sales = 0;


	$clear_sales = 0;
	$credit_sales = 0;
	$manage_accounts = 0;
	$manage_account_categories = 0;
	$manage_expenses = 0;

	$reports = 0;
	$sales_reports = 0;


	$transfer_reports = 0;
	$issue_reports = 0;
	$recieve_reports = 0;
	$manage_users = 0;
	$manage_user_groups = 0;
	$manage_user_rights = 0;
	$manage_customer_groups = 0;
	$manage_customers = 0;
	$manage_price_rules = 0;
	$all = 0;
	$nanyuki = 0;
	$isiolo = 0;
	$timau = 0;

	if(!empty($_POST['manage_categories'])){ $manage_categories =1; }
	if(!empty($_POST['manage_products'])){ $manage_products =1; }
	if(!empty($_POST['manage_suppliers'])){ $manage_suppliers =1; }
	if(!empty($_POST['receive_stock'])){ $receive_stock =1; }
	if(!empty($_POST['transfer_stock'])){ $transfer_stock =1; }

	if(!empty($_POST['issue_stock'])){ $issue_stock =1; }
	if(!empty($_POST['request_stock'])){ $request_stock =1; }
	if(!empty($_POST['make_sales'])){ $make_sales =1; }


	if(!empty($_POST['clear_sales'])){ $clear_sales =1; }
	if(!empty($_POST['credit_sales'])){ $credit_sales =1; }
	if(!empty($_POST['manage_accounts'])){ $manage_accounts =1; }
	if(!empty($_POST['manage_expenses'])){ $manage_expenses =1; }
	if(!empty($_POST['manage_account_categories'])){ $manage_account_categories =1; }
	if(!empty($_POST['sales_reports'])){ $sales_reports =1; }
	if(!empty($_POST['reports'])){ $reports =1; }


	if(!empty($_POST['transfer_reports'])){ $transfer_reports =1; }
	if(!empty($_POST['issue_reports'])){ $issue_reports =1; }
	if(!empty($_POST['recieve_reports'])){ $recieve_reports =1; }
	if(!empty($_POST['manage_users'])){ $manage_users =1; }
	if(!empty($_POST['manage_user_groups'])){ $manage_user_groups =1; }
	if(!empty($_POST['manage_user_rights'])){ $manage_user_rights =1; }
	if(!empty($_POST['manage_customer_groups'])){ $manage_customer_groups =1; }
	if(!empty($_POST['manage_customers'])){ $manage_customers =1; }
	if(!empty($_POST['manage_price_rules'])){ $manage_price_rules =1; }
	if(!empty($_POST['all'])){ $all =1; }
	if(!empty($_POST['nanyuki'])){ $nanyuki =1; }
	if(!empty($_POST['isiolo'])){ $isiolo =1; }
	if(!empty($_POST['timau'])){ $timau =1; }
	$conn->query("UPDATE `security_groups` SET `group_name`='$name',`manage_categories`='$manage_categories',`manage_products`='$manage_products',`manage_suppliers`='$manage_suppliers',`receive_stock`='$receive_stock',`transfer_stock`='$transfer_stock',`issue_stock`='$issue_stock',`request_stock`='$request_stock',`make_sales`='$make_sales',`clear_sales`='$clear_sales',`credit_sales`='$credit_sales',`manage_accounts`='$manage_accounts',`manage_account_categories`='$manage_account_categories',`manage_expenses`='$manage_expenses',`sales_reports`='$sales_reports',`transfer_reports`='$transfer_reports',`issue_reports`='$issue_reports',`recieve_reports`='$recieve_reports',`manage_users`='$manage_users',`manage_user_groups`='$manage_user_groups',`manage_user_rights`='$manage_user_rights',`manage_customer_groups`='$manage_customer_groups',`manage_customers`='$manage_customers',`manage_price_rules`='$manage_price_rules',`all_stores`='$all',`nanyuki`='$nanyuki',`isiolo`='$isiolo',`timau`='$timau',`reports`='$reports' WHERE id='$id'") or die($conn->error);
		header("location: groups.php");
}
// Users
$name = "";
$id_no = "";
$kra = "";
$username = "";
$email = "";
$password = "";
$cpassword = "";
$group = "";
$phone = "";
$status = 0;
// $username_err = $password_err = $confirm_password_err = "";
$name_err = $id_err = $mobile_err = $email_err ="";
$kra_err = $username_err = $pass_err = $cpass_err = "";
$valid = true;

if (isset($_POST['user_submit'])) {
	$name = trim($_POST['name']);
	$phone = trim($_POST['mobile']);
	$id_no = trim($_POST['id_no']);
	$kra = trim($_POST['kra']);
	$username = trim($_POST['username']);
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$cpassword = trim($_POST['cpassword']);
	$group = trim($_POST['group']);

	// Validate name
	if(empty($name)){
		$name_err = "Please enter a name.";
		$valid = false;
	}
	if(!is_numeric($phone)){
		$mobile_err = "Please valid mobile number.";
		$valid = false;
	}
	if(strlen($phone) < 10){
		$mobile_err = "Phone number must have atleast 10 characters.";
		$valid = false;
	}
	if(!is_numeric($id_no)){
		$id_err = "Please valid ID number.";
		$valid = false;
	}
	if(empty($username)){
		$username_err = "Please enter a unique username.";
		$valid = false;
	}
	$res = $conn->query("SELECT id FROM users WHERE username='$username' ") or die($conn->error);
	if(mysqli_num_rows($res) >= 1){
		$username_err = "Username is already taken.";
		$valid = false;
	}
	if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
	 	$name_err = "Only letters and white space allowed";
	 	$valid = false;
	}
	// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// 	$email_err = "Email is invalid";
	// 	$valid = false;
	// }
	if(empty($password)){
		$pass_err = "Please enter a strong password.";
		$valid = false;
	}
	if(strlen($password) < 6){
		$pass_err = "Password must have atleast 6 characters.";
		$valid = false;
	}
	if(empty($cpassword)){
		$cpass_err = "Please confirm your password.";
		$valid = false;
	}else{
		if($password != $cpassword){
			$cpass_err = 'Password did not match.';
			$valid = false;
		}
	}

	if (!empty($_POST['user_activate'])) {
		$status = 1;
	}
	if ($valid) {
		$password = md5($password);
		$conn->query("INSERT INTO `users`(`name`, `mobile`, `username`, `password`, `national_id`, `kra_pin`, `email`, `user_group`,`status`,`vendor`) VALUES ('$name','$phone','$username','$password','$id_no','$kra','$email','$group','$status','CGS') ") or die($conn->error);
		header('location: users.php');
	}
}

if (isset($_GET['user_edit'])) {
	$id = $_GET['user_edit'];
	$user = $conn->query("SELECT * FROM users WHERE id='$id' ") or die($conn->error);
	if (mysqli_num_rows($user) == 1) {
		$row = $user->fetch_array();
		$name = $row['name'];
		$phone = $row['mobile'];
		$id_no = $row['national_id'];
		$kra = $row['kra_pin'];
		$username = $row['username'];
		$email = $row['email'];
	}
}

if (isset($_GET['user_delete'])) {
	$id = $_GET['user_delete'];
	$conn->query("DELETE FROM users WHERE id='$id' ") or die($conn->error);
	header('location: users.php');
}
?>

 ?>

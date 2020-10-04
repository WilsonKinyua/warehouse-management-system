<?php
include '../utils/conn.php';
$json = file_get_contents('php://input');
// Converts it into a PHP object
"""
Format:
{
  "username":"---",
  "password":"---"
}

"""
$data = json_decode($json);
$username = $data["username"];
$password = $data['password'];

public function fetchDetails()
{
  // Check the Db for credentials
	$res = $conn->query("SELECT * FROM users WHERE username='$username' ") or die($conn->error);
	if (mysqli_num_rows($res) == 1) {
		$row = $res->fetch_assoc();
		$hashed_password = $row['password'];
		if (password_verify($password, $hashed_password)) {
			$userID = $row['id'];
      sendQuantities($userID)
		}else{
			echo "Incorrect Password";
		}
	}else{
		echo "Username not found";
	}
}

public function sendQuantities($value)
{
  $res = $conn->query("SELECT * FROM stock_mobile WHERE store_owner='$value'");
  echo json_encode($res,JSON_PRETTY_PRINT);
}
 ?>

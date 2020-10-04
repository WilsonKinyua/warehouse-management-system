<?php
include '../utils/conn.php';
session_start();

$postDetails = array();

$search_key = $_GET['term'];

//get rows query
$query = "SELECT * FROM products where name like '%$search_key%'";
$result = mysqli_query($conn, $query);

//number of rows
$rowCount = mysqli_num_rows($result);

if($rowCount > 0){
    while($row = mysqli_fetch_assoc($result)){
			$postDetails[] = ucfirst($row['name']);
	}
}
echo json_encode($postDetails);
?>

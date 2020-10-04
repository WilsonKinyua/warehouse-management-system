<?php
include '../../utils/conn.php';
// 'name','category','price','selling_cost','distributor','stockist','wholesale','retail'
if(isset($_POST["name"], $_POST["category"],$_POST['price'], $_POST['selling_cost']))
{
 $name = mysqli_real_escape_string($conn, $_POST["name"]);
 $category = mysqli_real_escape_string($conn, $_POST["category"]);
 $price = mysqli_real_escape_string($conn, $_POST["price"]);
 $selling_cost = mysqli_real_escape_string($conn, $_POST["selling_cost"]);
 $distributor = mysqli_real_escape_string($conn, $_POST["distributor"]);
 $stockist = mysqli_real_escape_string($conn, $_POST["stockist"]);
 $wholesale = mysqli_real_escape_string($conn, $_POST["wholesale"]);
 $retail = mysqli_real_escape_string($conn, $_POST["retail"]);
 $query = "INSERT INTO `products`(`name`, `category`, `price`, `selling_cost`, `distributor`, `stockist`, `wholesale`, `retail`) VALUES('$name', '$category','$price', '$selling_cost','$distributor', '$stockist','$wholesale', '$retail')";
 if(mysqli_query($conn, $query))
 {
  echo 'Data Inserted';
 }
}else {
  echo "Failed";
}
?>
